function UsersController($scope, $http, API_URL, transformRequestAsFormPost, $document) {
    //retrieve employees listing from API
    var frm_defaults = {
        'active': '1',
        'group_id': ''
    };
   
    $scope.frmInfo = frm_defaults;
$http.get(API_URL + "users/get_users")
            .success(function (response) {
                $scope.users = response;
            });
    function get_users(){
         $http.get(API_URL + "users/get_users")
            .success(function (response) {
                $scope.users = response;
                 $scope.tabs = response;
            });
    }
   
    $scope.resetForm = function (formName, clearForm) {
        $scope.user = angular.copy(frm_defaults);
         $scope.id = null;

        formName.$setPristine();
        $scope.user = angular.copy(frm_defaults);
        console.log('empty');


    }
    $scope.toggle = function (actiontype, id) {

        switch (actiontype) {
            case 'add':
                break;
            case 'edit':
                $scope.id = id;
                $http.get(API_URL + 'users/get_users/' + id)
                        .success(function (response) {
                            console.log(response);
                            $scope.user = response;
                            getUserServices(id)
                        });
                break;
            default:
                break;
        }
        console.log(id);
    }

    //save new record / update existing record
    $scope.save = function (id) {
        var url = API_URL + "users/add";

        //append employee id to the URL if the form is in edit mode
        if (id != undefined) {
            url = API_URL + "users/update/" + id;
        }
        $http({
            method: 'POST',
            url: url,
            data: $scope.user,
            transformRequest: transformRequestAsFormPost,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (response) {
             if(response.status == 0){
              toastr.success("User details has been saved.", "Successful!");
              get_users();
              getUserServices(response.id);
              $scope.id = response.id;
              $scope.showServices = true;
            }else{
              toastr.error(response, "Error!");

            }
        }).error(function (response) {
            toastr.error("Please fill out necessary information.", "Error!");

        });
    }
    /**
     * Tabs
    */
    
    // Tab 1
    // function searchUsers(keyword){
    //     $http.get(base_url+"/panel/user_context/search", { params: {'keyword' : keyword, 'service_id': vm.frmInfo.id}})
    //         .success(function(response){
    //             this.searchUsersOptions = response.data;
    //     });
    // }

    // function saveServiceUsers(){
    //     if(this.operators.selected != null){
    //         $http.post(base_url+"/panel/user_context/save_service_users",
    //             {'users' : this.operators.selected, 'service_id': this.frmInfo.id})
    //             .success(function(response){
    //                 this.operators.res = response;
    //                 this.getServiceUsers();
    //         });
    //     }
    // }

    // function getServiceUsers(){
    //     if(this.frmInfo.id != null){
    //         $http.get(API_URL+"/panel/user_context/search_assigned",
    //             { params: {
    //                     'service_id' : this.frmInfo.id,
    //                     'page' : this.assignedUsers.current_page
    //                 }
    //             }).success(function(response){
    //                 this.assignedUsers = response;
    //         });
    //     }
    // }

    // function deleteAssignedUsers(userServiceId){
    //     $http.post(base_url+"/panel/user_context/delete", {'id' : userServiceId}).success(function(response){
    //         if(response == 1)
    //             getServiceUsers();
    //     });
    // }
    
    function getUserServices(userid){
        $http.get(API_URL+"user_context/user_services/"+ userid)
            .success(function(response){
                $scope.userServices = response;
        });
    }

    $scope.getUnassignedServices = function(userid){
        $http.get(API_URL+"user_context/unassigned_services/"+ userid)
            .success(function(response){
                $scope.unassignedServices = response;
        });
    }

    $scope.toggleAssigned = function(toggle) {
        angular.forEach($scope.userServices, function(itm){ itm.selected = toggle; });
        
        $scope.selectedList();
    }
    $scope.selectedList = function () {
        $scope.serviceSelected = [];
        angular.forEach($scope.userServices, function(services){
            if (!!services.selected) $scope.serviceSelected.push(services.service_id);
        })
    }

    $scope.deleteAssignedServices = function (id) {
        var url = API_URL+"user_context/delete_assigned_services/"+ id;
        
        $http({
            method: 'POST',
            url: url,
            data: $scope.serviceSelected,
            transformRequest: transformRequestAsFormPost,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (response) {
            if($scope.serviceSelected.indexOf(response) !== -1){
              toastr.success("User Services has been Deleted.", "Successful!");
            }
            getUserServices (id);
        }).error(function (response) {
            
        });
    }

    $scope.toggleUnassigned = function(toggle) {
        angular.forEach($scope.visibleServices, function(itm){ itm.selected = toggle; });
        
        $scope.selectedListUnassigned();
    }
    $scope.selectedListUnassigned = function () {
        $scope.unserviceSelected = [];
        angular.forEach($scope.visibleServices, function(unservices){
            if (!!unservices.selected) $scope.unserviceSelected.push(unservices.id);
        })
    }

    $scope.saveServicesToUser = function(id){
        var url = API_URL+"user_context/assign_services/"+ id;
        
        $http({
            method: 'POST',
            url: url,
            data: {services : angular.toJson($scope.unserviceSelected)},
            transformRequest: transformRequestAsFormPost,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (response) {
            console.log(response);
            if(response == 0){
              toastr.success("Services has been assigned to user.", "Successful!");
            }
            getUserServices (id);
        }).error(function (response) {
            
        });
    }

    // Tab 2
    function getRules(){
        console.log('rules');
    }

    function getAutoReminders(){
        console.log('getAutoReminders');
    }

    /**
     * End of Tabs
    */

}