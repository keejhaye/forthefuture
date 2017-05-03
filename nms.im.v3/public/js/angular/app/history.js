angular.module('MainApp', ['headerModule','angularUtils.directives.dirPagination','ngSanitize','angularMoment'])
  .config(function ($socketProvider) {
    $socketProvider.setConnectionUrl('http://localhost:3000/history')
  })
  .constant('API_URL', 'http://localhost/nms.im.v3/public/panel/')
  .factory('transformRequestAsFormPost',TransformRequestAsFormPost)
  .controller('historyController', historyController)
  .filter('songTime',function(){
 return function(seconds) {
     var days = Math.floor(seconds / 86400);
var hours = Math.floor((seconds % 86400) / 3600);
var minutes = Math.floor(((seconds % 86400) % 3600) / 60);
var numseconds = ((seconds % 86400) % 3600) % 60;

var timeString = '';
    if (days !== 0) {
        timeString += (days !== 1) ? (days + ' days ') : (days + ' day ');
    }
    if (hours !== 0) {
        timeString += (hours !== 1) ? (hours + ' hours ') : (hours + ' hour ');
    }
    if (minutes !== 0) {
        timeString += (minutes !== 1) ? (minutes + ' minutes ') : (minutes + ' minute ');
    }
    if (numseconds !== 0) {
        timeString += (numseconds !== 1) ? (numseconds + ' seconds ') : (numseconds + ' second ');
    }
return timeString;
}
})
.filter('timeAgo',function (){
   return function(time){
      return moment(time).fromNow();
    }

    this.$stateful = true;
    return fromNowFilter;
  });