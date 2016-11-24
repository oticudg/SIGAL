<!DOCTYPE html>
<html lang="en" ng-app="deposito">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>SIGAL | Dashboard</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="{{asset('css/vendor/bootstrap.min.css')}}">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{asset('css/vendor/font-awesome.min.css')}}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="{{asset('css/vendor/ionicons.min.css')}}">
  <link rel="stylesheet" href="{{asset('css/vendor/select.min.css')}}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{elixir('css/app.css')}}">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body class="hold-transition skin-blue sidebar-mini"  @yield('bodytag')>
<div class="wrapper">

  @include('layouts.navs.top-nav')
  @include('layouts.navs.side-menu')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        @yield('panel-name')
      </h1>
      <ol class="breadcrumb">
        @yield('breadcrumb')
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
     @yield('content') 
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 2.1.0
    </div>
    <strong>Copyleft &copy; 2015-2016 <a href="http://sahum.gob.ve">Servicio Autónomo Hospital Universitario de Maracaibo</a><strong> 



  </footer>


  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>

</div>
<!-- ./wrapper -->

<!-- jQuery 2.2.3 -->
<script src="{{asset('plugins/jQuery/jquery-2.2.3.min.js')}}"></script>
<script src="{{asset('js/vendor/angular.min.js')}}"></script>
<script src="{{asset('js/vendor/ui-bootstrap-tpls-0.13.0.min.js')}}"></script>
<script src="{{asset('js/vendor/dirPagination.js')}}"></script>
<script src="{{asset('js/vendor/angular-sanitize.min.js')}}"></script>
<script src="{{asset('js/vendor/select.min.js')}}"></script>
<!-- Bootstrap 3.3.6 -->
<script src="{{asset('js/vendor/bootstrap.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{asset('dist/js/app.min.js')}}"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="{{asset('dist/js/pages/dashboard2.js')}}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{asset('dist/js/demo.js')}}"></script>
<script src="{{elixir('js/config.js')}}"></script>
<script src="{{elixir('js/deposito.js')}}"></script>

@yield('addscript')


</body>
</html>
