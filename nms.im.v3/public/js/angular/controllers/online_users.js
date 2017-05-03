function OnlineUsersController($scope, $http, API_URL, transformRequestAsFormPost) {
//retrieve conversation listing
 $scope.users = 0;
  setInterval(function(){
    $http.get(API_URL + "online/services")
            .success(function (response) {
                $scope.services = response;
            });
    }, 5000);
  
  setInterval(function(){
  $http.get(API_URL + "online/fetch")
            .success(function (response) {
     $scope.users = response;
       
            });
    }, 5000);

    $scope.kick = function (id) {
                $scope.id = id;
                $http.get(API_URL + 'online/kick/' + id)
                        .success(function (response) {
                        });
        console.log(id);
    }

}