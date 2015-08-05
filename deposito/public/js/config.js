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
});

