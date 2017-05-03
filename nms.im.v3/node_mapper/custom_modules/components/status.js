module.exports = function(io){
	Status = {
		update_users : function(property, data){
			io.of('/chat_status').emit('update_users',{property : property, data : data})
		},
		update_conversations : function(property, data){
			io.of('/chat_status').emit('update_conversations',{property : property, data : data})
		},
		update_services : function(property, data){
			io.of('/chat_status').emit('update_services',{property : property, data : data})
		},
		update_timeouts : function(property, data){
			io.of('/chat_status').emit('update_services',{property : property, data : data})
		},
		delete : function(object, data){
			io.of('/chat_status').emit('delete_object',{object : object, data : data})
		},
		broadcast_update(){
            io.of('/chat_status').emit('receive-services', Services)
            io.of('/chat_status').emit('receive-conversations', Conversations.list)
            io.of('/chat_status').emit('receive-users', Users)
            io.of('/chat_status').emit('receive-settings', Config)
		}
	}
	module.exports = Status
}