
angular
	.module('headerModule', ['socket.io','ui.bootstrap','ngSanitize','ngMaterial'])
	.constant('API_URL', 'http://localhost/nms.im.v3/public/panel/')
	.config(function ($socketProvider) {

	    if(role_id == 6){
	    	$socketProvider.setConnectionUrl('http://localhost:3000/statistics_client')
	    }
	    else{
	    	$socketProvider.setConnectionUrl('http://localhost:3000/statistics')
	    }

	    $socketProvider.setConnectTimeout(15000)
	    $socketProvider.setReconnect(true)
	    $socketProvider.setReconnectionDelay(1000)
	    $socketProvider.setMaxReconnectionAttempts(500)
	})
	.config(function($interpolateProvider) {
	    $interpolateProvider.startSymbol('<%')
	    $interpolateProvider.endSymbol('%>')
	})
	.factory('Statistics', Statistics)
	.controller('HeaderController', HeaderController)