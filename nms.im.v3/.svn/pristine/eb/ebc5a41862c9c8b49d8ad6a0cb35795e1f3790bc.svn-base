angular
	.module('StatusApp', ['angular-toArrayFilter','socket.io','ngMaterial', 'angularMoment','md.data.table','angularResizable','ngMessages'])

	.config(function ($socketProvider) {
	    //$socketProvider.setConnectionUrl('http://anne.nmsloop.com:8888')
	    // $socketProvider.setConnectionUrl('http://192.168.1.53:3000/chat_status')
	    $socketProvider.setConnectionUrl('http://localhost:3000/chat_status')
	    $socketProvider.setConnectTimeout(30000)
	    $socketProvider.setReconnect(true)
	    $socketProvider.setReconnectionDelay(1000)
	    $socketProvider.setMaxReconnectionAttempts(5)
	})

	.config(function($interpolateProvider) {
	    $interpolateProvider.startSymbol('<%')
	    $interpolateProvider.endSymbol('%>')
	})
	.controller('ChatStatusController', ChatStatusController)
