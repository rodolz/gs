<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

	//rutas exclusivamente para usuarios logeados
	Route::group(['middleware' => 'auth'], function(){

		//Perfil de usuario
		Route::get('/perfil','UserController@perfil');
		Route::post('/perfil','UserController@update_avatar');

		//Registro
		Route::group(['middleware' => ['permission:register-newusers']], function(){
			Route::get('register','Auth\RegisterController@index');
		});

		Route::get('/',[
			'as' => 'index',
			'uses' =>'DashboardController@dashboard'
			]
		);

		// RUTAS DE PRODUCTOS
		Route::group(['middleware' => ['permission:manage-productos']], function(){
			Route::get('productos','ProductoCRUDController@index')->name('productos.index');
			Route::get('productos/datatables','ProductoCRUDController@datatables');
			Route::get('productos/postdata','ProductoCRUDController@postdata');
			Route::post('check_inventario', 'ProductoCRUDController@check_inventario');
			Route::post('check_precio', 'ProductoCRUDController@check_precio');
			Route::get('productos/inventario_pdf', 'ProductoCRUDController@inventario_pdf');
			Route::get('productos/inventario',[
				'as' => 'productos.inventario',
				'uses' => 'ProductoCRUDController@inventario'
			]);
			Route::group(['middleware' => ['permission:editar-producto']], function(){
				Route::resource('productos','ProductoCRUDController', ['except' => [
					    'index',
				]]);
				Route::post('productos/update','ProductoCRUDController@actualizar_producto');
			});
		});	

		// RUTAS DE CLIENTES
		Route::group(['middleware' => ['permission:manage-clientes']], function(){
			Route::get('clientes','ClienteCRUDController@index')->name('clientes.index');
			Route::group(['middleware' => ['permission:editar-cliente']], function(){
				Route::resource('clientes', 'ClienteCRUDController', ['except' => [
					    'index',
					]]);
			});
		});

		// RUTAS CATEGORIAS
		Route::group(['middleware' => ['permission:manage-categorias']], function(){
			Route::get('categorias','CategoriaController@index')->name('categorias.index');
			Route::group(['middleware' => ['permission:editar-categoria']], function(){
				Route::resource('categorias','CategoriaController', ['except' => [
					    'index',
				]]);
			});
		});

		// RUTAS DE ORDENES
		Route::group(['middleware' => ['permission:manage-ordenes']], function(){
			Route::get('ordenes','OrdenesController@index')->name('ordenes.index');
                        Route::get('orden-pdf/{idOrden}', 'OrdenesController@pdf');
			Route::group(['middleware' => ['permission:editar-orden']], function(){
				Route::resource('ordenes','OrdenesController', ['except' => [
				    'index',
				]]);
				Route::post('nueva_orden', 'OrdenesController@nueva_orden');
				Route::post('update_orden', 'OrdenesController@update_orden');
				Route::get('/ordenes/edit_from_cotizacion/{idCotizacion}', [
					'as' => 'ordenes.create_from_cotizacion',
					'uses' => 'OrdenesController@nueva_orden_cotizacion'
				]
			);
			});
		});

		// RUTAS DE COMISIONES
		Route::group(['middleware' => ['permission:manage-comisiones']], function(){
			Route::get('comisiones','ComisionesController@index')->name('comisiones.index');
			Route::get('comision-pdf/{idComision}', 'ComisionesController@pdf');
			Route::group(['middleware' => ['permission:editar-comision']], function(){
				Route::resource('comisiones','ComisionesController', ['except' => [
				    'index',
				]]);
				Route::post('nueva_comision', 'ComisionesController@nueva_comision');
				Route::post('comisiones_repartidor', 'ComisionesController@comisiones_repartidor');
			});
		});

		// RUTAS DE FACTURAS
		Route::group(['middleware' => ['permission:manage-facturas']], function(){
			Route::get('facturas','FacturasController@index')->name('facturas.index');
			Route::group(['middleware' => ['permission:editar-factura']], function(){
				Route::resource('facturas','FacturasController', ['except' => [
					    'index',
				]]);
				Route::post('nueva_factura', 'FacturasController@nueva_factura');
				Route::get('factura-pdf/{idFactura}/{idOrden}', 'FacturasController@pdf');
				Route::get('factura-pdf/{idFactura}', 'FacturasController@pdf');
				Route::get('facturas/create-by-id/{idFactura}', 'FacturasController@create_by_id');
				Route::post('facturas/update/num_fiscal','FacturasController@actualizar_num_fiscal');
			});
		});

		// RUTAS DE VENTAS
		Route::group(['middleware' => ['permission:manage-ventas']], function(){
			Route::get('cotizacion-pdf/{idCotizacion}', 'CotizacionController@nueva_cotizacion_pdf');
			Route::post('ventas/lista_precios_pdf', [
				'as' => 'ventas.lista_precios_pdf',
				'uses' => 'VentasController@lista_precios_pdf'
				]);
			Route::get('ventas/lista_precios_dompdf', 'VentasController@lista_precios_dompdf');
			Route::get('ventas/lista_precios','VentasController@lista_precios')->name('ventas.index');
			Route::get('ventas/cotizaciones','CotizacionController@index')->name('ventas.cotizaciones.index');
			Route::group(['middleware' => ['permission:editar-venta']], function(){
				Route::resource('ventas/cotizaciones','CotizacionController', ['except' => [
					    'index',
				]]);
				Route::post('ventas/nueva_cotizacion', 'CotizacionController@nueva_cotizacion');
				Route::resource('ventas/lista_precios', 'VentasController@lista_precios', ['except' => [
					    'index',
				]]);
			});
			
		});

		//RUTAS DE pagos
		// INDEX DE PAGOS
		Route::group(['middleware' => ['permission:manage-pagos']], function(){
			Route::get('pagos','PagosController@index')->name('pagos.index');
			Route::get('pagos/postdata','PagosController@postdata');
			Route::post('check_monto', 'PagosController@check_monto');
			Route::get('pagos/estado_cuenta_pdf/{id}', 'PagosController@estado_cuenta_pdf');

			Route::get('pagos/cuentas_por_cobrar', 'PagosController@cuentas_por_cobrar_index');
			Route::post('pagos/cuentas_por_cobrar/resumen','PagosController@cuentas_por_cobrar');
			Route::group(['middleware' => ['permission:editar-pago']], function(){
				Route::get('pagos/nuevo_pago_index',[
					'as' => 'pagos.nuevo_pago_index',
					'uses' => 'PagosController@nuevo_pago_index'
					
				]);

				Route::post('pagos/nuevo_pago','PagosController@nuevo_pago');
				Route::post('pagos/nuevo_pago/{id}', [
				    'as' => 'pagos.nuevo_pago',
				    'uses' => 'PagosController@nuevo_pago_resumen'
				]);
				Route::post('pagos/guardar_pago','PagosController@guardar_pago');
				Route::resource('pagos', 'PagosController', ['except' => [
					    'index',
				]]);
			});
		});

		//Estadisticas
		Route::group(['middleware' => ['permission:manage-metricas']], function(){
			Route::get('stats/','StatsController@index');
			Route::post('stats/resumen','StatsController@estadisticas');
		});

});