module.exports = function(io){
  _lodash = require('lodash')
  moment = require('moment-timezone')
  phpdate = require('phpdate-js')
  gmdate = require('phpdate-js').gmdate
  redis = require('redis')
  Models = require('../sequelize.js')
  Users = require('./users.js')
  Dropdown = require('./dropdown.js')
  Config = require('./config.js')

  Reports = {
    client_socket : undefined,
    user_data : undefined,
    users : {},

    initialize_reports : function(client_socket){
      data = {
        Dropdown,
        default_tz : Config.default_timezone
      }

      client_socket.emit('init_reports', data)
      Reports.get_date_preset(Config.default_timezone)
    },

    get_date_preset : function(tz){
      preset = {}

      if(tz == undefined){
        tz = Config.default_timezone
      }

      preset.today = Reports.get_current_range()
      preset.yesterday = Reports.get_yesterday_range()
      preset.this_week = Reports.get_current_week_range()
      preset.this_month = Reports.get_current_month_range()
      preset.last_month = Reports.get_last_month_range()

      Reports.client_socket.emit('get_presets', preset)
    },

    get_current_range : function(timezone){
      var startDate = moment().format('YYYY-MM-DD 00:00:00')
      var endDate = moment(startDate).format('YYYY-MM-DD 23:59:59')

      range = {
        startDate : startDate,
        endDate : endDate,
      }

      return range
    },

    get_yesterday_range : function(){
      var startDate = moment().subtract(1, 'days').format('YYYY-MM-DD 00:00:00')
      var endDate = moment(startDate).format('YYYY-MM-DD 23:59:59')

      range = {
        startDate : startDate,
        endDate : endDate,
      }

      return range
    },

    get_current_week_range : function(){
      var startDate = moment().startOf('isoWeek').format('YYYY-MM-DD 00:00:00')
      var endDate = moment().endOf('isoWeek').format('YYYY-MM-DD 23:59:59')

      range = {
        startDate : startDate,
        endDate : endDate,
      }

      return range
    }, 

    get_current_month_range : function(){
      var startDate = moment().format('YYYY-MM-01 00:00:00')
      var endDate = moment(startDate).endOf('month').format('YYYY-MM-DD 23:59:59')

      range = {
        startDate : startDate,
        endDate : endDate,
      }

      return range
    },

    get_last_month_range : function(){
      var startDate = moment().subtract(1, 'months').format('YYYY-MM-01 00:00:00')
      var endDate = moment(startDate).endOf('month').format('YYYY-MM-DD 23:59:59')

      range = {
        startDate : startDate,
        endDate : endDate,
      }

      return range
    },

    generate_report : function(params){
      params = Reports.parseDates(params)
      params.log_start = moment().tz(Config.db_timezone).format('YYYY-MM-DD HH:mm:ss')

      Reports[params.display](params, function(data){
        data.display = params.display
        Reports.client_socket.emit('reports_data', data)

        //log report
        params.log_end = moment().tz(Config.db_timezone).format('YYYY-MM-DD HH:mm:ss')
        params.user_id = Reports.user_data.id
        params.username = Reports.user_data.username
        params.display = params.display.split('_').join(' ')
        params.date_created = moment().tz(Config.db_timezone).format('YYYY-MM-DD HH:mm:ss')
        Models.UtlReportLogs.addLog(params)
      })
    },

    get_logs : function(params){
      console.log(params)
      params._sdate = params.sdate
      params._edate = params.edate
      params = Reports.parseDates(params)

      Reports.entity_messages_logs(params, function(data){

        //format date bound time
        if(data.result.length > 0){
          for (var i = data.result.length - 1; i >= 0; i--) {
            data.result[i].bound_time = moment(moment.tz(data.result[i].bound_time, params.timezone)).tz(Config.db_timezone).format('YYYY-MMM-DD HH:mm a')
          };
        }

        Reports.client_socket.emit('logs_data', data)
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

    //---------------------------------- START OF QUERIES ----------------------------------//
    
    entity_messages_logs : function(params, callback){
      report = { headers : ['Operator', 'Persona', 'Subscriber', 'Service', 'Message', 'Bound Time'] }

      params = Reports.filter_entity_messages_logs(params)

      Models.RptMessages.sequelize.query(
        "SELECT rm.fullname, rm.persona_name, rm.subscriber_name, rm.service_name, m.message, rm.bound_time " +
        "FROM rpt_messages rm " +
        "LEFT JOIN tbl_messages m ON m.id = rm.message_id " +
        "WHERE rm.bound_time BETWEEN ? AND ? " +
        "AND rm.direction = ? " + params.filter,
      {replacements : [params.sdate, params.edate, params.direction], type: Models.RptMessages.sequelize.QueryTypes.SELECT})
      .then(function(rows){
        console.log("REPORTS: entity_messages_logs generated")
        report.filename = "message_logs_" + params.entity + "_" + params._sdate + "_" + params._edate + ".csv"
        report.result = rows
        callback(report)
      },
      function(rejectedPromise){
        console.log("REPORTS: entity_messages_logs rejectedPromise")
        console.log(rejectedPromise)
      })

    },

    average_response_time_per_hour : function(params, callback){
      report = { headers : ['Hour', '# of Inbound Messages', '# of Chatting Operators', 'Average Response Time'] }

      Models.RptMessageInterval.sequelize.query(
        "SELECT " +
          "FirstSet.per_hour AS per_hour " +
          ",FirstSet.inbound AS inbound " +
          ",SecondSet.operator AS operators " +
          ",FirstSet.average AS ave " +
        "FROM (" + 
          "SELECT DATE_FORMAT( " +
            "DATE_ADD(rmi.inbound_time, INTERVAL 8 HOUR),'%H:00') AS per_hour, " +
            "COUNT(rmi.`inbound_id`) AS inbound, " +
            "COUNT(DISTINCT(rmi.`user_id`)) AS operator, " +
            "AVG(rmi.`response_interval`) AS average " +
          "FROM rpt_message_interval rmi " +
            "WHERE rmi.`inbound_time` BETWEEN ? AND ? " +
            "GROUP BY per_hour " +
        ") AS FirstSet " +
        "INNER JOIN " +
          "(SELECT DATE_FORMAT( " +
            "DATE_ADD(rmi.outbound_time, INTERVAL 8 HOUR),'%H:00') AS per_hour, " +
            "COUNT(rmi.`inbound_id`) AS inbound, " +
            "COUNT(DISTINCT(rmi.`user_id`)) AS operator " +
          "FROM rpt_message_interval rmi " + 
            "WHERE rmi.`outbound_time` BETWEEN ? AND ? " +
            "GROUP BY per_hour " +
        ") AS SecondSet " +
        "ON FirstSet.per_hour = SecondSet.per_hour " +
        "GROUP BY  FirstSet.per_hour " +
        "ORDER BY FirstSet.per_hour;",
      {replacements : [params.sdate, params.edate, params.sdate, params.edate], type: Models.RptMessageInterval.sequelize.QueryTypes.SELECT})
      .then(function(rows){
        console.log("REPORTS: average_response_time_per_hour generated")
        report.result = rows
        callback(report)
      },
      function(rejectedPromise){
        console.log("REPORTS: average_response_time_per_hour rejectedPromise")
        console.log(rejectedPromise)
      })
    },

    total_messages_per_subscriber : function(params, callback){
      report = { headers : ['Subscriber', 'Inbound', 'Outbound', 'Free', 'Billed', 'Logs'] }
      filter = Reports.total_messages_per_entity_filter(params)

      Models.RptMessages.sequelize.query(
        "SELECT * FROM " +
          "(SELECT rm.subscriber_id, rm.subscriber_name as subscriber_name_in, COUNT(rm.id) AS inbound, " +
          "SUM(IF(rm.is_billed = 0, 1, 0)) AS free, SUM(IF(rm.is_billed = 0, 0, 1)) AS billed " +
          "FROM rpt_messages rm " +
          "WHERE rm.bound_time BETWEEN ? AND ? " +
          "AND rm.direction = 'inbound' " + filter +
          "GROUP BY rm.subscriber_id) AS sub_in " +
          "LEFT JOIN " +
          "(SELECT rm.subscriber_id, rm.subscriber_name as subscriber_name_out, COUNT(rm.id) AS outbound " +
          "FROM rpt_messages rm " +
          "WHERE rm.bound_time BETWEEN ? AND ? " +
          "AND rm.direction = 'outbound' " + filter +
          "GROUP BY rm.subscriber_id) AS sub_out ON sub_out.subscriber_id = sub_in.subscriber_id;", 
        {replacements: [params.sdate, params.edate, params.sdate, params.edate], type: Models.RptMessages.sequelize.QueryTypes.SELECT})
        .then(function(rows){
          console.log("REPORTS: total_messages_per_subscriber generated")
          report.result = rows
          callback(report)
        },
        function(rejectedPromise){
          console.log("REPORTS: total_messages_per_subscriber rejectedPromise")
          console.log(rejectedPromise)
        })
    },

    total_messages_per_service : function(params, callback){
      report = { headers : ['Service', 'Inbound', 'Outbound', 'Free', 'Billed'] }
      filter = Reports.total_messages_per_entity_filter(params)

      Models.RptMessages.sequelize.query(
        "SELECT * FROM " + 
          "(SELECT rm.service_id, rm.service_name as service_name_in, COUNT(rm.id) AS inbound " +          
          "FROM rpt_messages rm " +
          "WHERE rm.bound_time BETWEEN ? AND ? " +
          "AND rm.direction = 'inbound' " + filter +
          "GROUP BY rm.service_id) AS msg_in " +
          "LEFT JOIN " +
          "(SELECT rm.service_id, rm.service_name as service_name_out, COUNT(rm.id) AS outbound, " +
          "SUM(IF(rm.is_billed = 0, 1, 0)) AS free, SUM(IF(rm.is_billed = 0, 0, 1)) AS billed " +
          "FROM rpt_messages rm " +
          "WHERE rm.bound_time BETWEEN ? AND ? " +
          "AND rm.direction = 'outbound' " + filter +
          "GROUP BY rm.service_id) AS msg_out ON msg_out.service_id = msg_in.service_id;",
      {replacements: [params.sdate, params.edate, params.sdate, params.edate], type: Models.RptMessages.sequelize.QueryTypes.SELECT})
      .then(function(rows){
        console.log("REPORTS: total_messages_per_service generated")
        report.result = rows
        callback(report)
      },
      function(rejectedPromise){
        console.log("REPORTS: total_messages_per_service rejectedPromise")
        console.log(rejectedPromise)
      })
    },

    total_messages_per_user : function(params, callback){
      report = { headers : ['User', 'Outbound', 'Free', 'Billed', 'Logs'] }
      filter = Reports.total_messages_per_entity_filter(params)

      Models.RptMessages.sequelize.query(
        "SELECT rm.user_id, rm.fullname as fullname_out, COUNT(rm.id) AS outbound, " +
          "SUM(IF(rm.is_billed = 0, 1, 0)) AS free, SUM(IF(rm.is_billed = 0, 0, 1)) AS billed " +
          "FROM rpt_messages rm " +
          "WHERE rm.bound_time BETWEEN ? AND ? " +
          "AND rm.direction = 'outbound' " + filter +
          " GROUP BY rm.user_id;",
      {replacements: [params.sdate, params.edate], type: Models.RptMessages.sequelize.QueryTypes.SELECT})
      .then(function(rows){
        console.log("REPORTS: total_messages_per_user generated")
        report.result = rows
        callback(report)
      },
      function(rejectedPromise){
        console.log("REPORTS: total_messages_per_user rejectedPromise")
        console.log(rejectedPromise)
      })
    },

    total_messages_per_persona : function(params, callback){
      report = { headers : ['Persona', 'Inbound', 'Outbound', 'Free', 'Billed', 'Logs'] }
      filter = Reports.total_messages_per_entity_filter(params)

      Models.RptMessages.sequelize.query(
        "SELECT * FROM " +
        "(SELECT rm.persona_id, rm.persona_name as persona_name_in, COUNT(rm.id) AS inbound, " + 
        "SUM(IF(rm.is_billed = 0, 1, 0)) AS free, SUM(IF(rm.is_billed = 0, 0, 1)) AS billed " +
        "FROM rpt_messages rm " +
        "WHERE rm.bound_time BETWEEN ? AND ? " +
        "AND rm.direction = 'inbound' " + filter +
        "GROUP BY rm.persona_id) AS per_in " +
        "LEFT JOIN " +
        "(SELECT rm.persona_id, rm.persona_name as persona_name_out, COUNT(rm.id) AS outbound " +
        "FROM rpt_messages rm " +
        "WHERE rm.bound_time BETWEEN ? AND ? " +
        "AND rm.direction = 'outbound' " + filter +
        "GROUP BY rm.persona_id) AS per_out ON per_out.persona_id = per_in.persona_id;",
      {replacements: [params.sdate, params.edate, params.sdate, params.edate], type: Models.RptMessages.sequelize.QueryTypes.SELECT})
      .then(function(rows){
        console.log("REPORTS: total_messages_per_persona generated")
        report.result = rows
        callback(report)
      },
      function(rejectedPromise){
        console.log("REPORTS: total_messages_per_persona rejectedPromise")
        console.log(rejectedPromise)
      })
    },

    operator_messages_per_hour : function(params, callback){
      report = { headers : ['Hour', 'Operator', 'Replies', 'Susbcriber', 'Logs'] }
      filter = Reports.total_messages_per_entity_filter(params)

      Models.RptMessages.sequelize.query(
        "SELECT HOUR(rpm.bound_time) AS hour_in, rpm.user_id, " +
          "rpm.fullname, COUNT(rpm.id) AS replies, " +
          "COUNT(DISTINCT(rpm.subscriber_id)) AS subscribers " +
          "FROM rpt_messages rpm " +
          "WHERE rpm.bound_time BETWEEN ? AND ? " +
          "AND rpm.direction = 'outbound' " + filter +
          "GROUP BY hour_in, rpm.user_id;",
        {replacements : [params.sdate, params.edate], type: Models.RptMessages.sequelize.QueryTypes.SELECT})
        .then(function(rows){
          console.log("REPORTS: operator_messages_per_hour generated")
          report.result = rows
          callback(report)
        },
        function(rejectedPromise){
          console.log("REPORTS: operator_messages_per_hour rejectedPromise")
          console.log(rejectedPromise)
        })
    },

    hourly_report : function(params, callback){
      report = { headers : ['Hour', 'Inbound', 'Outbound', 'Free'] }
      service_filter = Reports.subquery_service_filter("rpm", params)

      in_query = "SELECT HOUR(rpm.bound_time) AS hour_in, COUNT(rpm.id) AS count_in, SUM(IF(rpm.is_billed = 0, 1, 0)) AS free " +
                  "FROM rpt_messages rpm " +
                  "WHERE rpm.bound_time BETWEEN ? AND ? " +
                  "AND rpm.direction = 'inbound' " + service_filter +
                  "GROUP BY HOUR(rpm.bound_time)";

      out_query = "SELECT HOUR(rpm.bound_time) AS hour_out, COUNT(rpm.id) AS count_out " +
                  "FROM rpt_messages rpm " +
                  "WHERE rpm.bound_time BETWEEN ? AND ? " +
                  "AND rpm.direction = 'outbound' " + service_filter +
                  "GROUP BY HOUR(rpm.bound_time)";

      first_join = "SELECT IF(hour_in IS NULL, hour_out, hour_in) AS hour_in, " +
                    "IF(count_in IS NULL, 0, count_in) AS count_in, " +
                    "IF(hour_out IS NULL, hour_in, hour_out) AS hour_out, " +
                    "IF(count_out IS NULL, 0, count_out) AS count_out, " +
                    "free " +
                    "FROM (" + in_query + ") as hourly_in " +
                    "LEFT JOIN (" + out_query + ") as hourly_out ON hourly_in.hour_in = hourly_out.hour_out";

      second_join = "SELECT IF(hour_in IS NULL, hour_out, hour_in) AS hour_in, " +
                    "IF(count_in IS NULL, 0, count_in) AS count_in, " +
                    "IF(hour_out IS NULL, hour_in, hour_out) AS hour_out, " +
                    "IF(count_out IS NULL, 0, count_out) AS count_out, " +
                    "free " +
                    "FROM (" + out_query + ") as hourly_out " +
                    "LEFT JOIN (" + in_query + ") as hourly_in ON hourly_in.hour_in = hourly_out.hour_out;";

      Models.RptMessages.sequelize.query(
        first_join + " UNION " + second_join,
      {replacements : [params.sdate, params.edate, params.sdate, params.edate, params.sdate, params.edate, params.sdate, params.edate, params.sdate, params.edate, params.sdate, params.edate], type: Models.RptMessages.sequelize.QueryTypes.SELECT})
        .then(function(rows){
          console.log("REPORTS: hourly_report generated")
          report.result = rows
          callback(report)
        },
        function(rejectedPromise){
          console.log("REPORTS: hourly_report rejectedPromise")
          console.log(rejectedPromise)
        })
    },

    kpi : function(params, callback){
      report = { headers : ['Service', 'Inbound', 'Discarded', 'Subscribers', 'KPI'] }

      Models.RptMessages.sequelize.query(
        "SELECT service, inbound, discarded, subscribers, ROUND(diff/subscribers,2) AS kpi " +
          "FROM(SELECT rm.service_name AS service, " +
          "COUNT(rm.id) AS inbound, " +
          "(SELECT COUNT(r.id) FROM rpt_messages r WHERE r.status='discard' AND r.service_id = rm.service_id AND r.status='discard'" +
          "AND r.bound_time BETWEEN ? AND ?) AS discarded, " +
          "COUNT(DISTINCT(rm.subscriber_id)) AS subscribers, " +
          "COUNT(rm.id)- (SELECT COUNT(r.id) FROM rpt_messages r WHERE r.status='discard' " +
          "AND r.direction = 'inbound' AND r.service_id = rm.service_id AND r.bound_time BETWEEN ? AND ?) AS diff " +
          "FROM rpt_messages rm " +
          "WHERE rm.bound_time BETWEEN ? AND ? " +
          "AND rm.direction = 'inbound' " +
          "GROUP BY rm.service_id) AS q;",
      {replacements: [params.sdate, params.edate, params.sdate, params.edate, params.sdate, params.edate], type: Models.RptMessages.sequelize.QueryTypes.SELECT})
      .then(function(rows){
        console.log("REPORTS: kpi generated")
        report.result = rows
        callback(report)
      },
      function(rejectedPromise){
        console.log("REPORTS: kpi rejectedPromise")
        console.log(rejectedPromise)
      })
    },

    operator_messages_and_payout: function(params, callback){
      date_gap = moment(params.edate).diff(moment(params.sdate), 'days')
      params.date_gap = date_gap
      report = {}

      multiplier_subquery = ""
      user_type_filter = Reports.user_type_filter(params.utype)
      filter = Reports.total_messages_per_entity_filter(params)

      //get all multipliers
      Models.TblServices.sequelize.query("SELECT DISTINCT (s.multiplier) FROM tbl_services s",
        {replacements:[], type: Models.RptMessages.sequelize.QueryTypes.SELECT})
      .then(function(rows){
        report.multipliers = rows
        report.headers = ['Operator', 'Messages']

        if(rows.length > 0){
          _lodash(rows).forEach(function(row){
            report.headers.push('x'+row.multiplier)
            multiplier_subquery += ", SUM(IF(s.multiplier = '" + row.multiplier + "', 1, 0)) AS 'x" + row.multiplier + "'"
          })
        }

        report.headers = _lodash.union(report.headers, ['Deductions', 'Rewards', 'Total Adjustment', 'Message Logs'])

        base_query = "SELECT rm.user_id AS rm_user_id, rm.fullname, COUNT(rm.id) as count " + multiplier_subquery + ", u.username " +
          "FROM rpt_messages rm " +
          "LEFT JOIN tbl_services s ON s.id = rm.service_id " +
          "LEFT JOIN tbl_users u ON u.id = rm.user_id " +
          "LEFT JOIN tbl_roles r ON u.role_id = r.id " +
          "WHERE rm.bound_time BETWEEN ? AND ? " +
          "AND rm.direction = 'outbound' " + filter + user_type_filter +
          "GROUP BY rm.user_id"

        reward_query = "SELECT fm.operator_id, COUNT(fm.id) AS rewards FROM tbl_flagged_messages fm " + 
          "WHERE fm.date_flagged BETWEEN ? AND ? AND fm.status = 'approved' GROUP BY fm.operator_id"

        deductions_subquery = "SELECT m.user_id as operator_id, COUNT(fm.id) AS deductions " +
          "FROM tbl_flagged_messages fm LEFT JOIN tbl_messages m ON fm.message_id = m.id " +
          "WHERE fm.date_flagged BETWEEN ? AND ? AND fm.status = 'approved' " 
          "GROUP BY m.user_id"


        statement = "SELECT * FROM (" + base_query + ") AS po_1 " +
          "LEFT JOIN (" + reward_query + ") AS po_3 ON po_1.rm_user_id = po_3.operator_id " + 
          "LEFT JOIN (" + deductions_subquery + ") AS po_4 ON po_1.rm_user_id = po_4.operator_id"

        Models.RptMessages.sequelize.query(
          statement, {replacements: [params.sdate, params.edate, params.sdate, params.edate, params.sdate, params.edate],
          type: Models.RptMessages.sequelize.QueryTypes.SELECT})
        .then(function(rows){
          console.log("REPORTS: operator_messages_and_payout generated")
          report.result = rows
          callback(report)
        },
        function(rejectedPromise){
          console.log("REPORTS: operator_messages_and_payout rejectedPromise")
          console.log(rejectedPromise)
        })
      })
    },
    //---------------------------------- END OF QUERIES ----------------------------------//

    //---------------------------------- START OF QUERY FILTERS ----------------------------------//
    user_type_filter : function(utype){
      subquery = ""

      if(utype == "operator-fl" || utype == "operator-ft")
        subquery += " AND r.name = " + "'" + utype + "'";
      else if (utype == "others")
        subquery += " AND r.name IN ('super', 'manager', 'admin') ";
      else
        subquery = "";

      return subquery
    },

    total_messages_per_entity_filter : function(params){
      sql = ""

      if (params.filter != undefined && params.filter_id != undefined) {
        switch (params.filter) {
          case "subscriber":
            sql = " AND rm.subscriber_id = " + params.filter_id
            break;
          case "service":
            sql = " AND rm.service_id = " + params.filter_id
            break;
          case "persona":
            sql = " AND rm.persona_id = " + params.filter_id
            break;
          case "user":
            sql = " AND rm.user_id = " + params.filter_id
            break;
        }//end of switch
      }//end of if

      sql += Reports.subquery_user_own_services("rm");

      return sql;
    },

    subquery_user_own_services : function(alias){
      sql = ""

      services = _lodash.trimStart(Reports.user_data.services, '[')
      services = _lodash.trimEnd(Reports.user_data.services, ']')

      if(_lodash.has(Reports.user_data.permissions, "own_services_only")){
        sql += " AND " + alias + ".service_id IN (" + services + ")"
      }

      return sql
    },

    subquery_service_filter : function(alias, params){
      sql = ""
      if (params.filter_by != undefined && params.filter_by == "service" && params.filter_id != undefined)
        sql = " AND " +  alias + ".service_id = " + params.filter_id;
      return sql;
    },

    filter_entity_messages_logs : function(params){
      params.direction = 'inbound'
      
      switch(params.display){
        case 'total_messages_per_user':
          params.direction = 'outbound'
          params.filter = "AND rm.user_id = " + params.id
          params.entity = "operator"
          break;
        case 'total_messages_per_subscriber':
          params.filter = "AND rm.subscriber_id = " + params.id
          params.entity = "subscriber"
          break;
        case 'total_messages_per_persona':
          params.filter = "AND rm.persona_id = " + params.id
          params.entity = "persona"
          break;
      }

      return params
    }
    
    //---------------------------------- END OF QUERY FILTERS ----------------------------------//

  }

  module.exports = Reports

} // end of module test