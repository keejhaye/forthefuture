angular.module('MainApp', ['ui.bootstrap','headerModule','angularUtils.directives.dirPagination', 'angucomplete-alt', 'ui.bootstrap.modal'])
  .config(function ($socketProvider) {
    $socketProvider.setConnectionUrl('http://localhost:3000/reports')
  })
  .constant('API_URL', 'http://localhost/imv3/public/panel/')
  .factory('transformRequestAsFormPost',TransformRequestAsFormPost)
  .controller('reportsController', reportsController)