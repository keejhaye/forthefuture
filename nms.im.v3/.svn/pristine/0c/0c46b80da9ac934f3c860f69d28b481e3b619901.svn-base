function FlaggedController($scope, $http, API_URL, transformRequestAsFormPost) {
    //retrieve employees listing from API
    var frm_defaults = {
        'active': '1',
        'group_id': ''
    };
    $scope.frmInfo = frm_defaults;
    $http.get(API_URL + "flagged_messages/get_flagged")
            .success(function (response) {
                $scope.flagged = response;
            });

    function get_flagged(){
        $http.get(API_URL + "flagged_messages/get_flagged")
            .success(function (response) {
                $scope.flagged = response;
            });
    }

    $scope.toggle = function (actiontype, id, conversation_id, message_id) {

        switch (actiontype) {
            case 'add':
                break;
            case 'edit':
                $scope.id = id;
                $http.get(API_URL + 'flagged_messages/get_flagged/' + id)
                        .success(function (response) {
                            console.log(response);
                            $scope.flag = response;
                            getHistoryLogs(conversation_id, message_id);
                            $scope.id = message_id;
                        });
                break;
            default:
                break;
        }
    }

    //save new record / update existing record
    $scope.save = function (id, action) {

        var url = API_URL + "users/add";

        //append employee id to the URL if the form is in edit mode
        if (id != undefined) {
            url = API_URL + "flagged_messages/update/" + id + '/' + action;
        }
        $http({
            method: 'POST',
            url: url,
            data: $scope.flag,
            transformRequest: transformRequestAsFormPost,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (response) {
             if(response == 0){
              toastr.success("Record updated.", "Successful!");
              get_flagged();
            }else{
              toastr.error("Please fill out necessary information.", "Error!");

            }
        }).error(function (response) {
            toastr.error("Please fill out necessary information.", "Error!");

        });
    }

     function getHistoryLogs(conversation_id, message_id){
      $http.get(API_URL+"flagged_messages/get_messages_history/"+ conversation_id +'/'+ message_id)
            .success(function(data){
                $scope.messages = data
        });

  }

}