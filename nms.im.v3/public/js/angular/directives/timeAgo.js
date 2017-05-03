
app.directive('timeAgo', ['NotificationService',
function(NotificationService) {
    return {
        template: "<span class='pull-right date-sent'>"+
                        "{{ timeAgo }}"+
                        "<md-tooltip md-direction='top'>{{ message.outbound_time | format : 'lll' }}</md-tooltip> "+
                    "</span>",
        replace: true,
        link: function(scope, element, attrs) {
            var updateTime = function() {
                scope.timeAgo = moment(scope.$eval(attrs.timeAgo)).fromNow()
            }
            NotificationService.onTimeAgo(scope, updateTime) // subscribe
            updateTime()
        }
    }
}])