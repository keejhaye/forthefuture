module.exports = function(io,Statistics,redisClient) {

    //INCLUDE DEPENDENCIES
    Services = require('./components/services.js')
    Conversations = require('./components/conversations.js')
    Users = require('./components/users.js')
    History = require('./components/history.js')
    Models = require('./sequelize.js')
    Config = require('./config.js')
    DevTools = require('../mock_modules/dev-tools.js')
    redis = require('redis')

    redis_client_stats = redisClient
	stats_redis = redis.createClient()
	redis_client_stats.on("error", function (err) {
	    console.log("Error redis_client_stats" + err);
	})

    allusers = io.of('/all')
    allusers.on('connection', function(client_socket){
        console.log(io.of('/all').connected)
    })

	statistics = io.of('/statistics')
    statistics.on('connection', function(client_socket){
        DevTools.isConnectionEnabled(client_socket)
        referer = client_socket.handshake.headers.referer

        if(referer == undefined)
            referer = "panel/park"

        client_socket.onPark = referer.match("panel/park") != null ? 1 : 0
        client_socket.emit('update', 'You are now connected to statistics namespace')
        console.log('new "STATISTICS" connection. id:' + client_socket.id)

        client_socket.on('disconnect', function() {
            console.log('disconnected STATISTICS id:' + client_socket.id)

            if(client_socket.userId != undefined){
                stats_redis.set('stat_socket:'+client_socket.userId, JSON.stringify({socketId:null}))
                
                if(Array.isArray(Statistics.users[client_socket.userId])){
                    var index = Statistics.users[client_socket.userId].indexOf(client_socket.id)
                    Statistics.users[client_socket.userId].splice(index,1)
                }
            }
        })

        client_socket.on('user_connect', function(data){
            stats_redis.getAsync('stat_socket:'+data.id).then(function(stat_socket){
                stat_socket = JSON.parse(stat_socket)

                if(stat_socket != null && stat_socket.onPark && client_socket.onPark == 1 && io.of('/statistics').connected[stat_socket.socketId] != undefined){
                    client_socket.emit('force_disconnection', Config.prompt.double_park)
                    client_socket.disconnect()
                }
                else{
                    client_socket.userId = data.id
                    stats_redis.set('stat_socket:'+data.id, JSON.stringify({socketId:client_socket.id, onPark: client_socket.onPark}))
                    Array.isArray(Statistics.users[client_socket.userId]) ? Statistics.users[client_socket.userId].push(client_socket.id) : Statistics.users[client_socket.userId] = [client_socket.id]
                    Statistics.getUserStatistics(client_socket, data)
                }
            })

        })

        client_socket.on('profile_message_history', function(data){
            History.profile_message_history(data, function(result){
                client_socket.emit('profile_message_history_data', result)
            })
        })
    })

    statistics_client = io.of('/statistics_client')

    statistics_client.on('connection', function(client_socket){
        DevTools.isConnectionEnabled(client_socket)
        referer = client_socket.handshake.headers.referer

        if(referer == undefined)
            referer = "panel/park"

        client_socket.onPark = referer.match("panel/park") != null ? 1 : 0
        client_socket.emit('update', 'You are now connected to statistics_client namespace')
        console.log('new "STATISTICS" connection. id:' + client_socket.id)

        client_socket.on('disconnect', function() {
            console.log('disconnected STATISTICS id:' + client_socket.id)

            if(client_socket.userId != undefined){
                clearInterval(Statistics.clientIntervals[client_socket.userId])
                stats_redis.set('stat_socket:'+client_socket.userId, JSON.stringify({socketId:null}))
                
                if(Array.isArray(Statistics.users[client_socket.userId])){
                    var index = Statistics.users[client_socket.userId].indexOf(client_socket.id)
                    Statistics.users[client_socket.userId].splice(index,1)
                }
            }
        })

        client_socket.on('user_connect', function(data){
            stats_redis.getAsync('stat_socket:'+data.id).then(function(stat_socket){
                stat_socket = JSON.parse(stat_socket)

                if(stat_socket != null && stat_socket.onPark && client_socket.onPark == 1 && io.of('/statistics_client').connected[stat_socket.socketId] != undefined){
                    client_socket.emit('force_disconnection', Config.prompt.double_park)
                    client_socket.disconnect()
                }
                else{
                    client_socket.userId = data.id
                    clearInterval(Statistics.clientIntervals[data.id])
                    stats_redis.set('stat_socket:'+data.id, JSON.stringify({socketId:client_socket.id, onPark: client_socket.onPark}))
                    Array.isArray(Statistics.users[client_socket.userId]) ? Statistics.users[client_socket.userId].push(client_socket.id) : Statistics.users[client_socket.userId] = [client_socket.id]
                    Statistics.getUserStatistics(client_socket, data)
                }
            })

        })

        client_socket.on('profile_message_history', function(data){
            History.profile_message_history(data, function(result){
                client_socket.emit('profile_message_history_data', result)
            })
        })
    })
}