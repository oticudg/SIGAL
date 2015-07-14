<!DOCTYPE html>
<html lang="en" ng-app="deposito">
<head>
	<meta charset="UTF-8">
	<title>Deposito</title>
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">

	<!-- Optional theme -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
</head>
<body @yield('bodytag')>
	
	<div class="container">
		@yield('conten')
	</div>
	<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.2/angular.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/angular-ui-bootstrap/0.13.0/ui-bootstrap-tpls.min.js"></script>
	<script src="{{asset('js/config.js')}}" type="text/javascript"></script>
	@yield('addscript')
	
</body>
</html>