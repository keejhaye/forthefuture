module.exports = function(io,Statistics,redisClient) {
    //INCLUDE DEPENDENCIES
    Models = require('./sequelize.js')
    Users = require('./components/users.js')
    Services = require('./components/services.js')
    Conversations = require('./components/conversations.js')
    Config = require('./components/config.js')
    Status = require('./components/status.js')
    redis = require('redis')
    moment = require('moment')
    gmdate = require('phpdate-js').gmdate
    _lodash = require('lodash')

    // redis_client_inbound = redis.createClient()
    redis_client_inbound = redisClient
    redis_client_inbound.on("error", function (err) {
        console.log("Error redis_client_inbound" + err);
    })

    redis_client_inbound.subscribe('api-inbound')
    redis_client_inbound.on("message", function (channel, message) {
        if(channel === 'api-inbound'){
            receive_inbound_process(JSON.parse(message))
        }
    })

    function receive_inbound_process(data){
        console.log('received data from php redis publish! Conversation ['+ data.conversation_id +']')
        data.date = now = gmdate('Y-m-d H:i:s')
        data.status = 'pending'
        data.message_id = data.id
        
        Conversations.createIfNotExists(data, now) //CREATE CONV OBJECT IF IT DOES NOT EXIST
        Conversations.logs[data.conversation_id].push({"time":gmdate('Y-m-d H:i:s'), "message":'New inbound has been received'})

        ///////////////////////////////////////////////////
        // AFTER Conversations.createIfNotExists(data):
        //     { id: 480111,
        //       conversation_id: 480111,
        //       status: 'new',
        //       wait_start: '2016-10-18 13:50:15',
        //       date: '2016-10-18 13:50:15',
        //       service_id: 75,
        //       persona_id: 40033,
        //       subscriber_id: 1973525,
        //       messages:
        //        { '2025778':
        //           { id: 2025778,
        //             content: 'new message',
        //             date: '2016-10-18 13:50:15',
        //             type: 'inbound' } } }
        ///////////////////////////////////////////////////

        Models.TblMessages.getPreviousMessages(data.conversation_id, Config.max_message_per_conversation, function(rows){
            loadedMessages = {}

            _lodash.forEach(rows, function(record) {
                loadedMessages[record.dataValues.id] = 
                loadedMessages[record.dataValues.id] = {
                    id : record.dataValues.id, 
                    content : record.dataValues.message, 
                    type : record.dataValues.direction, 
                    status : record.dataValues.status, 
                    date: record.dataValues.bound_time,
                    attachments:  record.dataValues.attachments,
                    additional_info: record.dataValues.additional_info,
                    user: record.dataValues.tbl_users != null ? record.dataValues.tbl_users.dataValues.username : null}
            })

            Conversations.list[data.conversation_id].messages = loadedMessages
            Conversations.list[data.conversation_id].last_message_id = data.message_id
            Conversations.list[data.conversation_id].latest_message_ids.push(data.message_id)
            Conversations.list[data.conversation_id].caller = 'inbound-receiver'
            Conversations.list[data.conversation_id].last_message_type = 'inbound'
            data.isnew = true
            data.last_message_id = data.message_id
            data.wait_start = Conversations.list[data.conversation_id].wait_start

            if(Services[data.service_id] != undefined && Object.keys(Services[data.service_id].users).length > 0) //THERE IS AN ACTIVE OPERATOR ASSIGNED TO THE SERVICE
            {
                if(Conversations.list[data.conversation_id].status === 'assigned')
                {
                    console.log('Conversation ['+ data.conversation_id +'] is already mapped to operator ['+ Conversations.list[data.conversation_id].user_id +']. Sending message via socket')
                    if(Users[Conversations.list[data.conversation_id].user_id] != undefined)
                    {
                        data.now = now
                        
                        if(Conversations.list[data.conversation_id].last_message_type == 'outbound'){
                            Conversations.list[data.conversation_id].assigned_latest = now
                        }

                        if(data.attachments == undefined)
                            data.attachments = []
                        if(data.additional_info == undefined)
                            data.additional_info = null

                        Conversations.list[data.conversation_id].messages[data.message_id] = {
                            id : data.message_id,
                            content: data.message,
                            date: Conversations.list[data.conversation_id].messages[data.message_id].date,
                            type : 'inbound',
                            user_id : Conversations.list[data.conversation_id].user_id,
                            attachments : data.attachments,
                            additional_info : data.additional_info
                        }
                        
                        data.user_id = Conversations.list[data.conversation_id].user_id
                        message_ids = Object.keys(Conversations.list[data.conversation_id].messages)
                        if(message_ids.length > Config.max_message_per_conversation)
                            delete Conversations.list[data.conversation_id].messages[message_ids[0]]

                        Conversations.syncToDatabase(data)
                        
                        io.of('/chat').to(Users[Conversations.list[data.conversation_id].user_id].socket_id).emit('new_message', data)
                        Conversations.logs[data.conversation_id].push({"time":gmdate('Y-m-d H:i:s'), "message":'New inbound has been sent via socket.'})
                        Status.update_conversations('all', Conversations.list[data.conversation_id])
                    }
                    else
                        console.log('SOMETHINGS WRONG ON INBOUND. USER DATA NOT FOUND ON "USERS" OBJECT. user_id ['+ Conversations.list[data.conversation_id].user_id +']')
                    
                }
                else //CONV NOT YET ASSIGNED. ASSIGN IT TO LONGEST WAITING USER W/ THE LESS QUEUE COUNT OR QUEUE CONVERSATION IF THERE ARE STILL PENDING IN SERVICE'S QUEUE
                {
                    console.log('CONVERSATION ['+ data.conversation_id +'] IS NOT YET ASSIGNED. TRY TO ASSIGN IT TO LONGEST WAITING USER W/ THE LESS QUEUE COUNT')
                    data.messages = loadedMessages
                    data.new_date = gmdate('Y-m-d H:i:s')

                    Models.TblConversationDuration.updateLatestEntry(data, function(cdid){
                        Conversations.list[data.conversation_id].status = 'pending'
                        Conversations.list[data.conversation_id].last_message_type = 'inbound'
                        Conversations.list[data.conversation_id].cdid = cdid
                        data.isnew = false

                        cid = [data.conversation_id]
                        Models.TblConversationNotes.getConversationsNotes(cid, function(notes){
                            if(_lodash.isEmpty(Conversations.list[data.conversation_id].conversation_notes)){
                                Conversations.list[data.conversation_id].conversation_notes = {}
                                _lodash(notes).forEach(function(note){
                                    Conversations.list[data.conversation_id].conversation_notes[note.note_id] = { id: note.note_id, comment: note.comment, date : note.date_created, type: note.type }
                                })
                                data.conversation_notes = Conversations.list[data.conversation_id].conversation_notes
                            }

                            if(Services[data.service_id].pending <= 0){
                                Conversations.logs[data.conversation_id].push({"time":gmdate('Y-m-d H:i:s'), "message":'Trying to assign new message to longest waiting user.'})
                                Conversations.tryToAssignConversation(data)
                            }
                            else{
                                Conversations.logs[data.conversation_id].push({"time":gmdate('Y-m-d H:i:s'), "message":'There are still pending conversations for the services to be fethced, comversation is being queued'})
                                Conversations.queueConversation(data)
                            }
                        })

                    })
                }
            }
            else // THERE IS NO ACTIVE OPERATOR FOR THE SERVICE, SET STATUS CONV TO QUEUED
            {
                console.log('THERE IS NO ACTIVE OPERATOR FOR THE SERVICE ['+ data.service_id +'], CONVERSATION ['+ data.conversation_id +'] QUEUED')
                if(Services[data.service_id] !== undefined){
                    console.log('THERE IS NO ACTIVE OPERATOR FOR THE SERVICE ['+ data.service_id +'], CREATING CONVO DURATION RECORD INITIALLY')
                    data.new_date = gmdate('Y-m-d H:i:s')
                    data.assigned_time = gmdate('Y-m-d H:i:s')
                    
                    // Conversations.syncToDatabase(data)
                    Models.TblConversationDuration.updateLatestEntry(data, function(cdid){
                        Conversations.list[data.conversation_id].cdid = cdid
                        Conversations.logs[data.conversation_id].push({"time":gmdate('Y-m-d H:i:s'), "message":'There is no active operator for the service, conversation is being queued.'})
                        Conversations.queueConversation(data)
                    })
                }
                else{
                    console.log('Service ['+data.service_id+'] not found, deleting conversation ['+ data.conversation_id +'] mapped to it')
                    delete Conversations.list[data.conversation_id]
                }
            }

        })//end of Model.TblMessages callback
        
    }
}