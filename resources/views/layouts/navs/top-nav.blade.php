<header class="main-header">

  <!-- Logo -->
  <a href="/" class="logo">
    <!-- mini logo for sidebar mini 50x50 pixels -->
    <span class="logo-mini"><b>SIG</b></span>
    <!-- logo for regular state and mobile devices -->
    <span class="logo-lg"><b>SIGAL</b></span>
  </a>

  <!-- Header Navbar: style can be found in header.less -->
  <nav class="navbar navbar-static-top" ng-controller="menuController">
    <!-- Sidebar toggle button-->
    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
      <span class="sr-only">Toggle navigation</span>
    </a>
    <!-- Navbar Right Menu -->
    <div class="navbar-custom-menu">
      <ul class="nav navbar-nav">
      <li class="active"><a><strong>{{Auth::user()->getDepositoName()}}</strong></a></li>
      @if( Auth::user()->hasPermissions(['inventory_notification_alert']) && ( $var = App\Repositories\AlertsRepository::alert() ) > 0 )
        <li class="dropdown notifications-menu">
			<a href="{{route('inven::herra::niveles')}}" data-toggle="tooltip" data-placement="bottom" title="Notificaciones" class="dropdown-toggle">
            <i class="fa fa-bell-o"></i>
            <span class="label label-warning">{{$var}}</span>
          </a>
        </li>  
      @endif


        <!-- User Account: style can be found in dropdown.less -->
        <li class="dropdown user user-menu">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <i class="fa fa-user"></i>
            <span class="hidden-xs">{{ ucwords(Auth::user()->nombre." ".Auth::user()->apellido)}}</span>
          </a>
            <ul class="dropdown-menu">
              <li class="user-body">
                @if( Auth::user()->hasPermissions(['stores_multiple']))
                  <a href="#" ng-click="deposito()">
                    <i class="glyphicon glyphicon-inbox text-aqua"></i> Cambiar almacén
                  </a>
                @endif 
                <div class="divider"></div>
                <a href="#" ng-click="password()">
                  <i class="glyphicon glyphicon-lock text-aqua"></i> Cambiar contraseña
                </a>
                <div class="divider"></div>
                <a href="/auth/logout">
                  <i class="glyphicon glyphicon-log-out text-aqua"></i> Salir
                </a>
              </li>
            </ul>
        </li>
      </ul>
    </div>

  </nav>
</header>