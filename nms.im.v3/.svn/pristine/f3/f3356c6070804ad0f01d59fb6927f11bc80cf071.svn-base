function BulletinController($scope, $http, API_URL, transformRequestAsFormPost, $filter) {
    
    var frm_defaults = {
        'active': '1'
    };
    var vm = this;

    $http.get(API_URL + "bulletin/get_bulletin")
            .success(function (response) {
                $scope.bulletins = response;
                getServices();
                getUsers();
            $scope.loadModal = true; 
            });   

    function getBulletin () {
        $http.get(API_URL + "bulletin/get_bulletin")
        .success(function (response) {
            $scope.bulletins = response;
        });   
    }
        
    $scope.resetForm = function (formName, clearForm, $holder) {
        $scope.bulletinInfo = angular.copy(frm_defaults);


        formName.$setPristine();
        $scope.bulletinInfo = angular.copy(frm_defaults);
        console.log('empty');
    }



    $scope.toggle = function (actiontype, id) {
        switch (actiontype) {
            case 'add':
                break;
            case 'edit':
                $scope.id = id;
                $http.get(API_URL + 'bulletin/get_bulletin_info/' + id)
                    .success(function (response) {
                        $scope.bulletinInfo = response;
                        getBulletinServices (id);
                        getBulletinUsers (id);
                    });
                break;
            default:
                break;
        }
        console.log(id);
    }
    $scope.save = function (id) {
        var bulletinInfomation = angular.copy($scope.bulletinInfo);
        
        var recipients_list = [];
        angular.forEach(bulletinInfomation.recipients, function(value, key) {
            recipients_list.push(parseInt(value.id));
        });

        var services_list = [];
        angular.forEach(bulletinInfomation.services, function(value, key) {
            services_list.push(parseInt(value.id));
        });
        bulletinInfomation.recipients = recipients_list; 
        bulletinInfomation.services = services_list;

        var url = API_URL + "bulletin/add";

        // append employee id to the URL if the form is in edit mode
        if (id != undefined) {
            url = API_URL + "bulletin/update/" + id;
        }

        $http({
            method: 'POST',
            url: url,
            data: bulletinInfomation,
            transformRequest: transformRequestAsFormPost,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (response) {
            if(response == 0){
              toastr.success("Service details has been saved.", "Successful!");
            }
            // getBulletin ();
            // getBulletinInformation (response)
            console.log(response);
        }).error(function (response) {
        
            // getBulletin ();
        });
    }

    vm.frmInfo = frm_defaults;
    vm.services = {'current_page':1};
    vm.userlist = {'current_page':1};
    vm.loadModal = false;
    
    function getBulletinInformation (id) {
        $http.get(API_URL + 'bulletin/get_bulletin_info/' + id)
        .success(function (response) {
            $scope.bulletinInfo = response;
            getBulletinServices (id);
            getBulletinUsers (id);
        });
    }
    
    function getBulletinServices (id) {
        $http.get(API_URL + "bulletin/get_bulletin_info_services/"+ id)
        .success(function (response) {
            $scope.bulletinInfo.services = response;
        });
    }    

    function getBulletinUsers (id) {
        $http.get(API_URL + "bulletin/get_bulletin_info_users/"+ id)
        .success(function (response) {
            $scope.bulletinInfo.recipients = response;
        });
    }    

    function getServices () {
        $http.get(API_URL + "bulletin/get_services")
        .success(function (response) {
            $scope.services = response;
        });
    }

    function getUsers () {
        $http.get(API_URL + "bulletin/get_users")
        .success(function (response) {
            $scope.userlist = response;
        });
    }

    $scope.searchBulletin = function() {
        var url = API_URL + "bulletin/search_bulletin";
        $http({
                method: 'POST',
                url: url,
                data: $scope.keyword,
                transformRequest: transformRequestAsFormPost,
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (response) {
            $scope.bulletins = response;    
        })
    }


}