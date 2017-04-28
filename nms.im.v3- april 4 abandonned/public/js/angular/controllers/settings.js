function SettingsController($scope, $http, API_URL, transformRequestAsFormPost) {
    //retrieve employees listing from API
    $http.get(API_URL + "settings/maximum_conversation")
            .success(function (response) {
                $scope.conversation = response;
            });

    $http.get(API_URL + "settings/unmap_time_from_operator")
    .success(function (response) {
        $scope.timeout = response;
    });

    $scope.saveSettings = function(name, value){
        if(name == 'max_conversation'){
            var url = API_URL + "settings/set_max_conversation";
        }else{
             var url = API_URL + "settings/set_unmap_time";
        }
        $http({
            method: 'POST',
            url: url,
            data: {'value':value},
            transformRequest: transformRequestAsFormPost,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (response) {
            if(response == 0){
              toastr.success("Settings Saved", "Successful!");    
            }
        }).error(function (response) {
            console.log('error');
        });
    }


    //save new record / update existing record
}