function historyController($scope,$socket, $http, API_URL, transformRequestAsFormPost, $timeout, $document,Statistics) {
  $scope.statistics = Statistics;
  $scope.status = 'Disconnected';
  $scope.isReady = false;

  $document.ready(function($interval){
    
    $scope.isReady = true
    $scope.search = {}
    $scope.timezones = {}
    $scope.display = {}
    $scope.filter = {}
    $scope.preset = {}
    $scope.dateRange = {}
    $scope.uTypes = {}
    $scope.defaultTZ = undefined
    $scope.fetchedConversations = []
    $scope.generating = true
    $scope.dateTz = undefined

    $scope.user_id = user_id
    $scope.role_id = role_id;
    $scope.user_services = JSON.parse(user_services);

     $("#service").autocomplete({
        source: function(request, response) {
            $.ajax({
                url: API_URL+"autocomplete/search_service",
                dataType: "json",
                data: {
                    term : request.term
                },
                success: function(data) {
                    response(data);
                   
                }
            });
        },
        min_length: 3,
       
    });

      $("#user").autocomplete({
        source: function(request, response) {
            $.ajax({
                url: API_URL+"autocomplete/search_user",
                dataType: "json",
                data: {
                    term : request.term
                },
                success: function(data) {
                    response(data);
                   
                }
            });
        },
        min_length: 3,
    });


  })

  $socket.on('connect', function () {
      console.log('socket successfully connected in historyController!');
      $scope.generating = true
      $scope.status = 'Connected';
      initialize();
  })

  $socket.on('disconnect', function () {
      $scope.generating = false
      $scope.status = 'Disonnected';
  })

  function initialize(){
    if($scope.isReady){
      $socket.emit('user_data', { id : $scope.user_id, services : $scope.user_services, role_id : $scope.role_id });
      initDatePicker();
   //   initStatus();
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

     defaultDate: moment().format('YYYY-MM-DD 00:00:00'),
      format: 'YYYY-MM-DD 00:00:00',
      useCurrent: false,

    });
    $('#datetimepicker7').datetimepicker({
      defaultDate: moment().format('YYYY-MM-DD 23:59:59'),
      format: 'YYYY-MM-DD 23:59:59',
      useCurrent: false //Important! See issue #1075
    });

    $("#datetimepicker6").on("dp.change", function (e) {
      $('#datetimepicker7').data("DateTimePicker").minDate(e.date);
      $scope.search.sdate = moment(e.date).format('YYYY-MM-DD 00:00:00')
    });

    $("#datetimepicker7").on("dp.change", function (e) {
      $('#datetimepicker6').data("DateTimePicker").maxDate(e.date);
      $scope.search.edate = moment(e.date).format('YYYY-MM-DD 23:59:59')
    });
  }

  function initStatus(){
$scope.search.status_filter  = $('#status_filter').data();
  }

  $socket.on('init_history', function(data){
    $scope.timezones = data.Dropdown.history_timezones
    $scope.status_filter = data.Dropdown.status_filters
    $scope.display = 'fetch_conversations'
    $scope.preset = data.Dropdown.report_date_ranges
    $scope.uTypes = data.Dropdown.user_type
    $scope.defaultTZ = data.default_tz_history
    $scope.search.timezone = data.default_tz_history
    $scope.search.sdate = $("#datetimepicker6").find("input").val();
    $scope.search.edate = $("#datetimepicker7").find("input").val();
    data.status =  $scope.initial_sdate

     $timeout(function(){
          $socket.emit('get_conversations', $scope.search)
      },1000)
   

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
      $('#datetimepicker6').data("DateTimePicker").clear()
      $('#datetimepicker7').data("DateTimePicker").clear()
      $('#datetimepicker6').data("DateTimePicker").date(moment($scope.dateRange[range].startDate)).disable()
      $('#datetimepicker7').data("DateTimePicker").date(moment($scope.dateRange[range].endDate)).disable()
    }
    else{
      $('#datetimepicker6').data("DateTimePicker").enable()
      $('#datetimepicker7').data("DateTimePicker").enable()
    }
  }

  $scope.timediff = function(start, end){
    var d1 = new Date(start);
    var d2 = new Date(end);
  return diffSeconds = (d2 - d1) / 1000;
  }

  $scope.setTimezone = function(tz){
    var str = tz;
    var format_tz = str.slice(0, str.indexOf("("));

    $socket.emit('get_tz_date_ranges', format_tz)
  }


  $scope.fetchConversations = function(){
    $scope.generating = true
    var regExp = /\(([^)]+)\)/;
    var matches = regExp.exec($scope.search.timezone);

    $scope.dateTz = matches[1]; 
    $socket.emit('get_conversations', $scope.search)
  }

  $scope.getConversationTraces = function(id){
      $http.get(API_URL+"history/get_traces/"+ id)
            .success(function(data){
             var logs = data.logs
              $scope.cdid = id
                $scope.Traces = JSON.parse(logs)
        });

  }

$scope.getConversationLogs = function(id, conversation_id){
      $http.get(API_URL+"history/get_logs/"+ id)
            .success(function(data){
              $scope.cdid = id
                $scope.logs = data

                getConversationComments(conversation_id)
        });

  }

function getConversationComments(conversation_id){
      $http.get(API_URL+"history/get_comments/"+ conversation_id)
            .success(function(data){
              $scope.conversation_id = conversation_id
                $scope.comments = data
        });

  }

$scope.addComment = function(conversation_id){
 $http({
            method: 'POST',
            url: url,
            data: $scope.user,
            transformRequest: transformRequestAsFormPost,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (response) {
             if(response == 0){
              toastr.success("User details has been saved.", "Successful!");
            }else{
              toastr.error("Please fill out necessary information.", "Error!");

            }
        }).error(function (response) {
            toastr.error("Please fill out necessary information.", "Error!");

        });
}
   $socket.on('history_data', function(data){
    $scope.generating = false
        if(data.result.length > 0){
       $scope.fetchedConversations = data
     }
     else{// clear scopes
       $scope.fetchedConversations = []
     }
   })

} // end of reportsController