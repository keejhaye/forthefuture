module.exports = function(io,Statistics){

	//INCLUDE DEPENDENCIES
	Models = require('../sequelize.js')
	Config = require('../components/config.js')
	Status = require('../components/status.js')
	Users = require('./users.js')
	Services = require('./services.js')
	moment = require('moment')
	phpdate = require('phpdate-js')
  	gmdate = require('phpdate-js').gmdate
	async = require('async')

	Conversations = {
	    timeouts : {},
	    list : {},
	    logs : {},
	    createIfNotExists : function(data, now){
	    	//IF THE CONV DOES NOT EXIST ON OBJ, ADD IT
	    	console.log("CREATE IF NOT EXIST: [" + data.conversation_id + "]")
	        if(Conversations.list[data.conversation_id] === undefined){
	        	if(now == undefined)
	        		now = gmdate('Y-m-d H:i:s')
           
	        	Conversations.logs[data.conversation_id] = [{"time" : now, "message":'Conversation object is created.'}]
	            Conversations.logs[data.conversation_id].last_message_type = 'inbound'

	            Conversations.list[data.conversation_id] = {
	              id: data.conversation_id,
	              conversation_id: data.conversation_id,
	              status: 'new',
	              wait_start : now,
	              date : now, 
	              service_id : data.service_id,
	              persona_id : data.persona_id,
	              subscriber_id : data.subscriber_id,
	              last_message_id : data.message_id,
	              first_message_bound_time : data.first_message_bound_time,
	              latest_message_ids : [],
	              conversation_notes : {},
	              messages : {}}

	            Conversations.list[data.conversation_id].messages[data.message_id] = {id : data.message_id, content: data.message, date: now, type: 'inbound'}
	            console.log("NEW CONVERSATION OBJECT CREATED [" + data.conversation_id + "]")
	        }
	    },
	    updateConversationData : function(data, user_id, now){
	        Conversations.logs[data.conversation_id].push({"time" : now, "message":'Conversation is assigned to :' + user_id})
	        Conversations.list[data.conversation_id].status = 'assigned'
	        Conversations.list[data.conversation_id]['user_id'] = user_id
	        Conversations.list[data.conversation_id]['assigned_latest'] = now
        	Conversations.list[data.conversation_id].assigned_start = now

	        if(data.message_id != undefined && Conversations.list[data.conversation_id].messages[data.message_id] == undefined)
	        	Conversations.list[data.conversation_id].messages[data.message_id] = {id : data.message_id, user_id : user_id, content: data.message, date: now, type: 'inbound'}
            
            message_ids = Object.keys(Conversations.list[data.conversation_id].messages)
            if(message_ids.length > 10)
              delete Conversations.list[data.conversation_id].messages[message_ids[0]]

	        data.now = now
	        Conversations.createTimeout('unmap', data)
	        Status.update_conversations('all',Conversations.list[data.conversation_id])
	    },
	    removeTimeout: function(cid){
	    	if(this.timeouts[cid] != undefined){
	            clearTimeout(this.timeouts[cid])
	            delete this.timeouts[cid]
	            console.log("removed timeout for conversation [" + cid + "]")
        	}
 	    },
	    createTimeout : function(action, data){
	        Conversations.removeTimeout(data.conversation_id)
	        
	        if(action === 'unmap'){
				console.log('Creating unmap timeout for conversation [' + data.conversation_id + ']')
        		if(Conversations.list[data.conversation_id] === undefined)
        			Conversations.list[data.conversation_id] = data

        		data.unmap_start = moment()	          
        		Conversations.list[data.conversation_id]['unmap_start'] = data.unmap_start	            
        		this.timeouts[data.conversation_id] = setTimeout(this.unmapActiveConversation.bind(null, data), Config.wait_time_to_unmap_from_operator)
	        }
	        else if(action === 'delete'){
	            this.timeouts[data.conversation_id] = setTimeout(this.deleteIdleConversation.bind(null, data), Config.wait_time_to_handle_subscriber_conversation)
				console.log('Creating delete timeout for conversation [' + data.conversation_id + ']')
	        }
	    },
	    unmapActiveConversation : function(data){
	        console.log('unmap active conversation! Conversation ['+data.conversation_id+'] from User [' + data.user_id + ']')
	        Conversations.logs[data.conversation_id].push({"time": gmdate('Y-m-d H:i:s') , "message":'Conversation unmapped from user:' + data.user_id})

	        if(Users[data.user_id] != undefined){
	            io.of('/chat').to(Users[data.user_id].socket_id).emit('unmap_conversation', {conversation_id : data.conversation_id, from : 'unmap'})
	            Services.updateQueue('decrement', data.user_id)
	            conv_index = Users[data.user_id].conversation_ids.indexOf(data.conversation_id)
	            
	            if(conv_index != -1){
	            	Users[data.user_id].conversation_ids.splice(conv_index, 1)
	  		        Status.update_users('conversation_ids', {id : data.user_id, conversation_ids : Users[data.user_id].conversation_ids})
	            }
	        }

			data.isnew = false
			data.unmap_end = moment().format('YYYY-MM-DD HH:mm:ss')
          	Conversations.list[data.conversation_id].user_id = null
			Conversations.list[data.conversation_id].unmap_end = data.unmap_end
          	Services[data.service_id].queue[data.conversation_id].status = 'pending'

          	if(Users[data.user_id] == undefined){
            	var service_queue = {}
            	service_queue[data.service_id] = Services[data.service_id]
            	Status.update_services('queue', service_queue)
          	}

	        if(data.conversation_id != undefined || data.user_id != undefined ){
	            console.log("Trying to assign unmapped Conversation ["+ data.conversation_id + "]")
	            Conversations.list[data.conversation_id].caller = 'unmapActiveConversation'
	            Conversations.tryToAssignConversation(data, true)
	        }
	        else{
            	console.log('somethings wrong with the data on unmap.')
	        }

	        // console.log(Conversations.list[data.conversation_id])
	    },
	    deleteIdleConversation : function(data){
	        console.log('delete idle conversation! [' + data.conversation_id + ']')
	        delete_time = gmdate('Y-m-d H:i:s')
	        time_ended = gmdate('Y-m-d H:i:s')
	        Models.TblConversations.syncData(data.conversation_id, data.user_id, 'handled')
	        Models.TblConversationDuration.end_conversation(data, time_ended)

	        if(Users[data.user_id] != undefined){
	            Conversations.tryToFetchNewConversation(data.user_id)
	            conv_index = Users[data.user_id].conversation_ids.indexOf(data.conversation_id)
	            if(conv_index > -1){
	            	Users[data.user_id].conversation_ids.splice(conv_index, 1)
	            	Status.update_users('conversation_ids', {id : data.user_id, conversation_ids : Users[data.user_id].conversation_ids})
	            }
	        }
	        else{
	            console.log('deleteIdleConversation(): User ['+ data.user_id +'] is already disconnected.') 
	        }
	        
	        Conversations.logs[data.conversation_id].push({"time":delete_time,"message":'There is no reply from subscriber. Conversation will now be handled.'})
	        Models.TblConversations.saveLogs(Conversations.logs[data.conversation_id], data.conversation_id, time_ended, Conversations.list[data.conversation_id].cdid,Models, function(){
		        if(Services[data.service_id].queue[data.conversation_id]){
		        	delete Services[data.service_id].queue[data.conversation_id]
		        }
		            
		      	clearTimeout(Conversations.timeouts[data.conversation_id]);
		        delete Conversations.list[data.conversation_id]
		        delete Conversations.logs[data.conversation_id]
		        delete Conversations.timeouts[data.conversation_id]
		        Status.delete('conversations',data.conversation_id)
		        Status.delete('service_queue',{sid: data.service_id, cid: data.conversation_id})   
	        })
	    },
	    tryToAssignConversation : function(data, unmapped){
	        //FILTER AVAILABLE OPERATORS & SORT BY WAITING TIME
	        Conversations.logs[data.conversation_id].push({"time": gmdate('Y-m-d H:i:s') ,"message":'Try to assign conversation.'})
	        data.service_id = Conversations.list[data.conversation_id].service_id
	        filtered_users = _lodash.filter(Services[data.service_id].users, {'status' : 'available'})
	        sorted_users = _lodash.orderBy(filtered_users, ['queued','wait_start'],['asc','asc']) 
	        longest_waiting_user = sorted_users[0]

	        //NO USER AVAILABLE. QUEUE THE MESSAGE
	        if(longest_waiting_user === undefined || Users[longest_waiting_user.id] === undefined || Services[data.service_id].users[longest_waiting_user.id] == undefined || Services[data.service_id].users[longest_waiting_user.id].status == 'unavailable'){
	            console.log('WELL, THERE IS NO USER AVAILABLE. QUEUE CONVERSATION ['+ data.conversation_id + ']')
	            date = gmdate('Y-m-d H:i:s')
              	ids = Conversations.list[data.conversation_id].latest_message_ids.length > 0 ? Conversations.list[data.conversation_id].latest_message_ids : Conversations.list[data.conversation_id].last_message_id

              	Models.TblConversations.syncData(data.conversation_id, null, 'pending')
	            Models.TblMessages.updateMessage(ids, null, 'normal', date)
	            Conversations.removeTimeout(data.conversation_id)
	            Conversations.queueConversation(data, unmapped)
	        }
	        else{
	            console.log('THERE IS AN AVAILABLE OPERATOR. MAP CONVERSATION [' + data.conversation_id + '] to USER [' + longest_waiting_user.id +']')
	            //IF 'longest_waiting_user' IS NOT NULL, THERE IS AN AVAILABLE OPERATOR. MAP IT TO HIM/HER
	            data.user_id = longest_waiting_user.id
	            data.unmapped = unmapped
	            Services[data.service_id].pending++
	            Conversations.syncToDatabase(data)
	            Conversations.mapToOperator(data, longest_waiting_user.id, unmapped)
	        }
	    },
	    mapToOperator : function(data, user_id, unmapped){
	    	if(Users[user_id] != undefined){
		        mapped_time = gmdate('Y-m-d H:i:s')
	          	Services[data.service_id].queue[data.conversation_id] = {id: data.conversation_id, status: 'assigned', date : mapped_time, service_id : data.service_id, persona_id : data.persona_id,subscriber_id : data.subscriber_id}
		        
	          	if(Services[data.service_id].pending > 0)
		        	Services[data.service_id].pending--
		        
		        if(Users[user_id].conversation_ids == undefined)
		        	Users[user_id].conversation_ids = []

		        Services.updateQueue('increment', user_id)
		        Users[user_id].conversation_ids.push(data.conversation_id)
		        Status.update_users('conversation_ids', {id : user_id, conversation_ids : Users[user_id].conversation_ids})
		        Models.TblConversations.syncData(data.conversation_id, user_id, 'assigned')
		        
	          	if(data.id.length > 0)
		        	Models.TblMessages.assignMessageToUser(data.id, user_id, mapped_time)
		        
		        conversation_metadata = {}
	            Models.TblConversations.getMetadata(data.conversation_id, function(response){
	                conversation_metadata = response[0]
	                data.status = 'pending'
	                data.user_id = user_id

	                new_data = Object.assign(data, conversation_metadata)
	                new_data.first_message_bound_time = Conversations.list[data.conversation_id].first_message_bound_time
	                new_data.date = mapped_time
		        	new_data.time_limit = Config.wait_time_to_unmap_from_operator
		        	new_data.cntdown = null

		        	if(Conversations.list[data.conversation_id].conversation_notes != undefined)
		        		new_data.conversation_notes = Conversations.list[data.conversation_id].conversation_notes

	                if(unmapped)
	                    new_data.messages = Conversations.list[data.conversation_id].messages

	              	Conversations.list[data.conversation_id] = _lodash.assign(Conversations.list[data.conversation_id], conversation_metadata)
	    	        Conversations.updateConversationData(data, user_id, mapped_time)

	              	if(Users[user_id] != undefined){
	    	        	console.log('New Conversation ['+ data.conversation_id +'] is mapped to ['+ user_id +']')
	                	io.of('/chat').to(Users[user_id].socket_id).emit('new_conversation', new_data)
	              	}
	            })
	    	}
	    },
	    tryToFetchNewConversation : function(user_id){
	    	console.log("Fetching new conversation for ["+ user_id +"]")
	        conversations_queue = {}
	        _lodash(Users[user_id].services).forEach(function(service){   
	            if(Services[service] !== undefined)
	                conversations_queue = _lodash.merge(conversations_queue, Services[service].queue)
	        })                    

	        conversation = _lodash.filter(conversations_queue, {'status' : 'pending'}) //SORT PENDING ONLY
	        conversation = _lodash.sortBy(conversation, ['date']).slice(0, 1)  //SORT BY DATE & GET 'ONE' LONGEST TIME WAITING 

	        if(!_lodash.isEmpty(conversation)){
	            console.log('a conversation to be mapped is found! ID: [' + conversation[0].id + ']')

	            now = gmdate('Y-m-d H:i:s')
	            now_moment = moment().format('YYYY-MM-DD HH:mm:ss')
	            conversation = conversation[0]
	            conversation.caller = 'tryToFetchNewConversation'

	            if(Users[user_id] != undefined){
	                Users[user_id].conversation_ids.push(conversation.conversation_id)

                if(Services[conversation.service_id].queue[conversation.conversation_id] == undefined){
                	Services[conversation.service_id].queue[conversation.conversation_id] = {
                		id : conversation.conversation_id,
                		conversation_id : conversation.conversation_id,
                		service_id : conversation.service_id,
                		persona_id : conversation.persona_id,
                		date : now,
                		status : 'assigned',
                	}
                }
                else{
                	Services[conversation.service_id].queue[conversation.conversation_id].status = 'assigned'
                	Services[conversation.service_id].queue[conversation.conversation_id].date = now
                }
                
                if(Services[conversation.service_id].pending > 0)
                	Services[conversation.service_id].pending--

                if(Conversations.list[conversation.conversation_id] != undefined){
                	Conversations.list[conversation.conversation_id].status = 'assigned'
                	Conversations.list[conversation.conversation_id].user_id = user_id
                	Conversations.list[conversation.conversation_id].assigned_start = Conversations.list[conversation.conversation_id].assigned_latest = conversation.date = now
                	Conversations.logs[conversation.conversation_id].push({"time":now, "message":'Conversation is assigned to user:' + user_id})
                	conversation.last_message_id = Conversations.list[conversation.conversation_id].last_message_id = Conversations.list[conversation.conversation_id].messages[_lodash.findLastKey(Conversations.list[conversation.conversation_id].messages)].id
                	conversation.additional_info = Conversations.list[conversation.conversation_id].messages[_lodash.findLastKey(Conversations.list[conversation.conversation_id].messages)].additional_info
                	conversation.user_id = user_id
                	conversation.time_limit = Config.wait_time_to_unmap_from_operator
                	conversation = _lodash.merge(conversation, Conversations.list[conversation.conversation_id])
                	
                	Models.TblConversations.syncData(conversation.conversation_id, user_id, 'assigned')
                	io.of('/chat').to(Users[user_id].socket_id).emit('new_conversation', conversation)
                	console.log('NEW CONVERSATION ['+ conversation.conversation_id +'] SENT TO USER ['+ user_id +'] SOCKET');

                	Status.update_users('conversation_ids', {id : user_id, conversation_ids : Users[user_id].conversation_ids })
  	                Services.updateQueue('increment', user_id)
  	                Conversations.syncToDatabase(conversation)
  	                
  	                Conversations.createTimeout('unmap', conversation)
  	                Models.TblConversations.getMetadataByConversationIds(conversation.conversation_id, function(rows){
  	                	if(rows !== undefined){
  	                		conversation_metadata = {}
  	                		_lodash(rows).forEach(function(data){
  	                			if(conversation_metadata[data.conversation_id] === undefined){
  	                				conversation_metadata[data.conversation_id] = data
  	                				conversation_metadata[data.conversation_id]['conversation_notes'] = {}
  	                				conversation_metadata[data.conversation_id]['conversation_notes'][data.subscriber_note_id] = [{ id: data.subscriber_note_id, comment: data.comment, date : data.date_created, type: data.type }]
  	                			}
  	                			else{
  	                				conversation_metadata[data.conversation_id].conversation_notes[data.subscriber_note_id] = { id: data.subscriber_note_id, comment: data.comment, date : data.date_created, type: data.type }
  	                			}

  	                			Conversations.list[data.conversation_id] = _lodash.merge(Conversations.list[data.conversation_id], conversation_metadata)
  	                			Status.update_conversations('all', Conversations.list[data.conversation_id])
  	                		})

  	                		io.of('/chat').to(Users[user_id].socket_id).emit('initial_conversations_metadata', conversation_metadata)
  	                	}
  	                })
  	            }
  	            else{
  	            	console.log('!!! FOR SOME ODD REASON, CONVERSATION ID [' + conversation.conversation_id + '] not found on conversations object! MAPPING CANCELLED!')
  	            }
  	        }
  	        else
  	        	console.log('FOR SOME ODD REASON, USER [' + user_id + '] not found on users object! MAPPING CANCELED!')
	        }
	    },
	    /**
	     * Will update Conversation Duration Data and
	     * make data.last_message_id status = assigned
	     * @param  {[type]} data [description]
	     */
	     syncToDatabase : function(data){
	        // data.new_date = moment().format('YYYY-MM-DD HH:mm:ss')
	        // CHANGED data.new_date BECAUSE IT SHOULB BE ON GMT0 WHEN SAVING IT TO DATABASE
	        data.new_date = gmdate('Y-m-d H:i:s')
	        data.new_date_moment = moment().format('YYYY-MM-DD HH:mm:ss')
	        data.assigned_time = gmdate('Y-m-d H:i:s')

	        if(data.last_message_id == undefined)
	        	data.last_message_id = Conversations.list[data.conversation_id].messages[_lodash.findLastKey(Conversations.list[data.conversation_id].messages)].id

	        Models.TblConversationDuration.updateLatestEntry(data, function(cdid){
	        	if(Conversations.list[data.conversation_id].cdid != undefined){
	        		Conversations.list[data.conversation_id].cdid = cdid
	        	}
	        	else{
	        		console.log('Missing Conversation Object ['+data.conversation_id+']')
	        	}
	        })

	        if(Conversations.list[data.conversation_id].caller === 'inbound-receiver'){
	        	Models.TblMessages.assignMessageToUser(Conversations.list[data.conversation_id].latest_message_ids, data.user_id, data.new_date, data.new_date_moment)
	        }
	        else{
	        	if(Conversations.list[data.conversation_id].latest_message_ids.length === 0){
	        		ids = Conversations.list[data.conversation_id].last_message_id
	        	}else{
	        		ids = Conversations.list[data.conversation_id].latest_message_ids
	        	}

	        	Models.TblMessages.updateMessage(ids, data.user_id, 'assigned', data.new_date)
	        }
	    },
	    queueConversation : function(data, unmapped){
	    	new_date = gmdate('Y-m-d H:i:s')
        	// if(data.message_id != undefined)
	     	//    	Conversations.list[data.conversation_id].messages[data.message_id] = { id: data.message_id, content: data.message, date: new_date, type : 'inbound'}

	     	if(Services[data.service_id].queue[data.conversation_id] == undefined){
	     		Services[data.service_id].queue[data.conversation_id] = {id : data.conversation_id, conversation_id : data.conversation_id, service_id : data.service_id, persona_id :data.persona_id, status : 'pending', date : data.date}
	     	}

     		Services[data.service_id].pending++
     		service_obj = {}
     		service_obj[data.service_id] = Services[data.service_id]
     		Status.update_services('queue', service_obj)

	     	Conversations.logs[data.conversation_id].push(new_date + ' -> Conversation is queued.')
	     	message_ids = Object.keys(Conversations.list[data.conversation_id].messages)

	     	latest_message_ids = Conversations.list[data.conversation_id].latest_message_ids.length > 0 ? Conversations.list[data.conversation_id].latest_message_ids : Conversations.list[data.conversation_id].last_message_id

	     	Models.TblMessages.update({
	     		status : 'normal'
	     	}, {
	     		where : { id : latest_message_ids }
	     	})

	     	Models.TblConversations.update({
	     		status : 'pending',
	     		user_id : null
	     	}, {
	     		where : { id : data.conversation_id }
	     	})

	     	if(message_ids.length > 10)
	     		delete Conversations.list[data.conversation_id].messages[message_ids[0]]

	     	Conversations.list[data.conversation_id].status = 'queued'
	     	Status.update_conversations('all', Conversations.list[data.conversation_id])
	     	console.log('Conversation ['+ data.conversation_id +'] has been queued')
	 	},
	    processConversationMetaData : function(conv_ids, client_socket, callback){
	        if(conv_ids.length > 0){
	            Models.TblConversations.getMetadataByConversationIds(conv_ids, function(rows){
	                if(rows !== undefined){
	                    conversation_metadata = {}
	                    _lodash(rows).forEach(function(data){
                            conversation_metadata[data.conversation_id] = data
                        	conversation_metadata[data.conversation_id]['conversation_notes'] = {}
                        })

                    	Models.TblConversationNotes.getConversationsNotes(conv_ids, function(notes){
                    		_lodash(notes).forEach(function(note){
                    			if(conversation_metadata[note.conversation_id] != undefined){
                          			conversation_metadata[note.conversation_id].conversation_notes[note.note_id] = { id: note.note_id, comment: note.comment, date : note.date_created, type: note.type }
                    			}
                    			else{
                    				console.log('DEBUG: conversation_notes of undefined. CIDS ['+conv_ids+']')
                    			}
                    		})

	                    	_lodash(conversation_metadata).forEach(function(data){
	                    		Conversations.list[String(data.conversation_id)] = _lodash.merge(data, Conversations.list[String(data.conversation_id)])
	                    	})

	                    	if(client_socket != false)
		                    	client_socket.emit('initial_conversations_metadata', conversation_metadata)
                    	})
	                }
	                if(callback != undefined) callback(true)
	            })
	        }
	    },
	    updateNoteType : function(note){
	    	Models.TblConversationNotes.updateNoteType(note)
	    	if(Conversations.list[note.cid] != undefined && Conversations.list[note.cid].conversation_notes[note.id] != undefined){
	    		Conversations.list[note.cid].conversation_notes[note.id].type = note.new_type
	    	}
	    },
	    discardMessage : function(details){
	    	Models.TblMessages.updateMessage(details.message_id, details.user_id, 'discard', details.date, details.fullname)
	    } 
	}
	module.exports = Conversations
}
