var App = angular.module('DateTimePicker', []);

App.directive('dateTimePicker', function() {
     return {
            require: '?ngModel',
            restrict: 'A',
            scope: {},
            link: function (scope, elem, attrs, ngModel) {
            	$(elem).datetimepicker(scope.$eval(attrs.dateTimePicker));

                //Local event change
                elem.on('blur', function (event) {
                    scope.$apply(function(){
                    	ngModel.$setViewValue(event.target.value);
                    });
                })
            }
        };
});