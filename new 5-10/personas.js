function PersonasController($scope, $http, API_URL, transformRequestAsFormPost, $window) {
    
    // --changes 5/8/2017
    $scope.pageno = 0; 
    $scope.total_count = 0;
    $scope.itemsPerPage = 10;
    $scope.getData = function(pageno){
        $scope.personas = [];  

        if ($scope.personasName !== undefined) {
            let myURL = "http://localhost/nms.im.v3/public/panel/personas/get_paganated_personas/"+((pageno >= 1 ? pageno-1 : pageno)*10)+"/10/"+$scope.personasName;
             console.log("1->",myURL);
            $http.get(myURL)
                .success(function(response){ 
                   
                    $scope.personas = response;  
                });
        }else {
            console.log("2->",API_URL)
            $http.get("http://localhost/nms.im.v3/public/panel/personas/get_paganated_personas/"+((pageno >= 1 ? pageno-1 : pageno)*10)+"/10")
                .success(function(response){ 
                    $scope.personas = response;  
                    get_personas_count();
                });
        }
    };
    $scope.getData($scope.pageno);


    $scope.searchPersonas = function(searchKeys, searchCol){
        $scope.currentPage = 1;
        if (searchKeys.length > 0) {
            $http.get(API_URL + "personas/get_search_users/"+searchCol+"/"+searchKeys+"")
                .success(function(response){ 
                    $scope.is_no_search_result = true;
                    $scope.personas = response;  
                    $scope.total_count = response.length;
                });
        }else { 
            $scope.personasName = undefined;
            $http.get(API_URL + "personas/get_personas")
                .success(function(response){ 
                    $scope.personas = response;  
                    $scope.total_count = response.length;
                });
        }
    }

    function get_personas_count(){
        $http.get(API_URL + "personas/count_all_personas")
                .success(function (response) {
                    $scope.total_count = response;
                });
    }
    // --changes 5/8/2017

    function get_personas(){
         $http.get(API_URL + "personas/get_personas")
            .success(function (response) {
                $scope.personas = response;

            });
    }
    $scope.toggle = function (actiontype, id) {

        switch (actiontype) {
            case 'add':
                break;
            case 'edit':
                $scope.id = id;
                $http.get(API_URL + 'personas/get_personas/' + id)
                        .success(function (response) {
                            $scope.persona = response;
                        });
                break;
            default:
                break;
        }
        console.log(id);
    }

    function searchService(){
        $("#service").autocomplete({
        source: function(request, response) {
            $.ajax({
                url: API_URL+"autocomplete/search_service",
                dataType: "json",
                data: {
                    term : request.term
                },
                success: function(data) {
                    response(data);
                   
                }
            });
        },
        min_length: 3,
       
    });
    }
    

//save new record / update existing record
    $scope.save = function (id) {
        var url = API_URL + "personas/personas";
        if (id != undefined) {
               url = API_URL + "personas/update/" + id;
        }

        $http({
            method: 'POST',
            url: url,
            data: $scope.persona,
             transformRequest : transformRequestAsFormPost,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (response) {
             if(response == 0){
              toastr.success("Persona details has been saved.", "Successful!");
              get_personas()
            }else{
              toastr.error("Please fill out necessary information.", "Error!");

            }
        }).error(function (response) {
            console.log(response);
            alert('This is embarassing. An error has occured. Please check the log for details');
        });
    }
}