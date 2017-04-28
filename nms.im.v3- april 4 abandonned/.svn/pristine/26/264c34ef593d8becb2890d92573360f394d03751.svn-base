// This file handles the configuration of the app.
// It is required by app.js

	var express = require('express'),
	bodyParser  = require('body-parser'),
	session     = require('express-session'),
	uuid        = require('node-uuid');
	// RedisStore  = require('connect-redis')(session),
	// redis       = require('redis'),
	// redisClient = redis.createClient();

module.exports = function(app, io){

	// Set .html as the default template extension
	app.set('view engine', 'html')

	// Initialize the ejs template engine
	app.engine('html', require('ejs').renderFile)

	// Tell express where it can find the templates
	app.set('views', __dirname + '/views')

	// Make the files in the public folder available to the world
	app.use(express.static(__dirname + '/public'))


	var sessionMiddleware = session({
		genid: function(req) {
			return uuid.v4() // use UUIDs for session IDs
		},
		// store: new RedisStore({
		//     client :redisClient,
		//     host: 'localhost',         // optional
		//     port: 6379,                // optional
		//     type: 'redis',
		//     prefix: 'sess',            // optional
		//     timeout: 10000             // optional
		// }),
		name: 'express.sid',
		key: 'express.sid',
		secret: 'WS314@23123%$!s43dqaDQ',
		duration: 30 * 60 * 1000,
		resave : false,
		saveUninitialized : true,
		activeDuration: 365 * 24 * 60 * 60 * 1000,
		cookie: {
			path: '/', // cookie will only be sent to requests under '/'
			maxAge: 365 * 24 * 60 * 60 * 1000, // duration of the cookie in milliseconds, defaults to duration above
			httpOnly: false, // when true, cookie is not accessible from javascript
			secure: false // when true, cookie will only be sent over SSL. use key 'secureProxy' instead if you handle SSL not in your node process
		}
	});


	// support json encoded bodies
	app.use(bodyParser.json())

	// support encoded bodies
	app.use(bodyParser.urlencoded({ extended: true }))
	io.use(function(socket, next) {
	    sessionMiddleware(socket.request, socket.request.res, next);
	})
	app.use(sessionMiddleware);

};
