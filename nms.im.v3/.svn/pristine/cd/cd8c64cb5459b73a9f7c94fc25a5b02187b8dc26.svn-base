function BatchController($scope, $http, API_URL, transformRequestAsFormPost) {
    var vm = this;


    /**
     * Tabs
    */

    vm.operators = {'selected':null, 'res':null}
    vm.searchUsers = searchUsers;
    vm.searchUsersOptions = [];
    vm.saveServiceUsers = saveServiceUsers;
    vm.getServiceUsers = getServiceUsers;
    vm.deleteAssignedUsers = deleteAssignedUsers;

    vm.getRules = getRules;
    vm.getAutoReminders = getAutoReminders;

    // Tab 1
    function searchUsers(keyword){
        $http.get(API_URL+"user_context/search", { params: {'keyword' : keyword}})
            .success(function(response){
                vm.searchUsersOptions = response.data;
        });
    }
     function searchService(keyword){
        $http.get(API_URL+"user_context/search_service", { params: {'keyword' : keyword}})
            .success(function(response){
                vm.searchServiceOptions = response.data;
        });
    }

    function saveServiceUsers(){
        if(vm.operators.selected != null){
            $http.post(API_URL+"user_context/save_service_users",
                {'users' : vm.operators.selected})
                .success(function(response){
                    vm.operators.res = response;
                    vm.getServiceUsers();
            });
        }
    }

    function getServiceUsers(){
        if(vm.frmInfo.id != null){
            $http.get(API_URL+"user_context/search_assigned").success(function(response){
                    vm.assignedUsers = response;
            });
        }
    }

    function deleteAssignedUsers(userServiceId){
        $http.post(API_URL+"user_context/delete", {'id' : userServiceId}).success(function(response){
            if(response == 1)
                getServiceUsers();
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