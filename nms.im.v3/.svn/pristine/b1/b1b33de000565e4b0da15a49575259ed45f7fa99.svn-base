


angular
	.module('MainApp', ['headerModule','angular-toArrayFilter','angular-moment','luegg.directives','angularMoment','ngSanitize','ngMaterial', 'ngLetterAvatar'])

	.config(function ($socketProvider) {
		$socketProvider.setConnectionUrl('http://localhost:3000/chat')
	})
	.factory('focus',Focus)
	.factory('toArray',ToArray)
	.factory('transformRequestAsFormPost',TransformRequestAsFormPost)
	.controller('ChatController', ChatController)