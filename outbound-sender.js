module.exports = function(io,Statistics,redisClient) {
    //INCLUDE DEPENDENCIES
    Models = require('./sequelize.js')
    Services = require('./components/services.js')
    Conversations = require('./components/conversations.js')
    Users = require('./components/users.js')
    Config = require('./components/config.js')
    redis = require('redis')
    moment = require('moment')
    gmdate = require('phpdate-js').gmdate
    _lodash = require('lodash')
    const request = require('request');

    redis_client_outbound = redisClient
    redis_client_outbound.on("error", function (err) {
        console.log("Error redis_client_outbound" + err);
    })

    redis_client_outbound.subscribe('send-targeturl')
    redis_client_outbound.on("message", function (channel, message) {
        if(channel === 'send-targeturl'){

            // FOR NOW THESE ARE THE PARAMETERS RECEIVED IN im2.nmsloop.com. 
            var reply = {
                username : "anubis",
                password : "anubis123",
                service_code : "imtest",
                to : "gale",
                from : "Tonton",
                message : "TEST",
            }
            // THIS IS THE REAL NIGGA data: JSON.stringify(message)
            var realdata = JSON.parse(message)


            let options = {  
                url: 'http://im2.nmsloop.com/mock/outbound_receiver/receive',
                timeout: 10000,
                form: {
                    data: JSON.stringify(reply) // replace here!!!!! should be  JSON.stringify(message)
                }
            };

            request.post(options, function(error, response, body){

                console.log("1. fetching and updating aggregator data..... ", realdata) 
                Models.TblAggregatorResponses.update({ 
                        attempts: 1, 
                        last_attempt: new Date()}, 
                        { where: { message_id : realdata.id }} 
                ).then(function() {

                    //sending outbound details if target url server response is success
                    if (!error && response.statusCode == 200) {

                        outbound_sending_process(realdata.id)

                    }else {

                        console.log("message not sent! 1 ", error)

                    }

                }).catch(function(err) {
                    console.log("fetching and updating aggregator data has failed! ", error)
                })

            });

        }
    })



    outbound_sending_process = function(message_id) {

        console.log("2. sending outbound data..... ", message_id)    
        Models.TblAggregatorResponses.update({ 
            status: 'done', 
            executed: new Date()}, 
            { where: { message_id : message_id }} 
        ).then(function() {

            console.log("message sent! ----------------", message_id)

        }).catch(function() {


        })

    }
























}