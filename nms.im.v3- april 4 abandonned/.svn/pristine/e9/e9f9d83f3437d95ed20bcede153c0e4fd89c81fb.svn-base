angular.module('angular-moment', [])

.filter('moment', function () {
  return function (input, momentFn /*, param1, param2, ...param n */) {
    var args = Array.prototype.slice.call(arguments, 2),
        momentObj = moment(input)
    return momentObj[momentFn].apply(momentObj, args)
  }
})

.filter('format', function () {
  return function (input, momentFn /*, param1, param2, ...param n */) {
    var args = Array.prototype.slice.call(arguments, 2)

    return moment(input).format(arguments[1])
  }
})

.filter('ago', function () {
  return function (input /*, param1, param2, ...param n */) {
    return moment(input).fromNow()
  }
})