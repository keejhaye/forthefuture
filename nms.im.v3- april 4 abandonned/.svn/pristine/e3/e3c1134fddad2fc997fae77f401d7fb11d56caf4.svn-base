module.exports = function(io, redis) {

	var CronJob = require('cron').CronJob
	var job = new CronJob({
	  cronTime: '3 * * * * * *',
	  onTick: function() {
		console.log(conversations)
		console.log(_lodash)
	  },
	  start: false,
	  timeZone: 'America/Los_Angeles'
	})

	job.start()

}