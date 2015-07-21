"use strict";


var deposito = angular.module('deposito',['ui.bootstrap','ngFileUpload']);

deposito.config( function($interpolateProvider){

        $interpolateProvider.startSymbol('{#');
        $interpolateProvider.endSymbol('#}');
});

