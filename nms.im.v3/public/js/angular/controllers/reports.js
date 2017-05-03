function reportsController($scope, $socket, $http, transformRequestAsFormPost, Statistics, $timeout, $document) {
  $scope.statistics = Statistics;
  $scope.status = 'Disconnected';
  $scope.isReady = false;

  $document.ready(function(){
    $scope.isReady = true
    $scope.search = {}
    $scope.reportParams = {}

    $scope.timezones = {}
    $scope.display = {}
    $scope.filter = {}
    $scope.preset = {}
    $scope.dateRange = {}
    $scope.uTypes = {}
    $scope.defaultTZ = undefined
    $scope.generatedReport = []
    $scope.fltrEntity = ''
    $scope.disableFltrInput = true
    $scope.disableUtypeInput = true
    $scope.enableAlert = false
    $scope.generating = false
    $scope.alert = {}

    $scope.logsData = []

    $scope.user_id = user_id
    $scope.role_id = role_id;
    $scope.user_services = JSON.parse(user_services);
  })

  $socket.on('connect', function () {
      console.log('socket successfully connected in reportsController!');
      $scope.status = 'Connected';
      initialize();
  })

  function initialize(){
    if($scope.isReady){
      $socket.emit('user_data', { id : $scope.user_id, services : $scope.user_services, role_id : $scope.role_id });
      initDatePicker();
    }
    else{
      console.log('document not yet ready. restarting operation..');
      $timeout(function(){
          initialize();
      },1000)
    }
  }

  function initDatePicker(){
    $('#datetimepicker6').datetimepicker({
      format: 'YYYY-MM-DD HH:mm:ss',
      useCurrent: false
    });
    $('#datetimepicker7').datetimepicker({
      format: 'YYYY-MM-DD HH:mm:ss',
      useCurrent: false //Important! See issue #1075
    });


    $("#datetimepicker6").on("dp.change", function (e) {
      $('#datetimepicker7').data("DateTimePicker");
      $scope.search.sdate = moment(e.date._d).format('YYYY-MM-DD HH:mm:ss')

      if($scope.search.sdate == undefined){
        $scope.search.sdate = angular.element(document.getElementById('sDate')).val()
      }
    });

    $("#datetimepicker7").on("dp.change", function (e) {
      $('#datetimepicker6').data("DateTimePicker");
      $scope.search.edate = moment(e.date._d).format('YYYY-MM-DD HH:mm:ss')

      if($scope.search.edate == undefined){
        $scope.search.edate = angular.element(document.getElementById('eDate')).val()
      }
    });
  }

  $socket.on('init_reports', function(data){
    $scope.timezones = data.Dropdown.timezones
    $scope.display = data.Dropdown.report_display
    $scope.filter = data.Dropdown.report_filters
    $scope.preset = data.Dropdown.report_date_ranges
    $scope.uTypes = data.Dropdown.user_type
    $scope.search.timezone = data.default_tz
  })

  $socket.on('get_presets', function(data){
    $scope.dateRange = data

    //update current date range
    if($scope.search != undefined && $scope.search.preset != undefined){
      $scope.setDatePreset($scope.search.preset)
    }
  })

  $scope.setDatePreset = function(range){
    if(range != 'custom'){
      // $('#datetimepicker6').data("DateTimePicker").clear()
      // $('#datetimepicker7').data("DateTimePicker").clear()
      $('#datetimepicker6').data("DateTimePicker").date(moment($scope.dateRange[range].startDate)).disable()
      $('#datetimepicker7').data("DateTimePicker").date(moment($scope.dateRange[range].endDate)).disable()
    }
    else{
      $('#datetimepicker6').data("DateTimePicker").enable()
      $('#datetimepicker7').data("DateTimePicker").enable()
    }
  }

  $scope.setTimezone = function(tz){
    $socket.emit('get_tz_date_ranges', tz)
  }

  $scope.generateReport = function(){
    if($scope.search.display == undefined){
      $scope.alert.type = 'danger'
      $scope.alert.message = 'Choose a report to display.'
      $scope.enableAlert = true
    }

    if(continueReportGeneration()){
      $scope.generating = true
      $scope.reportParams = $scope.search
      $socket.emit('generate_report', $scope.search)
    }
  }

  function continueReportGeneration(){
    switch($scope.search.display){
      case undefined: return false; break;
      case 'average_response_time_per_hour':
      case 'operator_messages_per_hour':
      case 'hourly_report':

        if($scope.search.sdate == undefined || $scope.search.edate == undefined){
          $scope.alert.type = 'danger'
          $scope.alert.message = 'Please select a date range'
          $scope.enableAlert = true
          return false
        }
        else{
          var rangeDiff = moment($scope.search.edate).diff(moment($scope.search.sdate), 'days')
          if(rangeDiff != 0){
            $scope.alert.type = 'danger'
            $scope.alert.message = 'Date range must be within 1 day only.'
            $scope.enableAlert = true
            return false
          }
        }
        // return true
        break;
      default:
        if($scope.search.sdate == undefined || $scope.search.edate == undefined){
          $scope.alert.type = 'danger'
          $scope.alert.message = 'Please select a date range'
          $scope.enableAlert = true
          return false
        }
        else return true
        break;
    }
  }

  $socket.on('reports_data', function(data){
    $scope.generating = false
    $scope.enableAlert = false
    $scope.generatedReport = data

    // if(data.result.length > 0){
    // }
    // else{// clear scopes
    //   console.log('no result')
    //   $scope.generatedReport = []
    //   // $scope.search = {} 
    //   // $('#datetimepicker6').data("DateTimePicker").clear()
    //   // $('#datetimepicker7').data("DateTimePicker").clear()
    // }
  })

  $socket.on('logs_data', function(data){
    $scope.generating = false
    if(data.result.length > 0){
      $scope.logsData = data
      showLogsModal()
    }
  })

  function showLogsModal(){
    if(!logsModalIsFilled()){
      $timeout(function(){ showLogsModal() },250)
    }
    else{
      $('.modal').modal('show')
    }
  }

  function logsModalIsFilled(){
    if(document.getElementById('modal-logs-tbody') == null){ return false }
    else{ return true }
  }

  $scope.getLogs = function(id){
    $scope.generating = true
    $scope.reportParams.id = id
    $socket.emit('get_logs', $scope.reportParams)
  }

  $scope.exportCSV = function(){
    var table = document.getElementById('reports-datatable')
    var csvString = ''
    var filename = $scope.search.display + "_" + $scope.search.sdate + "_" + $scope.search.edate

    for(var i=0; i<table.rows.length;i++){
      var rowData = table.rows[i].cells

      for(var j=0; j<rowData.length;j++){
        csvString = csvString + '"' + rowData[j].innerHTML + '",';
      }

      csvString = csvString.substring(0,csvString.length - 1);
      csvString = csvString + "\n";
    }

    csvString = csvString.substring(0, csvString.length - 1)

    var a = $('<a/>', {
            style:'display:none',
            href:'data:application/octet-stream;base64,'+btoa(csvString),
            download: filename + '.csv'
        }).appendTo('body')
    
    a[0].click()
    a.remove();
  }

  $scope.exportLogs = function(){
    var table = document.getElementById('modal-logs-table')
    var csvString = ''
    var filename = $scope.logsData.filename

    for(var i=0; i<table.rows.length;i++){
      var rowData = table.rows[i].cells

      for(var j=0; j<rowData.length;j++){
        csvString = csvString + '"' + rowData[j].innerHTML + '",';
      }

      csvString = csvString.substring(0,csvString.length - 1);
      csvString = csvString + "\n";
    }

    csvString = csvString.substring(0, csvString.length - 1)

    var a = $('<a/>', {
            style:'display:none',
            href:'data:application/octet-stream;base64,'+btoa(csvString),
            download: filename
        }).appendTo('body')
    
    a[0].click()
    a.remove();
  }

  $scope.enableFilter = function(fltr){
    $scope.fltrEntity = fltr
    $scope.search.filter = fltr

    if(fltr == 'no_filter') $scope.disableFltrInput = true;
    else $scope.disableFltrInput = false;
  }

  $scope.fltrSelectedObj = function(obj){
    //access id via: obj.description.id or obj.originalObject.id
    if(obj != undefined)
      $scope.search.filter_id = obj.description.id
  }


} // end of reportsController