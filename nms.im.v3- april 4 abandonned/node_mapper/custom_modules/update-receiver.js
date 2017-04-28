module.exports = function(io,Statistics, redisClient) {
    //INCLUDE DEPENDENCIES
    Services = require('./components/services.js')
    Conversations = require('./components/conversations.js')
    History = require('./components/history.js')
    Reports = require('./components/reports.js')
    Users = require('./components/users.js')
    Config = require('./components/config.js')
    redis = require('redis')
    moment = require('moment')
    _lodash = require('lodash')
    gmdate = require('phpdate-js').gmdate

    redis_client_ur = redis.createClient()
    redis_client_update = redisClient
    redis_client_update.on("error", function (err) {
        console.log("Error redis_client_update" + err);
    })
    
    redis_client_update.subscribe('update-data')
    redis_client_update.on("message", function (channel, message) {
        if(channel === 'update-data'){
            receive_update_process(JSON.parse(message))
        }
    })

    receive_update_process = function(update_data){
        var parking_loop = 0;
        var logout_loop = 0;

        if(update_data.action == 'flag_message'){
           flag_message(update_data)
        }
        if(update_data.action == 'blacklist'){
           blacklist_subscriber(update_data)
        }
        if(update_data.action == 'update_conversation_notes'){
           update_conversation_note(update_data)
        }
        if(update_data.action == 'delete_conversation_note'){
           delete_conversation_note(update_data)
        }
        if(update_data.action == 'park'){
            unassign_conversation_on_park(update_data, parking_loop)
        }
        if(update_data.action == 'logout'){
            unassign_conversation_on_logout(update_data, logout_loop)
        }
        if(update_data.action == 'discard'){
            discard_conversation(update_data)
        }
        if(update_data.action == 'update_user_services'){
            update_user_services(update_data)
        }
        if(update_data.action == 'new_service'){
            new_service(update_data)
        }
        if(update_data.action == 'update_service'){
            update_service(update_data)
        }
    }

    flag_message = function(update_data){
        Conversations.list[update_data.conversation_id].messages[update_data.message_id].flagged = true
        Conversations.list[update_data.conversation_id].messages[update_data.message_id].flagger_id = update_data.user_id
    }

    blacklist_subscriber = function(update_data){
        subscriberConversations = _lodash.filter(Conversations.list, {'subscriber_id' : update_data.subscriber});
        
        _lodash(subscriberConversations).forEach(function(conversation){
            Conversations.logs[conversation.id].push({time:moment().format('YYYY-MM-DD HH:mm:ss'), message:'Subscriber has been blacklisted by ->' + Users[update_data.user_id].username})
            
            if((conversation.user_id != undefined || conversation.user_id != null) && Users[conversation.user_id] != undefined){
                Conversations.removeTimeout(conversation.conversation_id)
                io.of('/chat').to(Users[conversation.user_id].socket_id).emit('unmap_blacklisted_conversation', conversation.conversation_id)
                Services.updateQueue('decrement', conversation.user_id)
                conv_index = Users[conversation.user_id].conversation_ids.indexOf(conversation.conversation_id)
                
                if(conv_index != -1){
                    Users[conversation.user_id].conversation_ids.splice(conv_index, 1)
                    Status.update_users('conversation_ids', {id : conversation.user_id, conversation_ids : Users[conversation.user_id].conversation_ids})
                }
            }

            conversation.user_id = Config.bot_id
            conversation.fullname = Config.bot_fullname
            conversation.handle_time = gmdate('Y-m-d H:i:s')

            Conversations.handle_blacklisted_subscriber_messages(conversation);
            Conversations.deleteIdleConversation(conversation); 
        })
    }

    discard_conversation = function(update_data){
        Conversations.logs[update_data.conversation_id].push({time:moment().format('YYYY-MM-DD HH:mm:ss'), message:'Message discarded by ->' + Users[update_data.user_id].username})
        update_data.service_name = Services[update_data.service_id].name
        update_data.persona_id = Conversations.list[update_data.conversation_id].persona_id
        update_data.persona_name = Conversations.list[update_data.conversation_id].persona_name
        update_data.subscriber_id = Conversations.list[update_data.conversation_id].subscriber_id
        update_data.subscriber_name = Conversations.list[update_data.conversation_id].subscriber_name
        
        Conversations.deleteIdleConversation(update_data);
        Services.updateQueue('decrement', update_data.user_id)
        Conversations.discardMessage(update_data);
        delete Conversations.list[update_data.conversation_id];            
    }

    update_conversation_note = function(update_data){
        if(Conversations.list[update_data.cid] != undefined){
            if(Conversations.list[update_data.cid].conversation_notes == undefined)
                Conversations.list[update_data.cid]['conversation_notes'] = {}

            Conversations.logs[update_data.cid].push({time:moment().format('YYYY-MM-DD HH:mm:ss'),message:'Conversation note [' + update_data.note.id + "] has been added by -> " + Users[update_data.note.user_id].username})
            Conversations.list[update_data.cid].conversation_notes[update_data.note.id] = update_data.note
        }
    }
    
    delete_conversation_note = function(update_data){
        console.log(update_data);
        if(Conversations.list[update_data.cid] != undefined && Conversations.list[update_data.cid].conversation_notes != undefined){
            Conversations.logs[update_data.cid].push({time:moment().format('YYYY-MM-DD HH:mm:ss'), message:'Conversation note [' + update_data.note_id + "] has been deleted by -> " + Users[update_data.user_id].username})
            delete Conversations.list[update_data.cid].conversation_notes[update_data.note_id]
        }
    }

    /*
    * Firefox and Chrome are disconnecting chat socket at different time
    * Firefox disconnects chat socket of user immediately before calling redis update
    * Chrome calls update receiver first before socket disconnection
    * NOTE: We will do cheking here if User socket is still saved; if not undefined call again function
    */
    unassign_conversation_on_park = function(update_data, loop){
        console.log('User ['+ update_data.user_id +'] parked.')
    
        if(update_data.conversations != undefined && update_data.conversations.length > 0){
            if(Users[update_data.user_id] != undefined && Users[update_data.user_id].socket_id != undefined){
                if(loop <= Config.max_parking_loop){
                    setTimeout(function(){
                        loop += 1
                        unassign_conversation_on_park(update_data, loop)
                    }, 500)
                }
                else{
                    //disconnect user's chat socket forcefully
                    io.of('/chat').to(Users[update_data.user_id].socket_id).emit('force_disconnection', Config.prompt.disable_chat)
                    if(io.of('/chat').sockets[Users[update_data.user_id].socket_id] != undefined){
                        io.of('/chat').sockets[Users[update_data.user_id].socket_id].disconnect()
                    }
                }
            }
            else{
                _lodash(update_data.conversations).forEach(function(data){
                    if(Conversations.list[data.conversation_id] != undefined && Conversations.list[data.conversation_id].last_message_type == 'outbound'){
                        data.service_id = Conversations.list[data.conversation_id].service_id
                        Conversations.deleteIdleConversation(data)
                    }

                    if(Conversations.list[data.conversation_id] != undefined && Conversations.list[data.conversation_id].status == "assigned" && Conversations.list[data.conversation_id].last_message_type != 'outbound'){
                       
                        Conversations.logs[data.conversation_id].push({time:moment().format('YYYY-MM-DD HH:mm:ss'),message:'User ' + update_data.username + ' parked'})        
                        Conversations.removeTimeout(data.conversation_id)
                        Conversations.unmapActiveConversation(Conversations.list[data.conversation_id])        
                    }
                })
            }
        }
        else{
            if(Users[update_data.user_id] != undefined && Users[update_data.user_id].socket_id != undefined){
                //disconnect user's chat socket forcefully
                io.of('/chat').to(Users[update_data.user_id].socket_id).emit('force_disconnection', Config.prompt.disable_chat)
                io.of('/chat').sockets[Users[update_data.user_id].socket_id].disconnect()
            }
        }
    }
    unassign_conversation_on_logout = function(update_data, loop){
        console.log('User ['+ update_data.user_id +'] logged out.')
        if(update_data.conversations != undefined && update_data.conversations.length > 0){
            _lodash(update_data.conversations).forEach(function(data){
                if(Conversations.list[data.conversation_id] != undefined && Conversations.list[data.conversation_id].last_message_type == 'outbound'){
                    data.service_id = Conversations.list[data.conversation_id].service_id
                    Conversations.deleteIdleConversation(data)
                }

                if(Conversations.list[data.conversation_id] != undefined && Conversations.list[data.conversation_id].status == "assigned" && Conversations.list[data.conversation_id].last_message_type != 'outbound'){
                    Conversations.logs[data.conversation_id].push({time:moment().format('YYYY-MM-DD HH:mm:ss'), message:'User ' + update_data.username + ' logged out'})        
                    Conversations.removeTimeout(data.conversation_id)
                    Conversations.unmapActiveConversation(Conversations.list[data.conversation_id])        
                }
            })
            disconnect_sockets_on_logout(update_data)
        }
        else{
            disconnect_sockets_on_logout(update_data)
        }
    }
    disconnect_sockets_on_logout = function(update_data){
        console.log('Disconnecting User ['+ update_data.user_id +'] sockets on logout')
        if(Users[update_data.user_id] != undefined && Users[update_data.user_id].socket_id != undefined){
            //disconnect user's chat socket forcefully
            io.of('/chat').to(Users[update_data.user_id].socket_id).emit('force_disconnection', Config.prompt.user_logout)
            if(io.of('/chat').sockets[Users[update_data.user_id].socket_id] != undefined){
                io.of('/chat').sockets[Users[update_data.user_id].socket_id].disconnect()
            }
        }

        if(Statistics.users[update_data.user_id] != undefined && Statistics.users[update_data.user_id].length > 0){
            _lodash(Statistics.users[update_data.user_id]).forEach(function(socket_id){
                io.of('/statistics').to(socket_id).emit('force_disconnection', Config.prompt.user_logout)
                io.of('/statistics_client').to(socket_id).emit('force_disconnection', Config.prompt.user_logout)
                
                if(io.of('/statistics').sockets[socket_id] != undefined) io.of('/statistics').sockets[socket_id].disconnect()
                if(io.of('/statistics_client').sockets[socket_id] != undefined) io.of('/statistics_client').sockets[socket_id].disconnect()
            })
            delete Statistics.users[update_data.user_id]
        }

        if(Reports.users[update_data.user_id] != undefined && Reports.users[update_data.user_id].length > 0){
            _lodash(Reports.users[update_data.user_id]).forEach(function(socket_id){
                io.of('/reports').to(socket_id).emit('force_disconnection', Config.prompt.user_logout)
                if(io.of('/reports').sockets[socket_id] != undefined) io.of('/reports').sockets[socket_id].disconnect()
            })
            delete Reports.users[update_data.user_id]
        }

        if(History.users[update_data.user_id] != undefined && History.users[update_data.user_id].length > 0){
            _lodash(History.users[update_data.user_id]).forEach(function(socket_id){
                io.of('/history').to(socket_id).emit('force_disconnection', Config.prompt.user_logout)
                if(io.of('/history').sockets[socket_id] != undefined) io.of('/history').sockets[socket_id].disconnect()
            })
            delete History.users[update_data.user_id]
        }
    }

    update_user_services = function(data){
        if(data.panel == 'users'){
            if(data.sub_action == 'add'){
                update_user_added_service(data.user, data.services)
            }
            else if(data.sub_action == 'delete'){
                update_user_services_on_chat(data.user, data.services);
            }
        }

        if(data.panel == 'services'){
            if(data.sub_action == 'add'){
                for(i in data.users){
                    update_user_added_service(data.users[i], [data.service])
                }
            }
            else if(data.sub_action == 'delete'){
                for(i in data.users){
                    update_user_services_on_chat(data.users[i], [data.service]);
                }
            }
        }
    }

    update_user_added_service = function(uid, services){
        //to users connected to statistics socket namespace
        if(Statistics.users[uid] != undefined){
            emit_new_service_notif(Statistics.users[uid], '/statistics')
        }

        //to users connected to history socket namespace
        if(History.users[uid] != undefined){
            emit_new_service_notif(History.users[uid], '/history')
        }

        //to users connected to reports socket namespace
        if(Reports.users[uid] != undefined){
            emit_new_service_notif(Reports.users[uid], '/reports')
        }

        //to users connected to chat socket namespace
        if(Users[uid] != undefined){
            redis_client_ur.get('user_services:'+uid, function(err, result){

                now = gmdate('Y-m-d H:i:s')
                user_services = JSON.parse(result)
                Users[uid].services = user_services
                
                service_status = {}
                for(i in services){
                    if(Users[uid].services.length == 0) Services[services[i]].users[uid] = {id : uid, wait_start : now, queued:0, status : 'available', message_sent : 0}
                    else Services[services[i]].users[uid] = Services[Users[uid].services[0]].users[uid]
                    service_status[services[i]] = Services[services[i]]
                }

                Status.update_users('services', Users[uid])
                Status.update_services('users', service_status)
                emit_new_service_notif([Users[uid].socket_id], '/chat')
            })
        }
    }

    update_user_services_on_chat = function(uid, services){
        if(Users[uid] != undefined){
            redis_client_ur.get('user_services:'+uid, function(err, result){
                now = gmdate('Y-m-d H:i:s')
                user_services = JSON.parse(result)
                Users[uid].services = user_services
                
                service_status = {}
                for(i in services){
                    if(Services[services[i]].users[uid] != undefined){
                        service_status[services[i]] = Services[services[i]]
                        delete Services[services[i]].users[uid]
                    }
                }

                io.of('/chat').to(Users[uid].socket_id).emit('unassigned_service')
                Status.update_users('services', Users[uid])
                Status.update_services('users', service_status)
            })
        }
    }   

    emit_new_service_notif = function(socket_ids, namespace){
        for(i in socket_ids){
            message = 'A new service has been assigned'
            if(namespace == '/chat')
                io.of(namespace).to(socket_ids[i]).emit('new_service_notif_onchat', message)
            else io.of(namespace).to(socket_ids[i]).emit('new_service_notif', message)
        }
    }

    new_service = function(data){
        sid = data.service.id
        if(Services[sid] == undefined){
            Services[sid] = data.service
            Services[sid].allow_multiple_reply = parseInt(data.service.allow_multiple_reply)
            Services[sid].allow_blacklist = parseInt(data.service.allow_blacklist)
            Services[sid].message_limit = parseInt(data.service.message_limit)
            Services[sid].min_char = parseInt(data.service.min_char)
            Services[sid].max_char = parseInt(data.service.max_char)
            Services[sid].queue = {}
            Services[sid].users = {}
            Services[sid].pending = 0
            console.log(Services)
        }
    }

    update_service = function(data){
        sid = data.service.id
        if(Services[sid] != undefined){
            Services[sid].status = data.service.status
            Services[sid].name = data.service.name
            Services[sid].allow_multiple_reply = parseInt(data.service.allow_multiple_reply)
            Services[sid].allow_blacklist = parseInt(data.service.allow_blacklist)
            Services[sid].message_limit = parseInt(data.service.message_limit)
            Services[sid].min_char = parseInt(data.service.min_char)
            Services[sid].max_char = parseInt(data.service.max_char)
            Services[sid].color_theme = data.service.color_theme
            Services[sid].timezone = data.service.timezone
        }
    }

} // --END-- /