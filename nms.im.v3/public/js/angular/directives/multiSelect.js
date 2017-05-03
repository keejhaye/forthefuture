var App = angular.module('Multiselect', []);

App.directive('multiSelectOption', function($timeout) {
    return {
    	// Restrict it to be an attribute in this case
        restrict: 'A',
        require: 'ngModel',
        scope: {
        	model: '=ngModel'
        },
        // responsible for registering DOM listeners as well as updating the DOM
        link: function(scope, element, attrs, ngModel) {	
			var options = scope.$eval(attrs.multiSelectOption);
			var selected = [];
			options.onChange = (function(option, checked) {
                if(checked){
                	selected.push(option.val());
                }else{
                	position = selected.indexOf(option.val());
                	selected.splice(position, 1);
                }
			});
			options.onDropdownHide = (function(event) {
				ngModel.$setViewValue(selected);
				ngModel.$render(); 
			});

       		$(element).multiselect(options);

       		var models = scope.$watch("model", function(){
       			console.log('changed');
       			$(element).multiselect('refresh');
       		});

			var unwatch = scope.$watch(
			    function () { return element[0].childNodes.length; },
			    function (newValue, oldValue) {
			      if (newValue !== oldValue) {
       				$(element).multiselect('rebuild');
       				unwatch();
			      }
			    }
			);

            // scope.$watch(attrs.ngModel, function () {
            	// console.log('testing');
       			// $(element).multiselect('refresh');
      		// });	
        }
    };
});