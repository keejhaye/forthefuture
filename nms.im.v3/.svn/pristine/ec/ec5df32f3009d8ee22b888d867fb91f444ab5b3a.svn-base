function ProfileController($scope, $http, API_URL, transformRequestAsFormPost, $socket) {
    //retrieve employees listing from API
    $http.get(API_URL + "profile/user")
    .success(function (response) {
        $scope.user = response;
    });

    $http.get(API_URL + "profile/get_services")
    .success(function (response) {
        $scope.services = response;
    });

    $scope.presetOptions = [
        {val: "today", name: "Today"},
        {val: "yesterday", name: "Yesterday"},
        {val: "this_week", name: "This Week"},
        {val: "this_month", name: "This Month"},
        {val: "last_month", name: "Last Month"},
    ]

    $scope.selectedOption = {val: "today", name: "Today"}
    $scope.fetchingHstry = false
    $scope.hstry = []

    //save new record / update existing record
    $scope.save = function (id) {
            url = API_URL + "profile/update" ;
        
        $http({
            method: 'POST',
            url: url,
            data: $scope.user,
            transformRequest: transformRequestAsFormPost,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (response) {
            if(response == 0){
              toastr.success("Profile has been updated.", "Successful!");
            }else{
              toastr.error("Password did not match.", "Error!");

            }
        }).error(function (response) {
            console.log(response);
            alert('This is embarassing. An error has occured. Please check the log for details');
        });
    }

    $scope.getMessageHistory = function(preset){
        $scope.fetchingHstry = true
        var data = {uid: user_id, preset: preset.val}
        $socket.emit('profile_message_history', data)
    }

    $socket.on('profile_message_history_data', function(data){
        $scope.fetchingHstry = false
        $scope.hstry = data
    })

    $scope.exportUserMH = function(){
        var csvString = ''
        var filename = user_id + "_message_history_("+$scope.hstry.range.startDate+"-"+$scope.hstry.range.endDate+").csv"
        var headers = '"CDID","Service","Persona","Subscriber","Inbound","Outbound"'
        csvString = csvString + headers + "\n"

        for(var i=0; i<$scope.hstry.record.length;i++){
            var rowData = $scope.hstry.record[i]
            csvString = csvString +
                        '"' + rowData.conversation_duration_id + '",' + 
                        '"' + rowData.service_name + '",' + 
                        '"' + rowData.persona_name + '",' + 
                        '"' + rowData.subscriber_name + '",' +
                        '"' + rowData.last_inbound_message + '",' +
                        '"' + rowData.last_outbound_message + '",';

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
}