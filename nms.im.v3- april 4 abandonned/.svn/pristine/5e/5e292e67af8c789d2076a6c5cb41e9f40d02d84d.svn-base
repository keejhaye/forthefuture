var ChatAdminController = function ChatAdminController($scope, $socket) 
{
	$scope.timeouts = {}
	$scope.logs = []
	$scope.users = []
	$scope.services = []
	$scope.conversations = []

	$socket.on('receive-services', function(services){
		$scope.services = services;
		console.log('services')
		// console.log(services)
	})

	$socket.on('receive-users', function(users){
		$scope.users = users;
		console.log('users')
		// console.log(users)
	})

	$socket.on('receive-conversations', function(conversations){
		$scope.conversations = conversations;
		console.log('conversations')
		// console.log(conversations)
	})

	$socket.on('receive-timeouts', function(timeouts){
		$scope.timeouts = timeouts
		console.log('timeouts')
		// console.log(timeouts)
	})

}