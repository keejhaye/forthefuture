function SubscribersController($scope, $http, API_URL, transformRequestAsFormPost) {
//retrieve employees listing from API

    // -- Kris' changes 5/9/2017
    $scope.pageno = 0; 
    $scope.total_count = 0;
    $scope.itemsPerPage = 10;
    $scope.getData = function(pageno){ 
        $scope.subscribers = [];  
        $http.get(API_URL + "subscribers/get_paganated_subscribers/"+((pageno >= 1 ? pageno-1 : pageno)*10)+"/10").success(function(response){ 
            $scope.subscribers = response;  
        });
    };
    $scope.getData($scope.pageno); 

    $http.get(API_URL + "subscribers/count_all_subscribers")
            .success(function (response) {
                $scope.total_count = response;
            });
    // -- Kris' changes 5/9/2017

    // $http.get(API_URL + "subscribers/get_subscribers")
    //         .success(function (response) {
    //             $scope.subscribers = response;
    //         });

    function get_subscribers(){
         $http.get(API_URL + "subscribers/get_subscribers")
            .success(function (response) {
                $scope.subscribers = response;
            });
    }
    $scope.toggle = function (actiontype, id) {

        switch (actiontype) {
            case 'add':
                break;
            case 'edit':
                $scope.id = id;
                $http.get(API_URL + 'subscribers/get_subscribers/' + id)
                        .success(function (response) {
             
                            $scope.subscriber = response;
                            getHistory(id);
                        });
                break;
            default:
                break;
        }
        console.log(id);
    }

//save new record / update existing record
    $scope.save = function (modalstate, id) {
        var url = API_URL + "subscribers/get_subscribers";
        //append employee id to the URL if the form is in edit mode
        if (id != undefined) {
             url = API_URL + "subcribers/update/" + id;
        }

        $http({
            method: 'POST',
            url: url,
            data: $scope.subscriber,
            transformRequest: transformRequestAsFormPost,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (response) {
            console.log(response);
            location.reload();
        }).error(function (response) {
            console.log(response);
            alert('This is embarassing. An error has occured. Please check the log for details');
        });
    }

    function getHistory(id){
        $http.get(API_URL + "subscribers/get_conversation_history/" + id)
            .success(function (response) {
                $scope.conversations = response;
            });
    }

    $scope.getSubscriberConversationLogs = function(id){
      $http.get(API_URL+"subscribers/get_messages/"+ id)
            .success(function(data){

                $scope.messages = data
        });

  }

  $scope.addToBlacklist = function(id){
      $http.get(API_URL+"subscribers/add_to_blacklist/"+ id)
            .success(function(data){
                if(data == 0){
               toastr.success("Subscriber added to blacklist.", "Successful!");  
               get_subscribers();   
                }
               
        });

  }
}