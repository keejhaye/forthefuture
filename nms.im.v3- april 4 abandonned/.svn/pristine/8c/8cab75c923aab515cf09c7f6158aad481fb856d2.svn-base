module.exports = function(io,redisClient) {
    //INCLUDE DEPENDENCIES
    Conversations = require('../custom_modules/components/conversations.js')
    sequelize = require('sequelize')
    moment = require('moment')
    _lodash = require('lodash')
    request = require('request')

    Mock = {
        clientSocket : null,
        services : {},
        conversations : {},
        maxReplyLimit : 5,
        maxBatchConversation : 500,
        inbound_url : 'http://localhost/nms.im.v3/public/api/inbound',
        // inbound_url : 'http://im.nmsloop.com/api/inbound',
        init : function(){
            Mock.getServices()
            Mock.conversations.list = {}
            Mock.conversations.batch = {}
            Mock.conversations.batch_convo_limit = Mock.maxBatchConversation
            
            //initially create batch at index 0
            Mock.conversations.batch[0] = {id: 0, reply_limit: Mock.maxReplyLimit, convo_count: 0}

            if(Mock.clientSocket != null){
                console.log('emiting conversations...')
                Mock.clientSocket.emit('conversations', Mock.conversations)
            }
            
            console.log(Mock.conversations)
        },
        
        getServices : function(){
            Models.TblServices.findAll({
                attributes: ['id', 'code', 'name', 'aggregator_username', 'aggregator_password', 'aggregator_url'],
                where : {
                    status : 'active',
                    // name : {$like: '%test%'},
                    aggregator_username : ['anubis', 'D@tePrO', 'wellhello']},
            })
            .then(function(rows){
                for(i in rows){
                    service = rows[i].dataValues
                    service.counter = 0
                    Mock.services[service.id] = service
                }

                // Mock.getServiceConversations(callback)
                // callback(Mock)
            })
        },

        getServiceConversations : function(callback){
            var service_ids = Object.keys(Mock.services)

            Models.TblConversations.sequelize.query(
                "SELECT c.id as `conversation_id`, c.service_id, p.name as `persona`, su.name as `susbcriber` FROM tbl_conversations c " +
                "LEFT JOIN tbl_personas p ON p.id = c.persona_id " +
                "LEFT JOIN tbl_subscribers su ON su.id = c.subscriber_id " +
                "WHERE c.service_id IN(?)",
                { replacements: [service_ids], type: sequelize.QueryTypes.SELECT }
            )
            .then(function(rows){
                for(i in rows){
                    Mock.conversations[rows[i].conversation_id] = rows[i]
                }

                callback(Mock)
            })
        },

        runAggregatorResponseRoutine : function(){
            
        },

        sendReply : function(message){
            if(Mock.services[message.service_id] != undefined){

                if(Mock.conversations.list[message.conversation_id] == undefined){
                    Mock.createConversationObj(message)
                }

                conversation_batch = Mock.conversations.list[message.conversation_id].batch_id
                Mock.conversations.list[message.conversation_id].counter += 1

                if(Mock.conversations.batch[conversation_batch].reply_limit >= Mock.conversations.list[message.conversation_id].counter){
                    var msg = 'test message reply ' +  Mock.conversations.list[message.conversation_id].counter

                    var reply = {
                        username : Mock.services[message.service_id].aggregator_username,
                        password : Mock.services[message.service_id].aggregator_password,
                        service_code : Mock.services[message.service_id].code,
                        to : message.persona_name,
                        from : message.subscriber_name,
                        message : msg,
                    }

                    request.post({url: Mock.inbound_url, form: {data: JSON.stringify(reply)}}, function(err,httpResponse,body){
                        console.log('CONVERSATION['+message.conversation_id+']: ' + body)

                        if(Mock.conversations.batch[conversation_batch].reply_limit == Mock.conversations.list[message.conversation_id].counter)
                                Mock.conversations.list[message.conversation_id].status = 'limit reached'

                        Mock.clientSocket.emit('conversations', Mock.conversations)
                    })
                }
                else{
                    Mock.conversations.list[message.conversation_id].counter -= 1
                    Mock.conversations.list[message.conversation_id].status = 'limit reached'
                    Mock.clientSocket.emit('conversations', Mock.conversations)
                }
            }
            else{
                console.log('Service [' + message.service_id + '] is not listed in the services object list')
            }
        },

        createConversationObj: function(message){
            Mock.conversations.list[message.conversation_id] = {}
            Mock.conversations.list[message.conversation_id].counter = 0
            Mock.conversations.list[message.conversation_id].conversation = message.conversation_id
            Mock.conversations.list[message.conversation_id].service = message.service_id
            Mock.conversations.list[message.conversation_id].status = 'available'

            var last_batch_key = _lodash.findLastKey(Mock.conversations.batch)

            if(Mock.conversations.batch[last_batch_key].convo_count == Mock.conversations.batch_convo_limit){
                if(Mock.conversations.batch[last_batch_key].reply_limit > 1){
                    //create new batch 
                    var index = parseInt(last_batch_key) + 1
                    var limit = Mock.conversations.batch[last_batch_key].reply_limit - 1
                    Mock.conversations.batch[index] = {}
                    Mock.conversations.batch[index].id = index
                    Mock.conversations.batch[index].reply_limit = limit
                    Mock.conversations.batch[index].convo_count = 1
                    Mock.conversations.list[message.conversation_id].batch_id = index
                }
                else{
                    Mock.conversations.list[message.conversation_id].status = 'unavailable'
                    Mock.conversations.list[message.conversation_id].batch_id = null
                }
            }
            else{
                Mock.conversations.list[message.conversation_id].batch_id = last_batch_key
                Mock.conversations.batch[last_batch_key].convo_count += 1
            }

            console.log(Mock.conversations)

        },
    }

    module.exports = Mock
}