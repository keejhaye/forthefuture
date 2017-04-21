module.exports = function(Statistics){
	//INCLUDE DEPENDENCIES
	Models = require('../sequelize.js')
	Config = require('../components/config.js')
	Users = require('../components/users.js')
	Status = require('../components/status.js')
	_lodash = require('lodash')
	moment = require('moment')
	phpdate = require('phpdate-js')
	gmdate = require('phpdate-js').gmdate
	Conversations = require('./conversations.js')

	Services = {
		addUserAndGetConversationsQueue : function(user_services, user_data, conversations_queue, client_socket){
			response_user_services = {}
      now = gmdate('Y-m-d H:i:s')
	        _lodash(user_services).forEach(function(service){
            if(Services[service] == undefined){
              console.log('Undefined user service: [' + service + '] user: [' + user_data.id + ']')
            }

	            response_user_services[service] = {
	               name : Services[service].name,
	                allow_multiple_reply : Services[service].allow_multiple_reply,
	                message_limit : Services[service].message_limit,
	                min_char : Services[service].min_char,
	                max_char : Services[service].max_char,
	                color_theme : Services[service].color_theme,
	                timezone : Services[service].timezone,
	                canned_messages : Services[service].CannedMessages
	            }
	            if(Services[service].users === undefined)
	                Services[service].users = {}

	            if(Services[service] !== undefined)
	                conversations_queue = _lodash.merge(conversations_queue, Services[service].queue)

	            Services[service].users[user_data.id] = {id : user_data.id, wait_start : now, queued:0, status : 'available', message_sent : 0}
	        })
          
          client_socket.emit('services_with_rules', response_user_services)
	        // console.log(conversations_queue)
	        return conversations_queue  
	    },
	    processConversationsQueue : function(conversations_queue,client_socket, user_data){
          now = gmdate('Y-m-d H:i:s')
          now_moment = moment()
           //SORT PENDING ONLY
          tobe_assigned_conversations_queue = _lodash.filter(conversations_queue, {'status' : 'pending'}) //SORT PENDING ONLY
          //SORT BY DATE & GET 'FOUR' LONGEST TIME WAITING 
          tobe_assigned_conversations_queue = _lodash.sortBy(tobe_assigned_conversations_queue, ['date']).slice(0, Config.max_operator_conversation)

          assigned_conversations = _lodash.filter(Conversations.list, {'user_id' : user_data.id, 'status' : 'assigned'}) //GET CURRENTLY ASSIGNED CONVERSATIONS
          conversations_queue = _lodash.merge(tobe_assigned_conversations_queue, assigned_conversations) //MERGE FILTERED CONVERSATIONS WITH THE ASSIGNED ONES.

          conv_ids = []
          mapped_conversations = {}
          Users[user_data.id].conversation_ids = []

        _lodash(conversations_queue).forEach(function(queue_item){
            Users[user_data.id].conversation_ids.push(queue_item.id)

            if(queue_item.status != 'assigned'){
              //FORMAT DATA THAT IS GOING TO FRONTEND
              mapped_conversations[queue_item.id] = queue_item
              mapped_conversations[queue_item.id]['conversation_id'] = queue_item.id
              mapped_conversations[queue_item.id]['status'] = 'pending'
              mapped_conversations[queue_item.id]['last_message_id'] = Conversations.list[queue_item.id].last_message_id
              mapped_conversations[queue_item.id]['messages'] = Conversations.list[queue_item.id].messages
              mapped_conversations[queue_item.id]['assigned_latest'] = now
              mapped_conversations[queue_item.id]['assigned_start'] = now
              mapped_conversations[queue_item.id]['unmap_start'] = now_moment
              mapped_conversations[queue_item.id]['wait_start'] = Conversations.list[queue_item.id].wait_start
              mapped_conversations[queue_item.id]['first_message_bound_time'] = moment(Conversations.list[queue_item.id].first_message_bound_time)
              
              Services[queue_item.service_id].queue[queue_item.id].status = 'assigned'
             
              if(Services[queue_item.service_id].pending > 0)
                Services[queue_item.service_id].pending--
              
              queue_item.user_id = user_data.id
              Conversations.updateConversationData(queue_item, user_data.id, now)
              Conversations.syncToDatabase(Conversations.list[queue_item.id])
              Conversations.list[queue_item.id].caller = 'processConversationsQueue'
            }
            else if(queue_item.status == 'assigned' && Conversations.list[queue_item.id].last_message_type == 'outbound'){
			  // This is for multiple reply conversations where last messag type is outbound
			  // Created this to avoid negative counter in the chat box when user refreshed page and last message type is outbound
              Conversations.createTimeout('unmap',queue_item)
              mapped_conversations[queue_item.id] = queue_item
              mapped_conversations[queue_item.id]['status'] = 'pending'
              mapped_conversations[queue_item.id]['unmap_start'] = now_moment
            }
            else{
                mapped_conversations[queue_item.id] = queue_item
                mapped_conversations[queue_item.id]['status'] = 'pending'
            }
            mapped_conversations[queue_item.id]['time_limit'] = Config.wait_time_to_unmap_from_operator
            Services.updateQueue('increment', user_data.id)
            conv_ids.push(queue_item.id)
          })

          console.log('[processConversationsQueue] assigning conversations ['+ conv_ids +'] to user ['+user_data.id+']')
          Models.TblConversations.syncData(conv_ids, user_data.id, 'assigned')
          Conversations.processConversationMetaData(conv_ids,client_socket)

	        client_socket.emit('initial_conversations', mapped_conversations)
	    },
	    updateQueue : function(action, user_id){
	        console.log('updateQueue ->' + action + '->' + user_id)
	        update_time = gmdate('Y-m-d H:i:s')
	        conversation_queues = {}

	        _lodash.forEach(Users[user_id].services, function(service){
              if(action === 'decrement')
                  Services[service].users[user_id].queued -= 1
              else if(action === 'increment') {
                  Services[service].users[user_id].queued += 1
                  Services[service].users[user_id].wait_start = update_time
              }

              if(Services[service].users[user_id].queued < Config.max_operator_conversation){
                  if(Services[service].users[user_id].status == 'unavailable')
                      Services[service].users[user_id].wait_start = update_time

                  Services[service].users[user_id].status = 'available'
              }
              else if(Services[service].users[user_id].queued > (Config.max_operator_conversation - 1)){
                  Services[service].users[user_id].status = 'unavailable'
                  Services[service].users[user_id].wait_start = update_time
              }
            
	        	conversation_queues[service] = Services[service]
          })

          Status.update_services('queue',conversation_queues)
      },

		tryFetchConversationOnQueue : function(client_socket, user_services, user_data){
      console.log('tryFetchConversationOnQueue')
      conversations_queue = {}
      conversations_queue = Services.addUserAndGetConversationsQueue(user_services, user_data, conversations_queue, client_socket)                  
      user_data['socket_id'] = client_socket.id
      client_socket.user_data = Users[user_data.id] = user_data
      
      Services.getUserData(user_data, function(user){
        Users[user_data.id].username = user.username
        Services.processConversationsQueue(conversations_queue, client_socket, user_data)
        Status.update_users('all',Users[user_data.id])
      })
    },

    getUserData : function(user_data, callback){
      Models.TblUsers.findById(user_data.id)
      .then(function(user){
        if(user != null){
          callback(user.dataValues)
        }
      })
    }
	}

	module.exports = Services
}