angular
  .module('Outbound', ['socket.io','ngMaterial', 'angularMoment'])

  .config(function ($socketProvider) {
      $socketProvider.setConnectionUrl('http://localhost:3001/outbound_mock')
  })

  .config(function($interpolateProvider) {
      $interpolateProvider.startSymbol('<%')
      $interpolateProvider.endSymbol('%>')
  })

  .controller('OutboundController', ['$scope', '$socket', '$document', function ($scope, $socket, $document){
    $document.ready(function(){
      $scope.user_id = user_id
      $scope.services = {}
      $scope.conversations = {}
      console.log("Outbound Mock Document is ready")

      $socket.on('connect', function(){
        console.log('Outbound Mock Socket Connected')
      })

      $socket.on('services', function(services){
        console.log(services)
        $scope.services = services
      })

      $socket.on('conversations', function(conversations){
        $scope.conversations = conversations
      })
    });
  }]);