<!DOCTYPE html>
<html lang="en" ng-app="deposito">
<head>
	<meta charset="UTF-8">
	<title>Deposito</title>
	<link rel="stylesheet" href="{{asset('css/vendor/bootstrap.min.css')}}">
	<link rel="stylesheet" href="{{asset('css/vendor/bootstrap-theme.min.css')}}">
</head>
<body @yield('bodytag')>
	
	<div class="container">
		@yield('conten')
	</div>
	<script src="{{asset('js/vendor/angular.min.js')}}"></script>
	<script src="{{asset('js/vendor/ui-bootstrap-tpls-0.13.0.min.js')}}"></script>
	<script src="{{asset('js/vendor/ng-file-upload.js')}}"></script>
	<script src="{{asset('js/config.js')}}" type="text/javascript"></script>
	@yield('addscript')
	
</body>
</html>