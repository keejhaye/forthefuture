module.exports = function(io,Statistics,redisClient) {
    //INCLUDE DEPENDENCIES
    Services = require('./components/services.js')
    Conversations = require('./components/conversations.js')
    Users = require('./components/users.js')
    Config = require('./components/config.js')
    redis = require('redis')
    moment = require('moment')
    gmdate = require('phpdate-js').gmdate
    _lodash = require('lodash')

    redisClient_2 = redis.createClient()

    redis_client_outbound = redisClient
    redis_client_outbound.on("error", function (err) {
        console.log("Error redis_client_outbound" + err);
    })

    redis_client_outbound.subscribe('send-outbound')
    redis_client_outbound.on("message", function (channel, message) {
        if(channel === 'send-outbound'){

            var reply = {
                    username : "Test",
                    password : "Test",
                    service_code : "Test",
                    to : "Test",
                    from : "Test",
                    message : "Test",
                }

            // var request = require('request');

            const request = require('request');

            let options = {  
                url: 'http://rovingdragon.com/apireceiver/receivedOutbound',
                form: {
                    email: JSON.stringify(reply),
                    password: 'myPassword'
                }
            };

            request.post(options, function(error, response, body){
                console.log("hhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhh-------------")
            });


            // request.post(
            //     'http://rovingdragon.com/apireceiver/receivedOutbound',
            //     { json: { data: JSON.stringify(reply) } },
            //     function (error, response, body) {
            //         if (!error && response.statusCode == 200) {
            //             console.log("heyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyy", response)
            //         }else {
            //             console.log("Loooooooooooooooooooooooooosssssssserrrr", response)
            //         }
            //     }
            // );

            receive_outbound_process(JSON.parse(message))
        }
    })

    receive_outbound_process = function(message){
        now = gmdate('Y-m-d H:i:s')
        message['date'] = moment(message['date'])
        message['type'] = 'outbound'

        console.log('Outbound message sent for Conversation [' + message.conversation_id + '] from User ['+message.user_id+']')
        if(Conversations.list[message.conversation_id])
        {
            if(Conversations.list[message.conversation_id].sent_outbound != undefined)
                Conversations.list[message.conversation_id].sent_outbound += 1
            else
                Conversations.list[message.conversation_id].sent_outbound = 1

            //update like in Conversations.deleteIdleConversation
            update_user_and_service_queue(message)

            Conversations.logs[message.conversation_id].push({"time":now,"message":'outbound id:'+message.id+' from user_id:'+message.user_id+' has been sent.'})
            Conversations.list[message.conversation_id].time_handled = now
            Conversations.list[message.conversation_id].last_message_type = 'outbound'
            Conversations.list[message.conversation_id].messages[message.id] = message
            Conversations.list[message.conversation_id].service_name = message.service_name
            Conversations.list[message.conversation_id].fullname = message.fullname
            message_ids = Object.keys(Conversations.list[message.conversation_id].messages)

            if(message_ids.length > Config.max_message_per_conversation)
                delete Conversations.list[message.conversation_id].messages[message_ids[0]]

            //create wait time to handle conversation before object deletion
            if(!Services[message.service_id].allow_multiple_reply){
                Conversations.createTimeout('delete', message)
            }

            Statistics.update('total_outbound', 'increment',message.service_id)

            redisClient_2.getAsync('user_stat:'+message.user_id).then(function(stats){
                if(Users[message.user_id] != undefined){
                    io.of('/chat').to(Users[message.user_id].socket_id).emit('update_user_stats', stats)
                }
            })
            Status.update_conversations('all', Conversations.list[message.conversation_id])
        }
        else
            console.log('Well, there is no Conversation [' + message.conversation_id + '] for some odd reason')
    }

    update_user_and_service_queue = function(message){
        if(!Services[message.service_id].allow_multiple_reply && Conversations.list[message.conversation_id].sent_outbound >= 1){
            //update service queue
            Services[message.service_id].queue[message.conversation_id].status = 'handled'
            Services.updateQueue('decrement', message.user_id)
        }

        //remove conversation for user conversation list if User is connected to socket
        if(Users[message.user_id] != undefined){
            conv_index = Users[message.user_id].conversation_ids.indexOf(message.conversation_id)
            if(conv_index > -1){
                Users[message.user_id].conversation_ids.splice(conv_index, 1)
                Status.update_users('conversation_ids', {id : message.user_id, conversation_ids : Users[message.user_id].conversation_ids})
            }
        }

        //clear latest message ids
        Conversations.list[message.conversation_id].latest_message_ids = []
        Conversations.list[message.conversation_id].status = 'handled'

        if(Services[message.service_id].allow_multiple_reply)
            Conversations.list[message.conversation_id].status = 'assigned'
    }
}