

function HeaderController($scope, Statistics, $document, $socket, $window, $timeout, $interval, $mdDialog, $mdToast) {
	$scope.statistics = Statistics;
	$scope.status = 'Disconnected';
    $scope.isReady = false;

    $document.ready(function(){
        $scope.isReady = true;
        $scope.role_id = role_id;
        $scope.user_services = JSON.parse(user_services);
        $scope.user_id = user_id;
  		$window.document.getElementById('pre-load').style.display = 'none';
    })

    $socket.on('connect', function() {
        $scope.status = 'Connected'
        console.log('socket successfully connected!');
        initialize();
    })

    $socket.on('disconnect', function() {
        $scope.status = 'Disconnected'
        console.log('socket is disconnected!');
    })

    $socket.on('force_disconnection', function(message){
        var dialog = $mdDialog.alert()
                    .htmlContent(message)
                    .ariaLabel('force-disconnection')
                    .escapeToClose(false)
        
        var template = angular.element(dialog._options.template)
        angular.element(template).find('md-dialog-actions').remove()
        dialog._options.template = template.html()
        $mdDialog.show(dialog)
    })

    function initialize(){
        if($scope.isReady){
            $socket.emit('user_connect', { id : $scope.user_id, services : $scope.user_services, role_id : $scope.role_id, currentPathname : window.location.pathname });
            // $interval(bulletin_notification, 5000);
        }
        else{
            console.log('document not yet ready. restarting operation..');
            $timeout(function(){
                initialize();
            },1000)
        }
    }

	$socket.on('initial_statistics', function(data){
		console.log('initial_statistics')
        $scope.statistics['online_count'] = (data[0] == null ? 0 : Number(data[0])) 
        $scope.statistics['assigned_conversations'] = (data[1] == null ? 0 : Number(data[1])) 
        $scope.statistics['unassigned_conversations'] = (data[2] == null ? 0 : Number(data[2])) 
        $scope.statistics['total_inbound'] = (data[3] == null ? 0 : Number(data[3])) 
        $scope.statistics['total_outbound'] = (data[4] == null ? 0 : Number(data[4]))

        user_stat = JSON.parse(data[5])
        $scope.statistics['outbound_count'] = user_stat.outbound_count 
        $scope.statistics['chat_time'] = user_stat.chat_time
	})

	$socket.on('initial_statistics_per_service', function(data){
		console.log('initial_statistics_per_service')
		console.log(data)
		$scope.statistics['online_count'] = (data[0] == null ? 0 : Number(data[0])) 
		$scope.statistics['assigned_conversations'] += (data[1] == null ? 0 : Number(data[1])) 
		$scope.statistics['unassigned_conversations'] += (data[2] == null ? 0 : Number(data[2])) 
		$scope.statistics['total_inbound'] += (data[3] == null ? 0 : Number(data[3])) 
		$scope.statistics['total_outbound'] += (data[4] == null ? 0 : Number(data[4]))

        user_stat = JSON.parse(data[5])
        $scope.statistics['outbound_count'] = user_stat.outbound_count 
        $scope.statistics['chat_time'] = user_stat.chat_time
	})

    $socket.on('update_statistics', function(data){
        if(Number($scope.role_id) != 6){
            $scope.statistics['online_count'] = (data[0] == null ? 0 : Number(data[0])) 
            $scope.statistics['assigned_conversations'] = (data[1] == null ? 0 : Number(data[1])) 
            $scope.statistics['unassigned_conversations'] = (data[2] == null ? 0 : Number(data[2])) 
            $scope.statistics['total_inbound'] = (data[3] == null ? 0 : Number(data[3])) 
            $scope.statistics['total_outbound'] = (data[4] == null ? 0 : Number(data[4])) 
        }
    })

    $socket.on('update_client_statistics', function(data){
        if(Number($scope.role_id) == 6){
            if(data.type == "user_stat"){
                $scope.statistics['outbound_count'] = data.total.outbound_count 
                $scope.statistics['chat_time'] = data.total.chat_time
            }
            else{
                $scope.statistics[data.type] = (data.total == null ? 0 : data.total) 
            }
        }
    })

    $socket.on('update_user_stats', function(data){
        data = JSON.parse(data)
        $scope.statistics['outbound_count'] = data.outbound_count
        $scope.statistics['chat_time'] = data.chat_time
    })

    $socket.on('reload_page', function(){
        location.reload()
    })

	$socket.on('new_service_notif', function(message){
        $mdToast.show( 
            $mdToast.simple()
                .toastClass('toast-service-notif')
                .textContent(message)
                .position('top right')
                .hideDelay(60000)
            );
	})
}
