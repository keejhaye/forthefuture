
app.directive('windowResize', function($window, $rootScope) {
      return function($scope) {
        $scope.initializeWindowSize = function() {
          chatBoxWidth = window.innerWidth > 959 ? (window.innerWidth * 80 / 100) : (window.innerWidth)
          newChatBoxLimit = Math.floor((chatBoxWidth - 82) / 294)

          if($scope.chatBoxLimit < newChatBoxLimit)
          {
              if($scope.subscribers[$scope.activeSubscriber].hiddenChatBoxesCount > 0)
              {
                   keys = Object.keys($scope.subscribers[$scope.activeSubscriber].chatBoxes)
                   finished = false
                   counter = keys.length - 1
                   while(!finished && counter > -1)
                   {
                      if($scope.subscribers[$scope.activeSubscriber].chatBoxes[keys[counter]] != undefined && $scope.subscribers[$scope.activeSubscriber].chatBoxes[keys[counter]].status == 'hidden')
                      {
                          $scope.subscribers[$scope.activeSubscriber].chatBoxes[keys[counter]].status = 'visible'
                          $scope.subscribers[$scope.activeSubscriber].hiddenChatBoxesCount -= 1
                          delete $scope.subscribers[$scope.activeSubscriber].hiddenChatBoxKeys[keys[counter]]
                          $scope.subscribers[$scope.activeSubscriber].visibleChatBoxesCount += 1

                          if($scope.subscribers[$scope.activeSubscriber].visibleChatBoxesCount >= newChatBoxLimit)
                              finished = true
                      }
                      counter--
                   }
              }
          }
          else if($scope.chatBoxLimit > newChatBoxLimit)
          {
              if($scope.subscribers[$scope.activeSubscriber] != undefined && $scope.subscribers[$scope.activeSubscriber].visibleChatBoxesCount > 0)
              {
                   keys = Object.keys($scope.subscribers[$scope.activeSubscriber].chatBoxes)
                   finished = false
                   counter = keys.length - 1

                   while(!finished && counter > -1)
                   {
                      if($scope.subscribers[$scope.activeSubscriber].chatBoxes[keys[counter]] != undefined && $scope.subscribers[$scope.activeSubscriber].chatBoxes[keys[counter]].status == 'visible')
                      {
                          $scope.subscribers[$scope.activeSubscriber].chatBoxes[keys[counter]].status = 'hidden'
                          $scope.subscribers[$scope.activeSubscriber].hiddenChatBoxesCount += 1
                          $scope.subscribers[$scope.activeSubscriber].hiddenChatBoxKeys[keys[counter]] = $scope.subscribers[$scope.activeSubscriber].chatBoxes[keys[counter]]
                          $scope.subscribers[$scope.activeSubscriber].visibleChatBoxesCount -= 1

                          if($scope.subscribers[$scope.activeSubscriber].visibleChatBoxesCount == newChatBoxLimit)
                              finished = true
                      }
                      counter--
                   }
              }
          }
          $scope.chatBoxLimit = newChatBoxLimit 
        }
        angular.element($window).bind("resize", function() {
          $scope.initializeWindowSize()
          $scope.$apply()
        })
        //$scope.initializeWindowSize()
      }
})
