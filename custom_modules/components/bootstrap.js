module.exports = function(server, app, Statistics){
    Models = require('../sequelize.js')
    _lodash = require('lodash')
    moment = require('moment')
    phpdate = require('phpdate-js')
    gmdate = require('phpdate-js').gmdate
    Services = require('./services.js')
    Conversations = require('./conversations.js')
    Users = require('./users.js')
    Status = require('./status.js')
    Config = require('./config.js')

    Models.TblSettings.findAll()
    .then(function(settings){
        _lodash.forEach(settings, function(setting){
            Config[setting.name] = Number(setting.value)
        })
        console.log('Bootstrap: Settings Successfully loaded!')
    })

    Models.TblServices.findAll({
      attributes: ['id','status','name','allow_multiple_reply','message_limit','min_char','max_char','color_theme','timezone'],
      where : { 'status' : 'active'},
      include: {
        model: Models.TblCannedMessages,
        as: 'CannedMessages'
      }
    })
    .then(function(rows){
        _lodash.forEach(rows, function(service) {
            //service.dataValues.canned = service.CannedMessages
            Services[service.id] = service.dataValues
            Services[service.id].queue = {}
            Services[service.id].users = {}
            Services[service.id].pending = 0
        })
        console.log('Bootstrap: Services Successfully loaded!')
        console.log('Bootstrap: Starting Stats Fetching!')
        
        setInterval(function(){
            Statistics.startStatsUpdate()
        }, Config.stats_update_interval)

        LoadUnhandledConversations()
    })

    LoadUnhandledConversations = function LoadUnhandledConversations(){
        Models.TblConversations.getUnhandledConversations(function(rows){
            conv_ids = []
            // boostrap_time = moment().format('YYYY-MM-DD HH:mm:ss')
            boostrap_time = gmdate('Y-m-d H:i:s')
            _lodash.forEach(rows, function(conversation){
                conversation.date = conversation.date
                conversation.message_bound_time = moment(conversation.date).tz(Config.db_timezone).format('YYYY-MM-DD HH:mm:ss')
                conversation.caller = 'bootstrap'
                conversation.messages = {}
                conversation.latest_message_ids = []

                if(conversation.wait_start != undefined){
                    conversation.wait_start = moment(conversation.assigned_latest).tz(Config.db_timezone).format('YYYY-MM-DD HH:mm:ss')
                }else{
                    conversation.wait_start = moment(conversation.date).tz(Config.db_timezone).format('YYYY-MM-DD HH:mm:ss')
                }

                Services[conversation.service_id].queue[conversation.id] = {id : conversation.id, 
                                                                            conversation_id : conversation.id, 
                                                                            service_id : conversation.service_id, 
                                                                            persona_id : conversation.persona_id,
                                                                            status : conversation.status,  
                                                                            date : conversation.date}
                if(conversation.user_id)
                    conversation.user_id = conversation.user_id.toString()

                conversation.messages[conversation.message_id] = { id : conversation.message_id, user_id : conversation.user_id, content : conversation.message, type : conversation.type }
                conversation['last_message_id'] = conversation.message_id
                conversation['last_message_type'] = conversation.type
                conversation.conversation_id = conversation.id

                if(conversation.status == 'assigned' && Users[conversation.user_id] != undefined){
                    // conversation.assigned_start = moment(conversation.assigned_start).tz(Config.db_timezone).format("YYYY-MM-DD HH:mm:ss")
                    // conversation.messages[conversation.message_id].date = conversation.assigned_start
                    // *** WILL COMMENT LINES ABOVE SINCE MESSAGES WILL BE LOADED LATER ON ***

                    if(Services[conversation.service_id].users[conversation.user_id] == undefined){
                        Services[conversation.service_id].users[conversation.user_id] = {id : conversation.user_id, wait_start:conversation.date, queued:0, status:'available', message_sent : 0}
                    }

                    Services[conversation.service_id].queue[conversation.id].user_id = conversation.user_id
                    Services[conversation.service_id].users[conversation.user_id].queued += 1
                    Services[conversation.service_id].users[conversation.user_id].status = Services[conversation.service_id].users[conversation.user_id].queued == Config.max_operator_conversation ? 'unavailable' : 'available'
                }
                else{
                    Services[conversation.service_id].pending++
                    Services[conversation.service_id].queue[conversation.id].status = 'pending'
                    conversation.status = 'queued'
                }
                conv_ids.push(conversation.id)

                // THIS WILL FETCH 20 LATEST MESSAGES OF THE CONVERSATION
                // NOTE: THIS IS A QUERY IN ALL CONVERSATIONS
                // NOTE: NEEDS TO BE MONITORED FOR SPEED
                Models.TblMessages.findAll({
                    attributes: ['id', 'message', 'bound_time', 'direction', 'additional_info', 'status', 'user_id'],
                    where: {conversation_id: conversation.id},
                    include: [
                        {model: Models.TblOutboundMessageAttachments, as: 'attachments'},
                        {model: Models.TblUsers, as: 'tbl_users'},
                    ],
                    limit: Config.max_message_per_conversation,
                    order: [['id', 'DESC']]
                })
                .then(function(rows){
                    _lodash.forEach(rows, function(messagedata) {

                        conversation.messages[messagedata.dataValues.id] = 
                        conversation.messages[messagedata.dataValues.id] = {
                            id : messagedata.dataValues.id, 
                            content : messagedata.dataValues.message, 
                            type : messagedata.direction, 
                            status : messagedata.dataValues.status, 
                            date: messagedata.dataValues.bound_time,
                            attachments:  messagedata.dataValues.attachments,
                            additional_info: messagedata.dataValues.additional_info,
                            user: messagedata.dataValues.tbl_users != null ? messagedata.dataValues.tbl_users.dataValues.username : null}

                        if(messagedata.dataValues.direction == 'inbound' && (messagedata.dataValues.status == 'normal' || messagedata.dataValues.status == 'assigned')){
                            conversation.latest_message_ids.push(messagedata.dataValues.id)
                        }
                    })

                    Conversations.list[conversation.id] = conversation
                    Conversations.logs[conversation.id] = [{"time": boostrap_time, "message": 'Conversation initialized from bootstrap.'}]
                })
            })
            console.log('Bootstrap: Conversations Successfully loaded!')
            
            if(conv_ids.length > 0){
                Conversations.processConversationMetaData(conv_ids, false, function(response){
                    console.log('Bootstrap: Conversations Metadata Successfully loaded!')
                    console.log('Express server listening on port %d in %s mode', app.get('port'), app.get('env'))
                    server.listen(app.get('port'))
                    Status.broadcast_update()
                })
            }
            else{
                server.listen(app.get('port'))
                Status.broadcast_update()
                console.log('Express server listening on port %d in %s mode', app.get('port'), app.get('env'))
            }

        })
    }
}
