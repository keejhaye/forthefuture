function BlacklistController($scope, $http, API_URL, transformRequestAsFormPost) {
    $http.get(API_URL + "blacklist/subscribers")
            .success(function (response) {
                $scope.subscribers = response;
            });


    function get_blacklisted(){
        $http.get(API_URL + "blacklist/subscribers")
            .success(function (response) {
                $scope.subscribers = response;
            });
    }

    $scope.toggle = function (actiontype, id) {

        switch (actiontype) {
            case 'add':
                break;
            case 'edit':
                $http.get(API_URL + 'blacklist/subscribers/' + id)
                        .success(function (response) {
                            $scope.subscriber = response;
                        });
                break;
            default:
                break;
        }
    }

    $scope.confirmDelete = function(id){
          $http.get(API_URL + 'blacklist/remove/' + id)
                        .success(function (response) {
                            if(response == 0){
                             toastr.success("Subscriber has been removed from blacklist.", "Successful!");
                             get_blacklisted();
                            }
                        });
    }
    
    
}