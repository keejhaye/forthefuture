
module.exports = function(io){

  redis = require('redis')
  moment = require('moment')
  Models = require('../sequelize.js')
  Config = require('../config.js')

	redis_client_statistics = redis.createClient()
	Statistics = {
    users : {},
    clientIntervals : {},
    update : function(type, action, service_id){
    	console.log('type ->' + type + ' action -> ' + action + ' service_id ->' + service_id)

      if(action == 'increment')
          redis_client_statistics.incr(type)
      else
          redis_client_statistics.decr(type)


      if(service_id != undefined){
        if(action == 'increment')
            redis_client_statistics.incr(type+':'+service_id)
        else
            redis_client_statistics.decr(type+':'+service_id)
      }
  	},
	  getUserStatistics : function(client_socket, data){
	  	if(data.role_id != 6){
	        redis_client_statistics.multi()
	                    .get("online_count")
	                    .get("assigned_conversations")
	                    .get("unassigned_conversations")
	                    .get("total_inbound")
                      .get("total_outbound")
                      .get("user_stat:"+data.id)
                      .execAsync()
                      .then(function(res) {
                          client_socket.emit('initial_statistics', res)
                      })
      }
      else{
        var total = {
          "online_count" : 0,
          "assigned_conversations" : 0,
          "unassigned_conversations" : 0,
          "inbound" : 0,
          "outbound" : 0
        }

        var keys = {
          online : [],
          inbound : [],
          outbound : [],
          assigned : [],
          unassigned : [],
        }

        for(i in data.services){
          keys.assigned.push('assigned_conversations:'+data.services[i])
          keys.unassigned.push('unassigned_conversations:'+data.services[i])
          keys.inbound.push('total_inbound:'+data.services[i])
          keys.outbound.push('total_outbound:'+data.services[i])
        }

        Statistics.clientIntervals[data.id] = setInterval(function(){
          Statistics.getCustomerStatistics(keys, data)
        }, Config.stats_update_interval)
	  	}
	  },

    getCustomerStatistics : function(keys, data){
      var total = {
          "online_count" : 0,
          "assigned_conversations" : 0,
          "unassigned_conversations" : 0,
          "inbound" : 0,
          "outbound" : 0
        }

      services = data.services.toString()
      Models.TblLoggedInUsers.totalOnline(services, function(rows){
        Statistics.updateCustomerHeaderStatistics({type: 'online_count', total:rows[0].online})
      })

      redis_client_statistics.mget(keys.assigned, function(err, reply){
        for(i in reply){
          total.assigned_conversations += parseInt(reply[i])
        }
        Statistics.updateCustomerHeaderStatistics({type: 'assigned_conversations', total: total.assigned_conversations})
      })

      redis_client_statistics.mget(keys.unassigned, function(err, reply){
        for(i in reply){
          total.unassigned_conversations += parseInt(reply[i])
        }
        Statistics.updateCustomerHeaderStatistics({type: 'unassigned_conversations', total: total.unassigned_conversations})
      })

      redis_client_statistics.mget(keys.inbound, function(err, reply){
        for(i in reply){
          total.inbound += reply[i] == null ? 0 : parseInt(reply[i])
        }
        Statistics.updateCustomerHeaderStatistics({type: 'total_inbound', total: total.inbound})
      })

      redis_client_statistics.mget(keys.outbound, function(err, reply){
        for(i in reply){
          total.outbound += reply[i] == null ? 0 : parseInt(reply[i])
        }
        Statistics.updateCustomerHeaderStatistics({type: 'total_outbound', total: total.outbound})
      })

      redis_client_statistics.get("user_stat:"+data.id, function(err, reply){
        Statistics.updateCustomerHeaderStatistics({type: 'user_stat', total: JSON.parse(reply)})
      })
    },

    updateCustomerHeaderStatistics: function(data){
      io.of('/statistics_client').emit('update_client_statistics', data)
      io.of('/chat').emit('update_client_statistics', data)
      io.of('/reports').emit('update_client_statistics', data)
      io.of('/history').emit('update_client_statistics', data)
    },

    /**
     * startStatsUpdate function will now update all users cpnnected to socket
     */
    startStatsUpdate : function(){
      Models.TblConversations.getStatsUpdate(function(rows){
        var total = {
          "online_count" : 0,
          "assigned_conversations" : 0,
          "unassigned_conversations" : 0
        }

        Models.TblLoggedInUsers.totalOnline(undefined, function(rows){
          redis_client_statistics.set("online_count", rows[0].online)
        })

        _lodash(rows).forEach(function(service){
          total.assigned_conversations += service.assigned
          total.unassigned_conversations += service.pending

          redis_client_statistics.set("online_count:"+service.id, service.logged_in)
          redis_client_statistics.set("assigned_conversations:"+service.id, service.assigned)
          redis_client_statistics.set("unassigned_conversations:"+service.id, service.pending)
        })

        redis_client_statistics.set("assigned_conversations", total.assigned_conversations)
        redis_client_statistics.set("unassigned_conversations", total.unassigned_conversations)

        redis_client_statistics.multi()
          .get("online_count")
          .get("assigned_conversations" )
          .get("unassigned_conversations")
          .get("total_inbound")
          .get("total_outbound")
          .execAsync()
          .then(function(res) {
              io.of('/statistics').emit('update_statistics', res)
              io.of('/chat').emit('update_statistics', res)
              io.of('/reports').emit('update_statistics', res)
              io.of('/history').emit('update_statistics', res)
          })
      })
    },

	} // end of Statistics

	return Statistics
}
