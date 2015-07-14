"use strict";


var deposito = angular.module('deposito',['ui.bootstrap']);

deposito.config( function($interpolateProvider){

        $interpolateProvider.startSymbol('{#');
        $interpolateProvider.endSymbol('#}');
});

