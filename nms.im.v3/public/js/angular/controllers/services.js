function ServicesController($scope, $http, API_URL, transformRequestAsFormPost, $filter) {
 
    var frm_defaults = {
        'active': '1'
    };
    var vm = this;

    // -- Kris' changes 5/9/2017
    $scope.pageno = 0; 
    $scope.total_count = 0;
    $scope.itemsPerPage = 10;
    $scope.getData = function(pageno){ 
        $scope.services = [];  
        $http.get(API_URL + "context/get_paganated_services/"+((pageno >= 1 ? pageno-1 : pageno)*10)+"/10").success(function(response){ 
            $scope.services = response;  
        });
    };
    $scope.getData($scope.pageno); 

    $http.get(API_URL + "context/count_all_services")
            .success(function (response) {
                $scope.total_count = response;
            });
    // -- Kris' changes 5/9/2017

    // $http.get(API_URL + "context/get_services")
    //         .success(function (response) {
    //             $scope.services = response;
    //         });

    function getServices(){
        $http.get(API_URL + "context/get_services")
            .success(function (response) {
                $scope.services = response;
            });
    }



    $scope.toggle = function (actiontype, id) {

        switch (actiontype) {
            case 'add':
                break;
            case 'edit':
                $scope.id = id;
                $http.get(API_URL + 'context/get_services/' + id)
                        .success(function (response) {
                            console.log(response);
                            $scope.service = response;
                            getLibraries(id);
                            getPersonas(id);
                            getRules(id);
                            getUserServices(id);
                            getCannedMessages(id);
                            getAutoReminders(id);
                        });
                break;
            default:
                break;
        }
        console.log(id);
    }
    
     $scope.resetForm = function (formName, clearForm, $holder) {
        $scope.service = angular.copy(frm_defaults);


        formName.$setPristine();
        $scope.service = angular.copy(frm_defaults);
        console.log('empty');
    }

    $scope.clearForm = function (formName){
        formName.$setPristine();
        $scope.canned = null;
        $scope.reminder = null;
        $scope.rules = null;
        $scope.rules = null;
        $scope.discardtime = null;
        console.log('alert');
    }

    //save new record / update existing record
    $scope.save = function (id) {
        var url = API_URL + "context/add";

        //append employee id to the URL if the form is in edit mode
        if (id != undefined) {
            url = API_URL + "context/update/" + id;
        }

        $http({
            method: 'POST',
            url: url,
            data: $scope.service,
            transformRequest: transformRequestAsFormPost,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (response) {

            if(response == 0){
              toastr.success("Service details has been saved.", "Successful!");
              getServices();
            }
        }).error(function (response) {
            
        });
    }


    /**
     * Tabs
    */

    // Tab 1
   
    vm.frmInfo = frm_defaults;
    vm.operator_service = {'selected':null, 'res':null}
    vm.userServices = {'current_page':1};
    vm.cannedMessages = {'current_page':1};
    vm.autoReminder = {'current_page':1};
    vm.responderRules = {'current_page':1};
    vm.singleRule = {'current_page':1};
    vm.serviceInfo = {'current_page':1};
    vm.autoDiscard = {'current_page':1};
    vm.libraries = [];
    vm.personas = [];
    vm.searchUsersOptions = [];

    // tab1 
 function getUserServices(id){
        $http.get(API_URL+"user_context/service_users/"+ id)
            .success(function(response){
                $scope.userServices = response;
        });
    }

    $scope.getUnassignedUsers = function(id){
        $http.get(API_URL+"user_context/unassigned_users/"+ id)
            .success(function(response){
                $scope.unassignedUsers = response;
        });
    }

    $scope.toggleAssigned = function(toggle) {
        angular.forEach($scope.userServices, function(itm){ itm.selected = toggle; });
        
        $scope.selectedList();
    }
    $scope.selectedList = function () {
        $scope.userSelected = [];
        angular.forEach($scope.userServices, function(users){
            if (!!users.selected) $scope.userSelected.push(users.user_id);
        })
    }

    $scope.deleteAssignedUsers = function (id) {
        var url = API_URL+"user_context/delete_assigned_users/"+ id;
        console.log($scope.userSelected)
        $http({
            method: 'POST',
            url: url,
            data: $scope.userSelected,
            transformRequest: transformRequestAsFormPost,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (response) {
            if($scope.userSelected.indexOf(response) !== -1){
              toastr.success("Users has been Deleted from Service.", "Successful!");
            }
            getUserServices (id);
        }).error(function (response) {
            
        });
    }

    $scope.toggleUnassigned = function(toggle) {
        angular.forEach($scope.visibleItems, function(itm){ itm.selected = toggle; });
        
        $scope.selectedListUnassigned();
    }
    $scope.selectedListUnassigned = function () {
        $scope.unuserSelected = [];
        angular.forEach($scope.visibleItems, function(unuser){
            if (!!unuser.selected) $scope.unuserSelected.push(unuser.id);
        })
    }

    $scope.saveServicesToUser = function(id){
        var url = API_URL+"user_context/assign_users/"+ id;
        
        $http({
            method: 'POST',
            url: url,
            data: {users : angular.toJson($scope.unuserSelected)},
            transformRequest: transformRequestAsFormPost,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (response) {
            console.log(response);
            if(response == 0){
              toastr.success("Users has been assigned to Service.", "Successful!");
            }
            getUserServices (id);
        }).error(function (response) {
            
        });
    }


    // Tab 2

    function getCannedMessages(id){
        $http.get(API_URL+"user_context/get_canned_messages/"+ id).success(function(response){
            $scope.cannedMessages = response;
        });   
    }


    $scope.deleteCannedMessage = function(msgId,id){
        $http.post(API_URL+"user_context/delete_canned_message", {'id' : msgId}).success(function(response){
            if(response == 1){
                getCannedMessages(id);
                toastr.success("Canned Message Deleted", "Successful!");
            }
        });
    }

    $scope.saveCannedMessage = function (id){
            var url = API_URL + "user_context/save_canned_message/" + id; 
            
            $http({
                method: 'POST',
                url: url,
                data: $scope.canned,
                transformRequest: transformRequestAsFormPost,
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).success(function (response) {
                if(response == 0){
                    toastr.success("Canned Messages Added", "Successful!");    
                    $scope.canned = null;
                    getCannedMessages(id);
                }
            }).error(function (response) {
                console.log('error');
            });
    }    

    // Tab 3

    function getAutoReminders(id){
        $http.get(API_URL+"user_context/get_auto_reminders/"+ id).success(function(response){
            $scope.autoReminder = response;
        });
    }

    function getLibraries(id){
        $http.get(API_URL+"user_context/get_libraries/" + id).success(function(response){
            $scope.libraries = response;
        });
    }

    $scope.deleteAutoReminder = function(reminderId,id){
        $http.post(API_URL+"user_context/delete_auto_reminder", {'id' : reminderId}).success(function(response){
            if(response == 1){
                getAutoReminders(id);
                toastr.success("Auto Reminder Deleted", "Successful!");
            }
        });
    }

    $scope.saveAutoReminder = function (id){
        var url = API_URL + "user_context/save_auto_reminder/" + id; 
        
        var reminder = angular.copy($scope.reminder);
        reminder.schedule = $filter('date')(reminder.schedule, "yyyy-MM-dd");

        $http({
            method: 'POST',
            url: url,
            data: reminder,
            transformRequest: transformRequestAsFormPost,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (response) {
            if(response == 0){
                toastr.success("Reminder Added", "Successful!");    
                $scope.reminder = null;
                getAutoReminders(id);
            }
        }).error(function (response) {
            console.log('error');
        });
    }

    // Tab 4
    
    function getRules(id){
        $http.get(API_URL+"user_context/get_rules/" + id ).success(function(response){
            $scope.responderRules = response;
        });
    }
    $scope.getRule = function(id, ruleId){
        $http.get(API_URL+"user_context/get_rule_by_id/" + ruleId).success(function(response){
            $scope.singleRule = response;
            console.log(response);
        });
    }
    
    function getPersonas(id){
        $http.get(API_URL+"user_context/get_personas/" + id).success(function(response){
            $scope.personas = response;
        });
    }

    $scope.saveRule = function (id){
        var url = API_URL + "user_context/save_rule/" + id; 
        var rules = angular.copy($scope.rules);
        rules.library = rules.library.id;
        
        $http({
            method: 'POST',
            url: url,
            data: $scope.rules,
            transformRequest: transformRequestAsFormPost,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (response) {
            if(response == 0){
              toastr.success("Rule Added", "Successful!");    
            }
            $scope.rules = null;
            getRules(id);
        }).error(function (response) {
            console.log('error');
        });
    }

    $scope.editRule = function (id, ruleId){
        var url = API_URL + "user_context/edit_rule/" + id + "/" + ruleId; 
        
        $http({
            method: 'POST',
            url: url,
            data: $scope.singleRule,
            transformRequest: transformRequestAsFormPost,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (response) {
            if(response == 0){
                toastr.success("Rule Updated", "Successful!");    
            }
            getRules(id);
        }).error(function (response) {
            console.log('error');
        });
    }

    $scope.deleteRule = function(ruleId,id){
        $http.post(API_URL+"user_context/delete_rule", {'id' : ruleId}).success(function(response){
            if(response == 1){
                getRules(id);
                toastr.success("Rule Deleted", "Successful!");
            }
        });
    }

    // Tabs 4   

    $scope.saveRoutes = function (id){
        var url = API_URL + "user_context/save_route/" + id; 
        $http({
            method: 'POST',
            url: url,
            data: {'route':$scope.service.route, 'mapping':$scope.service.mapping},
            transformRequest: transformRequestAsFormPost,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (response) {
            if(response == 0){
              toastr.success("Route Saved", "Successful!");    
            }
            serviceInfo(id);
        }).error(function (response) {
            console.log('error');
        });
    }

    //tab5    
    $scope.saveMessageLimit = function (id){
        var url = API_URL + "user_context/save_message_limit/" + id;
        $http({
            method: 'POST',
            url: url,
            data: {'limit':$scope.service.message_limit, 'action':$scope.service.message_limit_action, 'reset':$scope.service.message_limit_reset_period},
            transformRequest: transformRequestAsFormPost,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (response) {
            if(response == 0){
              toastr.success("Message Limit Saved", "Successful!");    
            }
            getMessageLimit(id);
        }).error(function (response) {
            console.log('error');
        });
    }

    $scope.savePersonaLimit = function (id){
        var url = API_URL + "user_context/save_persona_limit/" + id;
        $http({
            method: 'POST',
            url: url,
            data: {'limit':$scope.service.persona_limit, 'action':$scope.service.persona_limit_action, 'reset':$scope.service.persona_limit_reset_period},
            transformRequest: transformRequestAsFormPost,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (response) {
            if(response == 0){
              toastr.success("Persona Limit Saved", "Successful!");    
            }
            getMessageLimit(id);
        }).error(function (response) {
            console.log('error');
        });
    }

    $scope.saveSubscriberBilling = function (id){
        var url = API_URL + "user_context/save_subscriber_billing/" + id;
        $http({
            method: 'POST',
            url: url,
            data: {'enable':$scope.service.enable_subscriber_billing, 'idle':$scope.service.subscriber_billing_idle_time},
            transformRequest: transformRequestAsFormPost,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (response) {
            if(response == 0){
              toastr.success("Subscriber Billing Saved", "Successful!");    
            }
            getMessageLimit(id);
        }).error(function (response) {
            console.log('error');
        });
    }
    
    function getAutoDiscard(id){
        $http.get(API_URL+"user_context/get_auto_discard/" + id).success(function(response){
            $scope.autoDiscard = response;
        });
    }

    $scope.deleteAutoDiscard = function(limitId,id){
        $http.post(API_URL+"user_context/delete_auto_discard", {'id' : limitId}).success(function(response){
            if(response == 1){
                getAutoDiscard(id);
                toastr.success("Auto Discard Deleted", "Successful!");
            }
        });
    }

    $scope.saveAutoDiscard = function (id){
        var url = API_URL + "user_context/save_auto_discard/" + id;
        $http({
            method: 'POST',
            url: url,
            data: $scope.discardtime,
            transformRequest: transformRequestAsFormPost,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (response) {
            if(response == 0){
              toastr.success("Auto Discard Saved", "Successful!");    
            }
            $scope.discardtime = null;
            getAutoDiscard(id);
        }).error(function (response) {
            console.log('error');
        });
    }


    /**
     * End of Tabs
    */
}