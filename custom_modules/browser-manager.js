module.exports = function(io,Statistics) {
    //INCLUDE DEPENDENCIES
    Models = require('./sequelize.js')
    Users = require('./components/users.js')
    Services = require('./components/services.js')
    Status = require('./components/status.js')
    Config = require('./components/config.js')
    Conversations = require('./components/conversations.js')
    Reports = require('./components/reports.js')
    History = require('./components/history.js')
    DevTools = require('../mock_modules/dev-tools.js')
    moment = require('moment')
    async = require('async')
    redis = require('redis')
    _lodash = require('lodash')
    const isOnline = require('is-online')

    redis_client_bm = redis.createClient()
    redis_client_bm.on("error", function (err) {
        console.log("Error redis_client_bm" + err);
    })

    var chat_status = io.of('/chat_status')

    chat_status.on('connection', function(client_socket){
        client_socket.emit('receive-services', Services)
        client_socket.emit('receive-conversations', Conversations.list)
        client_socket.emit('receive-users', Users)
        client_socket.emit('receive-settings', Config)

        console.log('new "STATUS" connection. id:' + client_socket.id)

        client_socket.on('update_config', function(config){
            if(config != undefined && config.user_id != undefined && 
                config.name != undefined && config.value != undefined){
                Models.TblSettingsLogs.create({
                    user_id : config.user_id,
                    setting : config.name,
                    old_value : Config[config.name],
                    new_value : config.value,
                    date_created : moment().format('YYYY-MM-DD HH:mm:ss')
                })
                .then(function(result){
                    Models.TblSettings.update({
                        value : config.value
                    },
                    {
                        where : { name : config.name }
                    })
                    .then(function(){
                        Config[config.name] = config.value
                        client_socket.emit('update_config_callback', { status : 'success', name : config.name })
                    }, function(){
                        client_socket.emit('update_config_callback', { status : 'fail', name : config.name })
                    })
                })
            }
        })

        client_socket.on('disconnect', function() {
            console.log('disconnected chat_status id:' + client_socket.id)
        })
    })

    var chat = io.of('/chat')

    chat.on('connection', function(client_socket) {
        DevTools.isConnectionEnabled(client_socket)
        console.log('new "CHAT" connection:' + client_socket.id)
        console.log(client_socket.request.connection.remoteAddress)

        client_socket.on('disconnect', function() {
            console.log("browser manager: chat disconnected");
            if(client_socket.user_data != undefined && Users[client_socket.user_data.id] != undefined) 
            {
                Status.delete('users', client_socket.user_data.id)
                _lodash.forEach( Users[client_socket.user_data.id].services, function(service) {
                    if(Services[service] != undefined && Services[service].users != undefined)
                        delete Services[service].users[client_socket.user_data.id]
                })

                require('dns').resolve('www.google.com', function(err) {
                  if (err) {
                     console.log("->>>>>>>>>>>>>>>>>>   No connection");
                  } else {
                     console.log("->>>>>>>>>>>>>>>>>>   Connected");
                  }
                });
                isOnline().then(online => {
                    // console.log(">>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>");
                    //=> true 
                });

                delete Users[client_socket.user_data.id]
            }
            else{
                console.log('oops! something wrong on chat disconnect!')
                console.log(client_socket.user_data)
            }

            console.log('disconnected chat id:' + client_socket.id)
        })

        client_socket.on('user_data', function(user_data){
            if(Users[user_data.id] != undefined){
                client_socket.emit('force_disconnection', Config.prompt.double_chat)
                client_socket.disconnect()
            }

            redis_client_bm.getAsync('stat_socket:'+user_data.id).then(function(stat_socket){
                stat_socket = JSON.parse(stat_socket)
                if(stat_socket.socketId != null && io.of('/statistics').sockets[stat_socket.socketId] != undefined && io.of('/statistics').sockets[stat_socket.socketId].onPark){
                    io.of('/statistics').sockets[stat_socket.socketId].emit('force_disconnection', Config.prompt.disable_park)
                    io.of('/statistics').sockets[stat_socket.socketId].disconnect()
                }

                if(stat_socket.socketId != null && io.of('/statistics_client').sockets[stat_socket.socketId] != undefined && io.of('/statistics_client').sockets[stat_socket.socketId].onPark){
                    io.of('/statistics_client').sockets[stat_socket.socketId].emit('force_disconnection', Config.prompt.disable_park)
                    io.of('/statistics_client').sockets[stat_socket.socketId].disconnect()
                }

                // if(client_socket.connected && stat_socket == null){
                if(client_socket.connected){
                    stat_socket.onPark = 0
                    redis_client_bm.set('stat_socket:'+user_data.id, JSON.stringify(stat_socket))
                    now = moment().format('YYYY-MM-DD HH:mm:ss')
                    Statistics.getUserStatistics(client_socket, user_data)

                    redis_client_bm.get('user_services:'+user_data.id, function(err, reply){
                        console.log(reply)
                        if(reply != null){
                            user_data.services = JSON.parse(reply)
                            setTimeout(function(){
                                Services.tryFetchConversationOnQueue(client_socket, user_data.services, user_data)}, 500)
                        }
                    })

                }
            })
        })
        //outbound node starts here.
        client_socket.on('remove_timeout', function(cid){
            Conversations.removeTimeout(cid)
        })

        client_socket.on('user_unmap_conversation', function(cid){
            console.log('conversation ['+ cid +'] has been closed by user ['+ client_socket.user_data.id +']')
            data = Conversations.list[cid]
            logs = Conversations.logs[cid]

            if(Conversations.logs[cid] != undefined){
                Conversations.logs[cid].push({"time":gmdate('Y-m-d H:i:s'), "message":'Conversation has been closed by user ' + data.user_id + ' | last message type: ' + data.last_message_type})
                logs.push({"time":gmdate('Y-m-d H:i:s'), "message":'Conversation has been closed by user ' + data.user_id + ' | last message type: ' + data.last_message_type})
            }

            if(Conversations.list[cid] != undefined){
                if(Conversations.list[cid].last_message_type == 'outbound'){
                    client_socket.emit('unmap_conversation', {conversation_id : data.conversation_id, from : 'delete'})
                    Services.updateQueue('decrement', data.user_id)
                    Conversations.deleteIdleConversation(data)
                }
                else{
                    Conversations.removeTimeout(cid)
                    Conversations.unmapActiveConversation(data)
                }
            }
            else Models.TblConversations.getLastMessageType(cid, data, logs)
        })

        client_socket.on('update_note_type', function(note){
            Conversations.updateNoteType(note)
        })
    })

    var reports_socket = io.of('/reports')

    reports_socket.on('connection', function(client_socket){
        DevTools.isConnectionEnabled(client_socket)
        console.log('new "REPORTS" connection:' + client_socket.id)

        client_socket.on('disconnect', function() {
            console.log('disconnected user on reports: ' + client_socket.id)
        })

        client_socket.on('user_data', function(user_data){
            Array.isArray(Reports.users[user_data.id]) ? Reports.users[user_data.id].push(client_socket.id) : Reports.users[user_data.id] = [client_socket.id]
            Statistics.getUserStatistics(client_socket, user_data)

            Models.TblUsers.findOne({
                where : {id: user_data.id},
                include: {
                    model: Models.TblRoles,
                    as: 'role'
                  }
            }).then(function(data){
                user_data.username = data.dataValues.username
                user_data.permissions = JSON.parse(data.dataValues.role.dataValues.permissions)

                Reports.user_data = user_data
                Reports.client_socket = client_socket
                Reports.initialize_reports(client_socket)

                client_socket.on('get_tz_date_ranges', function(tz){
                    Reports.get_date_preset(tz)
                })

                client_socket.on('generate_report', function(data){
                    data.log_sdate = moment().tz(Config.db_timezone).format('YYYY-MM-DD HH:mm:ss')
                    Reports.generate_report(data)
                })

                client_socket.on('get_logs', function(params){
                    Reports.get_logs(params)
                })

                //test report without page refresh
                // data = { display: 'average_response_time_per_hour',
                //           filter: 'service',
                //           preset: 'custom',
                //           sdate: '2016-11-02 00:00:00',
                //           edate: '2016-11-02 23:59:59',
                //           utype: '0',
                //           timezone: 'Asia/Hong_Kong'
                // }
                // Reports.generate_report(data)
                
                // date_test = {
                //     start: '2016-11-02 00:00:00',
                //     end: '2016-11-02 23:59:59'}

                // date_test.startTzFormatted = moment(moment.tz(date_test.start, "GMT")).tz("GMT").format('YYYY-MM-DD HH:mm:ss');
                // date_test.endTzFormatted = moment(moment.tz(date_test.end, "GMT")).tz("GMT").format('YYYY-MM-DD HH:mm:ss');
                // console.log(date_test);
                // console.log(moment().tz(Config.db_timezone).format('YYYY-MM-DD HH:mm:ss'));
                // console.log(moment(moment.tz("2016-11-02 02:00:00", "GMT")).tz("Asia/Manila").format('YYYY-MM-DD HH:mm:ss'))
            })
        })
    })

    var history_socket = io.of('/history')
    history_socket.on('connection', function(client_socket){
        DevTools.isConnectionEnabled(client_socket)
        console.log('new "History" connection:' + client_socket.id)

        client_socket.on('disconnect', function() {
            console.log('disconnected user on history: ' + client_socket.id)
        })

           client_socket.on('user_data', function(user_data){
            Array.isArray(History.users[user_data.id]) ? History.users[user_data.id].push(client_socket.id) : History.users[user_data.id] = [client_socket.id]
            Statistics.getUserStatistics(client_socket, user_data)
            
            Models.TblRoles.findOne({
                where : {id: user_data.role_id},
            })
            .then(function(data){
                user_data.permissions = JSON.parse(data.permissions)
                History.user_data = user_data
                History.client_socket = client_socket
                History.initialize_history(client_socket);

                  client_socket.on('get_tz_date_ranges', function(tz){
                    History.get_date_preset(tz)
                })

                client_socket.on('get_conversations', function(data){
                    console.log(data)
                    History.get_conversations(data)
                })
            })
        })


    })
} // end of module