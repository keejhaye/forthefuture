function userActivityController($scope, $http, API_URL, $document,transformRequestAsFormPost) {
 $document.ready(function($interval){

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

$http.get(API_URL + "user_activity/get_logs")
        .success(function (response) {
        $scope.logs = response;
        });


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
      $scope.sdate = moment(e.date).format('YYYY-MM-DD 00:00:00')
    });

    $("#datetimepicker7").on("dp.change", function (e) {
      $('#datetimepicker6').data("DateTimePicker").maxDate(e.date);
      $scope.edate = moment(e.date).format('YYYY-MM-DD 23:59:59')
    });

  })

  


      
}