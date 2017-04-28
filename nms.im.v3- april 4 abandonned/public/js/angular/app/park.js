angular.module('MainApp', ['headerModule','timer'])
    .config(function ($socketProvider) {
        if(role_id == 6){
            $socketProvider.setConnectionUrl('http://localhost:3000/statistics_client')
        }
        else{
            $socketProvider.setConnectionUrl('http://localhost:3000/statistics')
        }
    })
    .controller('TimerCtrl', ['$scope', '$socket', function ($scope, $socket) {
        $socket.on('connect', function(){
            $socket.emit('unassign_conversation_on_park', user_id)
        })

        $scope.timerRunning = false;

        $scope.startTimer = function () {
            $scope.$broadcast('timer-start');
            $scope.timerRunning = true;
        };

        $scope.stopTimer = function () {
            $scope.$broadcast('timer-stop');
            $scope.timerRunning = false;
        };
        
    }]);

var timerModule = angular.module('timer', [])
    .directive('timer', ['$compile', function ($compile) {
    return {
        restrict: 'EA',
        replace: false,
        scope: {
            interval: '=',
            autoStart: '&'
        },
        controller: ['$scope', '$element', '$attrs', '$timeout', function ($scope, $element, $attrs, $timeout) {

            $scope.millis = 0;
            $scope.autoStart = $attrs.autoStart || $attrs.autostart;
            
            $element.append($compile($element.contents())($scope));

            $scope.startTime = null;
            $scope.endTime = null;
            $scope.timeoutId = null;
            $scope.isRunning = false;

            $scope.$on('timer-start', function () {
                $scope.start();
            });

            $scope.$on('timer-stop', function () {
                $scope.stop();
            });

            $scope.$on('timer-clear', function () {
                $scope.clear();
            });

            function resetTimeout() {
                if ($scope.timeoutId) {
                    clearTimeout($scope.timeoutId);
                }
            }

            $scope.start = function () {
                $scope.startTime = new Date();
                resetTimeout();
                tick();
                $scope.isRunning = true;
            };
            
            $scope.stop = function () {
                var timeoutId = $scope.timeoutId;
                $scope.clear();
                $scope.$emit('timer-stopped', {
                    timeoutId: timeoutId,
                    millis: $scope.millis,
                    seconds: $scope.seconds,
                    minutes: $scope.minutes,
                    hours: $scope.hours
                });
            };

            $scope.clear = function () {
                // same as stop but without the event being triggered
                $scope.stoppedTime = new Date();
                resetTimeout();
                $scope.timeoutId = null;
                $scope.isRunning = false;
            };

            $element.bind('$destroy', function () {
                resetTimeout();
                $scope.isRunning = false;
            });

            function calculateTimeUnits() {
               
                // compute time values based on maxTimeUnit specification
                
                    $scope.seconds = Math.floor(($scope.millis / 1000) % 60);
                    $scope.minutes = Math.floor((($scope.millis / (60000)) % 60));
                    $scope.hours = Math.floor((($scope.millis / (3600000)) % 24));
                    $scope.days = Math.floor((($scope.millis / (3600000)) / 24) % 30);
                
                //add leading zero if number is smaller than 10
                $scope.sseconds = $scope.seconds < 10 ? '0' + $scope.seconds : $scope.seconds;
                $scope.mminutes = $scope.minutes < 10 ? '0' + $scope.minutes : $scope.minutes;
                $scope.hhours = $scope.hours < 10 ? '0' + $scope.hours : $scope.hours;
                $scope.ddays = $scope.days < 10 ? '0' + $scope.days : $scope.days;

            }

            
            calculateTimeUnits();

            var tick = function () {

                $scope.millis = new Date() - $scope.startTime;
                var adjustment = $scope.millis % 1000;

                if ($scope.millis < 0) {
                    $scope.stop();
                    $scope.millis = 0;
                    calculateTimeUnits();
                    if ($scope.finishCallback) {
                        $scope.$eval($scope.finishCallback);
                    }
                    return;
                }
                calculateTimeUnits();

                //We are not using $timeout for a reason. Please read here - https://github.com/siddii/angular-timer/pull/5
                $scope.timeoutId = setTimeout(function () {
                    tick();
                    $scope.$digest();
                }, $scope.interval - adjustment);

                $scope.$emit('timer-tick', {
                    timeoutId: $scope.timeoutId,
                    millis: $scope.millis
                });
                
            };

            if ($scope.autoStart) {
                $scope.start();
                console.log("I am started");
            }
        }]
    };
}]);

/* commonjs package manager support (eg componentjs) */
if (typeof module !== "undefined" && typeof exports !== "undefined" && module.exports === exports) {
    module.exports = timerModule;
}