var ChatStatusController = function ChatStatusController($rootScope, $scope, $socket, $mdSidenav, $mdUtil, $q, $filter, $mdDialog, $document, $mdToast) 
{
	$scope.timeouts = {};
	$scope.logs = [];
	$scope.users = [];
	$scope.services = [];
	$scope.conversations = [];
	$scope.settings = {};
	$scope.leftOpen = true;
	$scope.rightOpen = true;
  $rootScope.keys = Object.keys

	$document.ready(function(){
		$scope.user_id = user_id
	});

    //SIDENAV
    $scope.toggleRight = buildToggler('right');

    function buildToggler(navID) {
      return function() {
        $mdSidenav(navID).toggle();
      };
    }

	$scope.count = {
		assigned : 0,
		pending : 0,
		onchat : 0
	};
	$scope.ids = {
		users : []
	};

    $scope.tableOptions = {
    	assigned : {
	        order: 'id',
	        limit: 7,
	        page: 1
    	},
    	pending : {
	        order: 'id',
	        limit: 7,
	        page: 1
    	},
    	users : {
	        order: 'id',
	        limit: 7,
	        page: 1
    	}
    };

    $scope.inbound = []
    $scope.outbound = []
    $scope.devRowLimit = 100

    $scope.saveSettings = function(name, value, ev){
	    // Appending dialog to document.body to cover sidenav in docs app
	    var confirm = $mdDialog.confirm()
	          .title('Confirm Edit Configuration')
	          .textContent('Are you sure you want to change configuration?')
	          .targetEvent(ev)
	          .ok('Yes!')
	          .cancel('No');

	    $mdDialog.show(confirm).then(function() {
	      $socket.emit('update_config', {name : name, value : value , user_id : $scope.user_id})
	    }, function() {

	    });
    };

    $socket.on('update_config_callback', function(data){
		$mdToast.show(
	      $mdToast.simple()
	        .textContent('Update Setting : ' + data.status)
	        .position('top left')
	        .hideDelay(3000)
	    );
	    $scope.settings[data.name].disabled = true
    })

	$socket.on('receive-services', function(services){
		$scope.services = services;
	});

	$socket.on('receive-users', function(users){
		$scope.users = users;
		$scope.ids.users = Object.keys($scope.users);
		$scope.count.onchat = Object.keys($scope.users).length;
	});

	$socket.on('receive-settings', function(settings){
		angular.forEach(settings, function(value,id){
			$scope.settings[id] = {value : value, disabled : true};
		});
	});

	$socket.on('receive-conversations', function(conversations){
		$scope.conversations = conversations;
		angular.forEach($scope.conversations, function(conversation){
			$scope.conversations[conversation.id].message_ids = Object.keys($scope.conversations[conversation.id].messages);
		});
		var arrayConversations = toArray($scope.conversations);
		$scope.count.assigned = $filter('filter')(arrayConversations, {status:'assigned'}).length;
		$scope.count.pending = $filter('filter')(arrayConversations, {status:'!assigned'}).length;
		// console.log(conversations);
	});
	$socket.on('receive-timeouts', function(timeouts){
		$scope.timeouts = timeouts;
	});

	$socket.on('update_users', function(user){
		if(user.property == 'all'){
			$scope.users[user.data.id] = user.data;
		}
		else{
			$scope.users[user.data.id][user.property] = user.data[user.property];
		}
		$scope.ids.users = Object.keys($scope.users);
	});

	$socket.on('update_services', function(service_data){
	    for(i in service_data.data){
	      $scope.services[i] = service_data.data[i]
	      console.log($scope.services[i])
	    }
	});

  	$socket.on('update_conversations', function(conversations){
	    conversations.data.message_ids = Object.keys(conversations.data.messages);
	    if(conversations.property == 'all'){
	      $scope.conversations[conversations.data.id] = conversations.data;
	    }
	    else{
	      $scope.conversations[conversations.data.id][conversations.property] = conversations.data[conversations.property];
	    }
	    var arrayConversations = toArray($scope.conversations);
	    $scope.count.assigned = $filter('filter')(arrayConversations, {status:'assigned'}).length;
	    $scope.count.pending = $filter('filter')(arrayConversations, {status:'!assigned'}).length;
  	});

	$socket.on('update_timeouts', function(timeouts){
    	console.log(timeouts)
	});

	$socket.on('delete_object', function(object){
		if(object.object != 'timeout'){
			if(object.object == 'users'){
				delete $scope[object.object][object.data];
				$scope.ids.users.splice($scope.ids.users.indexOf(object.data,1));
			}
			else if(object.object == 'conversations'){
				delete $scope[object.object][object.data];
				var arrayConversations = toArray($scope.conversations);
				$scope.count.assigned = $filter('filter')(arrayConversations, {status:'assigned'}).length;
				$scope.count.pending = $filter('filter')(arrayConversations, {status:'!assigned'}).length;
			}
			else if(object.object == 'service_queue'){
				delete $scope.services[object.data.sid].queue[object.data.cid]
			}
		}
	});

    function toArray(obj, addKey) {
        if (!angular.isObject(obj)) return obj; 
            if ( addKey === false ) {
              return Object.keys(obj).map(function(key) {
                return obj[key];
              });
            } else {
              return Object.keys(obj).map(function (key) {
                var value = obj[key];
                return angular.isObject(value) ?
                  Object.defineProperty(value, '$key', { enumerable: false, value: key}) :
                  { $key: key, $value: value };
              });
        }
    }

	$scope.isEmpty = function(obj){
		var keys = Object.keys(obj)
		return keys.length > 0 ? false : true
	}

	//FOR DEV STATUS
	$socket.on('inbound_requests', function(data){
		if($scope.inbound.length < $scope.devRowLimit)
			$scope.inbound.push(data)
		else{
			$scope.inbound.splice(0,1)
			$scope.inbound.push(data)
		}
	})

	$socket.on('outbound_requests', function(data){
		if($scope.outbound.length < $scope.devRowLimit)
			$scope.outbound.push(data)
		else{
			$scope.outbound.splice(0,1)
			$scope.outbound.push(data)
		}
	})
};