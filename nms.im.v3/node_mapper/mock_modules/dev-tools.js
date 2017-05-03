module.exports = function(Statistics){
	Models = require('../custom_modules/sequelize.js')
	Config = require('../custom_modules/components/config.js')
	Users = require('../custom_modules/components/users.js')
	Status = require('../custom_modules/components/status.js')
	Conversations = require('../custom_modules/components/conversations.js')
  _lodash = require('lodash')
  moment = require('moment')
  phpdate = require('phpdate-js')
  gmdate = require('phpdate-js').gmdate

	DevTools = {
    disabledConnectionMessage: "<strong>Sorry, Connection Disabled</strong><p>Administrators have disabled connection for now, please try again later</p>",
    isConnectionEnabled : function(client_socket){
        if(!Config.enable_connection){
            client_socket.emit('force_disconnection', DevTools.disabledConnectionMessage)
            client_socket.disconnect()
        }
    },
	}

	module.exports = DevTools
}