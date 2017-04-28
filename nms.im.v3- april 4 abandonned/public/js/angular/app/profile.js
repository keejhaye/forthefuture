angular.module('MainApp', ['headerModule','angularUtils.directives.dirPagination','angularMoment'])
        .constant('API_URL', 'http://localhost/nms.im.v3/public/panel/')
        .factory('transformRequestAsFormPost',TransformRequestAsFormPost)
        .controller('profileController', ProfileController)