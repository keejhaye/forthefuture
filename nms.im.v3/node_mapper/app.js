/**
 * Module dependencies.
 */

 const express      = require('express'),
   compression      = require('compression'),
   session          = require('express-session'),
   bodyParser       = require('body-parser'),
   logger           = require('morgan'),
   errorHandler     = require('errorhandler'),
   dotenv           = require('dotenv'),
   path             = require('path'),
   expressValidator = require('express-validator'),
   uuid             = require('node-uuid'),
   redis            = require('redis'),
   bluebird         = require("bluebird");

/**
 * Load environment variables from .env file, where API keys and passwords are configured.
 */
dotenv.load({ path: '.env.settings' })

/**
 * Create Express server.
 */
const app = express()
const Server = require('http').Server
const server = Server(app)

/**
 * Create Socket IO Server Instance.
 */
const io = require('socket.io')(server)

/**
 * SOCKET IO.
 */
io.use(function(socket, next) {
    sessionMiddleware(socket.request, socket.request.res, next)
})
/**
 * Controllers (route handlers).
 */
const mapperStatusController = require('./controllers/mapperStatus')

/**
 * Session variable to be used by both socketIO and Express
 */
const sessionMiddleware = session({
  genid: function(req) {
    return uuid.v4() // use UUIDs for session IDs
  },
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
    httpOnly: true, // when true, cookie is not accessible from javascript
    secure: false // when true, cookie will only be sent over SSL. use key 'secureProxy' instead if you handle SSL not in your node process
  }
})

// var connection = {port:6379, host:'127.0.0.1', password: 'S6uR-mIAtDGrl87KOKEm1@cIbriwVB4C'}
var redisClient = redis.createClient()

/**
 * Bluebird.
 */
bluebird.promisifyAll(redis.RedisClient.prototype)
bluebird.promisifyAll(redis.Multi.prototype)

/**
 * Get Statistics Object Model
 */
Statistics = require('./custom_modules/components/statistics.js')(io)

/**
 * Get Sequelize Models
 */
Models = require('./custom_modules/sequelize.js')

/**
 * Get Users Object Model
 */
Users = require('./custom_modules/components/users.js')

/**
 * Get Services Object Model
 */
Services = require('./custom_modules/components/services.js')(Statistics)

/**
 * Get Reports Object Model
 */
Reports = require('./custom_modules/components/reports.js')(io)


History = require('./custom_modules/components/history.js')(io)
/**
/**
 * Get Conversations Object Model
 */
Conversations = require('./custom_modules/components/conversations.js')(io,Statistics)


const DevTools = require('./mock_modules/dev-tools')(Statistics)
/**
 * BROWSER MANAGER MODULE.
 */
const browser_manager = require('./custom_modules/browser-manager')(io,Statistics)

/**
 * BROWSER MANAGER MODULE.
 */
const statistics_manager = require('./custom_modules/statistics-manager')(io,Statistics,redisClient)

/**
 * INBOUND DETAILS RECEIVER(FROM PHP VIA REDIS) MODULE.
 */
const inbound_receiver = require('./custom_modules/inbound-receiver')(io,Statistics,redisClient)

/**
 * OUTBOUND DETAILS RECEIVER(FROM PHP VIA REDIS) MODULE.
 */
const outbound_receiver = require('./custom_modules/outbound-receiver')(io,Statistics,redisClient)

/**
 * OUTBOUND DETAILS RECEIVER(FROM PHP VIA REDIS) MODULE.
 */
const update_receiver = require('./custom_modules/update-receiver')(io,Statistics,redisClient)

/**
 * OUTBOUND DETAILS SENDER(FROM PHP VIA REDIS) MODULE.
 */
const outbound_sender = require('./custom_modules/outbound-sender')(io,Statistics,redisClient)

const dev_receiver = require('./custom_modules/dev-receiver')(io,Statistics,redisClient)
/**
 * OUTBOUND DETAILS RECEIVER(FROM PHP VIA REDIS) MODULE.
 */
const status = require('./custom_modules/components/status')(io)
/**
 * MEMORY WATCHER/LOGGER MODULE.
 */
//const memory_watch = require('./components/memory-watch')

/**
 * OUTBOUND DETAILS RECEIVER(FROM PHP VIA REDIS) MODULE.
 */
// const cron_job = require('./cron-jobs')(io,redis)

/**
 * Express configuration.
 */

// Set .html as the default template extension
app.set('view engine', 'html')
  // Initialize the ejs template engine
  .engine('html', require('ejs').renderFile)
  // Tell express where it can find the templates
  .set('views', __dirname + '/views')
  .set('port', process.env.PORT || 3000)
  .use(compression())
  .use(logger('dev'))
  .use(bodyParser.json())
  .use(bodyParser.urlencoded({ extended: true }))
  .use(expressValidator())
  .use(express.static(path.join(__dirname, 'public'), { maxAge: 31557600000 }))
  .use(sessionMiddleware)

/**
 * Primary app routes.
 */
  .get('/', mapperStatusController.index)

/**
 * Error Handler.
 */
  .use(errorHandler(true))

/**
 * --------- END APP USE
 */
/**
 * Start Express server.
 */

/**
 * BOOTSTRAP MODULE.
 */
const ServerInitialize = require('./custom_modules/components/bootstrap')(server, app, Statistics)

module.exports = app
