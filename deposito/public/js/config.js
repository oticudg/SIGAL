"use strict";


var deposito = angular.module('deposito',
	
	[	'ui.bootstrap',
		'angularUtils.directives.dirPagination',
		'ngSanitize', 
		'ui.select',
    'directive.loading'
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
}).

filter('propsFilter', function() {
  return function(items, props) {
    var out = [];

    if (angular.isArray(items)) {
      var keys = Object.keys(props);
        
      items.forEach(function(item) {
        var itemMatches = false;

        for (var i = 0; i < keys.length; i++) {
          var prop = keys[i];
          var text = props[prop].toLowerCase();
          if (item[prop].toString().toLowerCase().indexOf(text) !== -1) {
            itemMatches = true;
            break;
          }
        }

        if (itemMatches) {
          out.push(item);
        }
      });
    } else {
      // Let the output be the input untouched
      out = items;
    }

    return out;
  };
});

angular.module('directive.loading', [])

    .directive('loading',   ['$http' ,function ($http)
    {
      return {
          restrict: 'A',
          link: function (scope, elm, attrs)
          {
              scope.isLoading = function () {
                
                if(scope.searchAjax == true)
                  return false;          
                else
                  return $http.pendingRequests.length > 0;

              };

              scope.$watch(scope.isLoading, function (v)
              {
                  if(v){
                      elm.show();
                  }else{
                      elm.hide();
                  }
              });
          }
      };
}]);

$(document).ready(function () {
var trigger = $('.hamburger'),
    overlay = $('.overlay'),
   isClosed = false;

  trigger.click(function () {
    hamburger_cross();      
  });

  function hamburger_cross() {

    if (isClosed == true) {          
      overlay.hide();
      trigger.removeClass('is-open');
      trigger.addClass('is-closed');
      isClosed = false;
    } else {   
      overlay.show();
      trigger.removeClass('is-closed');
      trigger.addClass('is-open');
      isClosed = true;
    }
}
  
$('[data-toggle="offcanvas"]').click(function () {
    $('#wrapper').toggleClass('toggled');
  });  
});



