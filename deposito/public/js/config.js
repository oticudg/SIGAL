"use strict";


var deposito = angular.module('deposito',
	
	[	'ui.bootstrap',
		'ngFileUpload',
		'angularUtils.directives.dirPagination'
	]
);

deposito.config( function($interpolateProvider){

        $interpolateProvider.startSymbol('{#');
        $interpolateProvider.endSymbol('#}');
}).

filter('capitalize', function() {
    return function(input, all) {
      return (!!input) ? input.replace(/([^\W_]+[^\s-]*) */g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();}) : '';
    }
});


