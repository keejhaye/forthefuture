module.exports = function(io){
  _lodash = require('lodash')
  moment = require('moment-timezone')
  phpdate = require('phpdate-js')
  gmdate = require('phpdate-js').gmdate
  redis = require('redis')
  imv3Models = require('../sequelize.js')
  Users = require('./users.js')
  Dropdown = require('./dropdown.js')
  Config = require('./config.js')
  im2Models = require('../sequelizeIMv2.js')
  History = {
    client_socket : undefined,
    user_data : undefined,
    users : {},

    initialize_history: function(client_socket){
      data = {
        Dropdown,
        default_tz : Config.default_timezone,
        default_tz_name : Config.default_timezone_name
      }
      client_socket.emit('init_history', data)
      History.get_date_preset(Config.default_timezone)
    },

    get_date_preset : function(tz){
      preset = {}

      if(tz == undefined){
        tz = Config.default_timezone
      }

      preset.today = History.get_current_range(tz)
      preset.yesterday = History.get_yesterday_range(tz)
      preset.this_week = History.get_current_week_range(tz)
      preset.this_month = History.get_current_month_range(tz)
      preset.last_month = History.get_last_month_range(tz)

      History.client_socket.emit('get_presets', preset)
    },

    get_current_range : function(timezone){
      var startDate = moment().tz(timezone).format('YYYY-MM-DD 00:00:00')
      var endDate = moment(startDate).format('YYYY-MM-DD 23:59:59')

      range = {
        startDate : startDate,
        endDate : endDate,
      }

      return range
    },

    get_yesterday_range : function(timezone){
      var startDate = moment().tz(timezone).subtract(1, 'days').format('YYYY-MM-DD 00:00:00')
      var endDate = moment(startDate).format('YYYY-MM-DD 23:59:59')

      range = {
        startDate : startDate,
        endDate : endDate,
      }

      return range
    },

    get_current_week_range : function(timezone){
      var startDate = moment().tz(timezone).startOf('isoWeek').format('YYYY-MM-DD 00:00:00')
      var endDate = moment().tz(timezone).endOf('isoWeek').format('YYYY-MM-DD 23:59:59')

      range = {
        startDate : startDate,
        endDate : endDate,
      }

      return range
    }, 

    get_current_month_range : function(timezone){
      var startDate = moment().tz(timezone).format('YYYY-MM-01 00:00:00')
      var endDate = moment(startDate).endOf('month').format('YYYY-MM-DD 23:59:59')

      range = {
        startDate : startDate,
        endDate : endDate,
      }

      return range
    },

    get_last_month_range : function(timezone){
      var startDate = moment().tz(timezone).subtract(1, 'months').format('YYYY-MM-01 00:00:00')
      var endDate = moment(startDate).endOf('month').format('YYYY-MM-DD 23:59:59')

      range = {
        startDate : startDate,
        endDate : endDate,
      }

      return range
    },

    get_conversations : function(params){
      params = History.parseDates(params)
        History["fetch_conversations"](params, function(data){
          History.client_socket.emit('history_data', data)
        })
    },

    parseDates : function(params){
      tz = Config.default_timezone

      if(params.timezone != undefined)
        tz = params.timezone

      params.sdate = moment(moment.tz(params.sdate, tz)).tz(Config.db_timezone).format('YYYY-MM-DD HH:mm:ss');
      params.edate = moment(moment.tz(params.edate, tz)).tz(Config.db_timezone).format('YYYY-MM-DD HH:mm:ss');
      return params
    },

    fetch_conversations : function(params, callback){
      history = { headers : ['Conversation Duration ID', 'Service', 'Subscriber', 'Persona', 'Inbound Message', 'Outbound Message', 'Operator'] }
       if(Config.switch_database_date < params.sdate){
        var Models = imv3Models;
      }else{
        var  Models = im2Models;
      } 
      user_filter = History.user_filter(params.user)
      service_filter = History.service_filter(params.service)
      status_filter = History.status_filter(params.status_filter)
      own_services_filter = History.subquery_user_own_services('s')

           Models.TblConversationDuration.sequelize.query(
        "SELECT cd.conversation_id, cd.id, s.name as service, p.name as persona, ss.name as subscriber, u.username as operator, cd.last_inbound_message as inbound, cd.last_outbound_message as outbound, cd.status as status, cd.last_inbound_time as inbound_time, cd.last_outbound_time as outbound_time, cd.duration as duration " +
          "FROM tbl_conversation_duration cd " +
          "LEFT JOIN tbl_conversations cs ON cs.id = cd.conversation_id " +
          "LEFT JOIN tbl_services s ON s.id = cs.service_id " +
          "LEFT JOIN tbl_personas p ON p.id = cs.persona_id " +
          "LEFT JOIN tbl_subscribers ss ON ss.id = cs.subscriber_id " +
          "LEFT JOIN tbl_users u ON u.id = cd.user_id " + 
          "WHERE cd.time_started BETWEEN ? AND ? " + user_filter + status_filter + service_filter + own_services_filter + 
          "ORDER BY cd.id DESC;",
        {replacements: [params.sdate, params.edate, params.sdate, params.edate, params.sdate, params.edate], type: Models.TblConversationDuration.sequelize.QueryTypes.SELECT})
      .then(function(rows){
        console.log("HISTORY: fetched conversations")
        history.result = rows
        callback(history)
      },
      function(rejectedPromise){
        console.log("HISTORY: conversations rejectedPromise")
        console.log(rejectedPromise)
      })
    },

    user_filter : function(user){
      subquery = ""
      if(user !== undefined)
        subquery += " AND u.username = " + "'" + user + "' ";
      else
       
       subquery = "" 
       
      return subquery
    },

    service_filter : function(service){
      subquery = ""

      if(service !== undefined)
            subquery += " AND s.name = " + "'" + service + "' "; 
      else
      subquery = ""

      return subquery
    },

    status_filter : function(status){
      subquery = ""
      if(status !== undefined)
            subquery += " AND cd.status LIKE " + "'" + status + "%'";
      else
      subquery = ""

      return subquery
    },

    profile_message_history : function(data, callback){
      tz = Config.default_timezone
      switch(data.preset){
        case "today": data.range = this.get_current_range(tz); break;
        case "yesterday": data.range = this.get_yesterday_range(tz); break;
        case "this_week": data.range = this.get_current_week_range(tz); break;
        case "this_month": data.range = this.get_current_month_range(tz); break;
        case "last_month": data.range = this.get_last_month_range(tz); break;
        default : data.range = this.get_current_range(tz); break;
      }

      data.sdate = data.range.startDate
      data.edate = data.range.endDate
      this.parseDates(data)

      Models.RptConversationDuration.sequelize.query(
        "SELECT * FROM rpt_conversation_duration WHERE status = ? AND user_id = ? AND time_started BETWEEN ? AND ? ORDER BY id DESC;",
        {replacements: ['ended', data.uid, data.sdate, data.edate], type: Models.RptConversationDuration.sequelize.QueryTypes.SELECT}
      )
      .then(function(rows){
        var result = {record:rows, total:rows.length, range:data.range}
        callback(result)
      },
      function(rejectedPromise){
        console.log("PROFILE MESSAGE HISTORY: rpt_conversation_duration rejectedPromise")
        console.log(rejectedPromise)
      })
    },

    subquery_user_own_services : function(alias){
      sql = ""

      services = _lodash.trimStart(History.user_data.services, '[')
      services = _lodash.trimEnd(History.user_data.services, ']')

      if(_lodash.has(History.user_data.permissions, "own_services_only")){
        sql += "AND " + alias + ".id IN (" + services + ") "
      }

      return sql
    },
} 

  module.exports = History

} // end of module