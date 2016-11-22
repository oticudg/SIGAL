<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar">
    <!-- search form -->
    <form action="#" method="get" class="sidebar-form">
      <div class="input-group">
        <input type="text" name="q" class="form-control" placeholder="Search...">
            <span class="input-group-btn">
              <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
              </button>
            </span>
      </div>
    </form>
    <!-- /.search form -->
    <!-- sidebar menu: : style can be found in sidebar.less -->
    <ul class="sidebar-menu">
      <li class="header">Manú de navegacion</li>
      
      <li><a href="documentation/index.html"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li> 

      {{-- Menu de administracion --}}
      @if( Auth::user()->hasPermissions(['users_consult', 'stores_consult', 'documents_consult', 'roles_consult', 'departs_consult', 'providers_consult', 'items_consult']) )
        <li class="treeview">
          <a href="#">
            <i class="glyphicon glyphicon-cog"></i> <span>Administración</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            @if( Auth::user()->hasPermissions(['users_consult']))
              <li><a href="/usuarios"><i class="glyphicon glyphicon-user"></i> Usuario</a></li>
            @endif

            @if( Auth::user()->hasPermissions(['stores_consult']) )
              <li><a href="{{route('depoInicio')}}"><i class="glyphicon glyphicon-inbox"></i> Almacenes</a></li></li>
            @endif

            @if(Auth::user()->hasPermissions(['documents_consult']))
              <li><a href="{{route('documIndex')}}"><i class="glyphicon glyphicon-folder-close"></i> Documentos</a></li></li>
            @endif

            @if(Auth::user()->hasPermissions(['roles_consult']))
              <li><a href="{{route('rolesIndex')}}"><i class="glyphicon glyphicon-compressed"></i> Roles</a></li></li>
            @endif

            @if( Auth::user()->hasPermissions(['departs_consult']) )
              <li><a href="/departamentos"><i class="glyphicon glyphicon-briefcase"></i> Departamentos</a></li>
            @endif

            @if( Auth::user()->hasPermissions(['providers_consult']) )
              <li><a href="/proveedores"><i class="glyphicon glyphicon-folder-open"></i> Proveedores</a></li>
            @endif

            @if( Auth::user()->hasPermissions(['items_consult']) )
              <li><a href="/insumos"><i class="glyphicon glyphicon-th"></i> Insumos</a></li>
            @endif

          </ul>
        </li>
      @endif
  
    {{-- Menu de inventario --}}
      @if( Auth::user()->hasPermissions(['inventory_stock', 'inventory_movements', 'inventory_alerts']) )
        <li class="treeview">
          <a href="#">
            <i class="glyphicon glyphicon-th-list"></i>
            <span>Inventario</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            @if( Auth::user()->hasPermissions(['inventory_stock']) )
              <li><a href="{{route('invenInicio')}}"><i class="glyphicon glyphicon-equalizer"></i> Existencia</a></li>
            @endif

            @if( Auth::user()->hasPermissions(['inventory_movements']) )
              <li><a href="{{route('entrPanel')}}"><i class="glyphicon glyphicon-circle-arrow-down"></i> Entradas</a></li>
            @endif

            @if( Auth::user()->hasPermissions(['inventory_movements']) )
              <li><a href="/salidas"><i class="glyphicon glyphicon-circle-arrow-up"></i> Salidas</a></li>
            @endif

            @if( Auth::user()->hasPermissions(['inventory_alerts']) )
              <li><a href="{{route('invenAlertsInicio')}}"><i class="glyphicon glyphicon-bell"></i> Alarmas</a></li>
            @endif

            @if( Auth::user()->hasPermissions(['inventory_modifications']) )
              <li><a href="{{route('invenModifInicio')}}"><i class="glyphicon glyphicon-edit"></i> Modificaciones</a></li>
            @endif

            <li><a href="/estadisticas"><i class="glyphicon glyphicon-tasks"></i> 
              Estadisticas</a></li>
          </ul>
        </li>
      @endif

      {{-- Menu de transferencias --}}
      @if( Auth::user()->hasPermissions(['movements_register_entry', 'movements_register_egress']) )
        <li class="treeview">
          <a href="#">
            <i class="glyphicon glyphicon-transfer"></i>
            <span>Tranferencias</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            @if( Auth::user()->hasPermissions(['movements_register_entry']) )
              <li><a href="{{route('entrRegistrar')}}"><i class="glyphicon glyphicon-circle-arrow-down"></i> Registro de Entrada</a></li>
            @endif

            @if( Auth::user()->hasPermissions(['movements_register_egress']) )
              <li><a href="/registrarSalida"><i class="glyphicon glyphicon-circle-arrow-up"></i> Registro de Salida</a></li>
            @endif 
          </ul>
        </li>
      @endif
    </ul>
  </section>
  <!-- /.sidebar -->
</aside>