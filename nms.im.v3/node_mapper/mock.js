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
   bluebird         = require("bluebird"),
   request         = require("request");

dotenv.load({ path: '.env.settings' })

const app = express()
const Server = require('http').Server
const server = Server(app)
const io = require('socket.io')(server)

var connection = {port:6379, host:'127.0.0.1', password: 'foobared'}
var redisClient = redis.createClient()

bluebird.promisifyAll(redis.RedisClient.prototype)
bluebird.promisifyAll(redis.Multi.prototype)


/**
 * GET ALL WHATS NEEDED
 */
require('./mock_modules/outbound-receiver.js')(io,redisClient)
Models = require('./custom_modules/sequelize.js')
Mock = require('./mock_modules/outbound-receiver.js')

app.set('view engine', 'html')
  .engine('html', require('ejs').renderFile)
  .set('views', __dirname + '/views')
  .set('port', process.env.PORT || 3001)
  .use(compression())
  .use(logger('dev'))
  .use(bodyParser.json())
  .use(bodyParser.urlencoded({ extended: true }))
  .use(expressValidator())
  .use(express.static(path.join(__dirname, 'public'), { maxAge: 31557600000 }))

console.log('Mock: Express server listening on port %d in %s mode', app.get('port'), app.get('env'))
server.listen(app.get('port'))

/**
 * LISTEN TO OUTBOUND MESSAGES
 */
redisClient.subscribe('send-outbound')
console.log('Mock: redis client susbcribed to `send-outbound` channel')

redisClient.on("message", function (channel, message) {
    if(channel == 'send-outbound'){
      Mock.sendReply(JSON.parse(message))
    }
})

/**
 * CONNECT TO OUTBOUND_MOCK SOCKET
 */
io.of('/outbound_mock')
  .on('connection', function(socket){
    console.log('Mock socket connected')
    Mock.clientSocket = socket
    /**
     * INITIALIZE BY GETTING ALL SERVICES AND CONVERSATIONS 
     */
    Mock.init()
  })
