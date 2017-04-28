angular.module('MainApp', ['headerModule','angularUtils.directives.dirPagination','colorpicker','ui.select', 'ngSanitize'])
        .constant('API_URL', 'http://localhost/nms.im.v3/public/panel/')
        .factory('transformRequestAsFormPost',TransformRequestAsFormPost)
        .controller('servicesController', ServicesController)