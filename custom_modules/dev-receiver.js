module.exports = function(io,Statistics,redisClient) {
    //INCLUDE DEPENDENCIES
    Models = require('./sequelize.js')
    Services = require('./components/services.js')
    Conversations = require('./components/conversations.js')
    History = require('./components/history.js')
    Reports = require('./components/reports.js')
    Users = require('./components/users.js')
    Config = require('./components/config.js')
    redis = require('redis')
    moment = require('moment')
    _lodash = require('lodash')

    redisClient.on("error", function (err) {
        console.log("Error redis_client_update" + err);
    })
    
    redisClient.subscribe('dev-info')
    redisClient.subscribe('dev-actions')
    redisClient.on("message", function (channel, info) {
        if(channel === 'dev-info'){
            receive_info_process(JSON.parse(info))
        }

        if(channel === 'dev-actions'){
            receive_dev_process(JSON.parse(info))
        }
    })

    var chat_status = io.of('/chat_status')
    chat_status.on('connection', function(client_socket){
        var referer = client_socket.handshake.headers.referer
        console.log(referer)

        if(referer.match('mock/dev/status/all') != null){
            get_inbound();
            get_outbound();
        }
    })

    get_inbound = function(){
        
    }

    get_outbound = function(){

    }

    receive_info_process = function(info){
        if(info.action === 'inbound_request'){
            inbound_request(info)
        }
        if(info.action === 'outbound_request'){
            outbound_request(info)
        }
    }

    receive_dev_process = function(info){
        if(info.action === 'disconnect'){
            disconnect_sockets();
        }
        if(info.action === 'enable_connection'){
            enable_socket_connection();
        }
        if(info.action === 'reload_page'){
            emit_page_reload()
        }
    }


    inbound_request = function(info){
        io.of('/chat_status').emit('inbound_request', info)
    }
    outbound_request = function(info){
        io.of('/chat_status').emit('outbound_request', info)
    }

    disconnection_message = function(){
        var message = "<strong>Sorry to Interrupt.</strong> <p>You have been disconnected by the administrators.</p>"
        return message
    }

    disconnect_sockets = function(){
        Config.enable_connection = false

        if(io.of('/statistics').connected != undefined){
            process_disconnection('/statistics')
        }
        if(io.of('/statistics_client').connected != undefined){
            process_disconnection('/statistics_client')
        }
        if(io.of('/chat').connected != undefined){
            process_disconnection('/chat')
        }
        if(io.of('/history').connected != undefined){
            process_disconnection('/history')
        }
        if(io.of('/reports').connected != undefined){
            process_disconnection('/reports')
        }
    }

    process_disconnection = function(namespace){
        for(i in io.of(namespace).connected){
            var socket_id = io.of(namespace).connected[i].id

            io.of(namespace).to(socket_id).emit('force_disconnection', disconnection_message())
            io.of(namespace).sockets[socket_id].disconnect()
        }
    }

    enable_socket_connection = function(){
        Config.enable_connection = true
    }

    emit_page_reload = function(){
        if(io.of('/all').connected != undefined){
            io.of('/all').emit('reload_page')
        }
    }

} // --END-- /