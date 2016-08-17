<?php

use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('permissions')->insert([
          ['nombre' => 'consultar', 'modulo' => 'usuarios', 'ip' => 'users_consult'],
          ['nombre' => 'Registrar', 'modulo' => 'usuarios', 'ip' => 'users_register'],
          ['nombre' => 'Editar',    'modulo' => 'usuarios', 'ip' => 'users_edit'],
          ['nombre' => 'Eliminar',  'modulo' => 'usuarios', 'ip' => 'users_delete'],
          ['nombre' => 'consultar', 'modulo' => 'roles', 'ip' => 'roles_consult'],
          ['nombre' => 'Registrar', 'modulo' => 'roles', 'ip' => 'roles_register'],
          ['nombre' => 'Editar', 'modulo' => 'roles', 'ip' => 'roles_edit'],
          ['nombre' => 'Eliminar', 'modulo' => 'roles', 'ip' => 'roles_delete'],
          ['nombre' => 'consultar', 'modulo' => 'proveedores', 'ip' => 'providers_consult'],
          ['nombre' => 'Registrar', 'modulo' => 'proveedores', 'ip' => 'providers_register'],
          ['nombre' => 'Editar', 'modulo' => 'proveedores', 'ip' => 'providers_edit'],
          ['nombre' => 'Eliminar', 'modulo' => 'proveedores', 'ip' => 'providers_delete'],
          ['nombre' => 'consultar', 'modulo' => 'departamentos', 'ip' => 'departs_consult'],
          ['nombre' => 'Registrar', 'modulo' => 'departamentos', 'ip' => 'departs_register'],
          ['nombre' => 'Editar', 'modulo' => 'departamentos',    'ip' => 'departs_edit'],
          ['nombre' => 'Eliminar', 'modulo' => 'departamentos',  'ip' => 'departs_delete'],
          ['nombre' => 'consultar', 'modulo' => 'insumos', 'ip' => 'items_consult'],
          ['nombre' => 'Registrar', 'modulo' => 'insumos', 'ip' => 'items_register'],
          ['nombre' => 'Editar', 'modulo' => 'insumos',    'ip' => 'items_edit'],
          ['nombre' => 'Eliminar', 'modulo' => 'insumos',  'ip' => 'items_delete'],
          ['nombre' => 'consultar', 'modulo' => 'almacenes', 'ip' => 'stores_consult'],
          ['nombre' => 'Registrar', 'modulo' => 'almacenes', 'ip' => 'stores_register'],
          ['nombre' => 'Editar', 'modulo' => 'almacenes',    'ip' => 'stores_edit'],
          ['nombre' => 'Eliminar', 'modulo' => 'almacenes',  'ip' => 'stores_delete'],
          ['nombre' => 'Multiples almacenes', 'modulo' => 'almacenes',  'ip' => 'stores_multiple'],
          ['nombre' => 'consultar', 'modulo' => 'documentos', 'ip' => 'documents_consult'],
          ['nombre' => 'Registrar', 'modulo' => 'documentos', 'ip' => 'documents_register'],
          ['nombre' => 'Editar', 'modulo' => 'documentos',    'ip' => 'documents_edit'],
          ['nombre' => 'Eliminar', 'modulo' => 'documentos',  'ip' => 'documents_delete'],
          ['nombre' => 'Existencia', 'modulo' => 'inventario', 'ip' => 'inventory_stock'],
          ['nombre' => 'Kardex', 'modulo' => 'inventario', 'ip' => 'inventory_kardex'],
          ['nombre' => 'Movimientos', 'modulo' => 'inventario',    'ip' => 'inventory_movements'],
          ['nombre' => 'Reportes', 'modulo' => 'inventario',  'ip' => 'inventory_report'],
          ['nombre' => 'Alarmas', 'modulo' => 'inventario',  'ip' => 'inventory_alerts'],
          ['nombre' => 'Notificación fallas', 'modulo' => 'inventario',  'ip' => 'inventory_notification_alert'],
          ['nombre' => 'Registrar entradas',  'modulo' => 'movimientos', 'ip' => 'movements_register_entry'],
          ['nombre' => 'Registrar salidas',   'modulo' => 'movimientos', 'ip' => 'movements_register_egress']
        ]);
    }
}
