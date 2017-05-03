var app = angular.module('Records', ['headerModule','angularUtils.directives.dirPagination'])
        .constant('API_URL', 'http://localhost/nms.im.v3/public/panel/')