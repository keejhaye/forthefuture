module.exports = function(io,Statistics,redisClient) {
    //INCLUDE DEPENDENCIES
    Models = require('./sequelize.js')
    Config = require('./components/config.js')
    redis = require('redis')
    moment = require('moment')
    gmdate = require('phpdate-js').gmdate
    _lodash = require('lodash')
    // const request = require('request');
    const request = require('requestretry');

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
            var numOfTries = 0;

            function myRetryStrategy(err, response, body){
                // retry the request if we had an error or if the response was a 'Bad Gateway'
                numOfTries++;
                Models.TblAggregatorResponses.update({ 
                        attempts: numOfTries, 
                        target_response: err,
                        timeout : response.request.timeout,
                        last_attempt: new Date()}, 
                        { where: { message_id : realdata.id }
                }).then(function(affectedRows) {
                    if (JSON.parse(affectedRows) !== 0) {

                        console.log("Successfully retried the request, but there is still an error.")

                    }else {

                        console.log("No rows affected in myRetryStrategy function.")
                    }

                }).catch(function(err) {

                    console.log("Update query failed at myRetryStrategy function.")

                })
                
                return err || response.statusCode !== 200;
            }


            let options = {  
                url: 'http://im2.nmsloop.com/mock/outbound_receiver/receive',
                timeout: 10000,
                form: {
                    data: JSON.stringify(reply) // replace here!!!!! should be  JSON.stringify(message)
                },
                maxAttempts: 5,   // (default) try 5 times 
                retryStrategy: myRetryStrategy
            };

            request.post(options, function(error, response, body){

                //sending outbound details if target url server response is success
                if (!error && response.statusCode == 200) {

                    console.log("1. initializing update to aggregator table..... ", response.body) 
                    Models.TblAggregatorResponses.update({ 
                            attempts: 1, 
                            last_attempt: new Date()}, 
                            { where: { message_id : realdata.id }} 
                    ).then(function() {

                        outbound_sending_process(realdata.id, response.body)

                    }).catch(function(err) {
                        console.log("initializing update to aggregator table had failed! ", error)
                    })

                }else {

                    console.log("message not sent! there is an error or the status code is not 200 ", error)

                }

                

            });

        }
    })



    outbound_sending_process = function(message_id, body_response) {

        console.log("2. sending outbound data..... ", message_id)    
        Models.TblAggregatorResponses.update({ 
            target_response: body_response, 
            status: 'done', 
            executed: new Date()}, 
            { where: { message_id : message_id }} 
        ).then(function() {

            console.log("3. message sent! ----------------", message_id)
            // POST method route
            app.post('/', function (req, res) {
              res.send('POST request to the homepage')
            })

        }).catch(function() {

            console.log("updating status in aggregator table to done had failed ! ----------------", message_id)

        })

    }
























}