<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Cliente;
use App\Orden;
use App\Factura;
use App\Pago;
use App\Producto;
use App\User;
use Charts;

class StatsController extends Controller
{
    public function index(Request $request){
    	$clientes = Cliente::pluck('empresa','id');
    	return view('ventas.stats.index', compact('clientes'));
    }

    public function estadisticas(Request $request){

    $cliente = Cliente::findOrfail($request->idCliente);
    $empresa = $cliente->empresa;
    $ordenes = Orden::where('idCliente', $cliente->id)->get();

    $cant_ordenes = Charts::database($ordenes,'bar', 'highcharts')
    		->title("Cantidad de Notas de Entrega Mensual")
		    ->elementLabel("Totales")
		    ->legend(false)
		    ->width(0)
		    ->responsive(true)
		    ->lastByMonth(12,true);

	//Sum total de revenue de las ordenes agrupadas en  Year,Month
	$data = Factura::select('facturas.created_at', DB::raw('sum(facturas.monto_factura) as aggregate'))
			->where('idCliente', $cliente->id)
			->groupBy(DB::raw('Year(facturas.created_at), Month(facturas.created_at)'))
			->get();

	$rev_orden = Charts::database($data,'line','highcharts')
			->title("Monto Total de Facturas Mensual")
			->elementLabel("Total ($)")
			->preaggregated(true)
			->legend(false)
			->lastByMonth(12,true);


	$results = DB::table('ordens')
				->select('ordenes_productos.idProducto as producto')
				->selectRaw('SUM(ordenes_productos.cantidad_producto) as cant_producto')
				->join('ordenes_productos', 'ordens.id', '=', 'ordenes_productos.idOrden')
				->where('ordens.idCliente', '=', $cliente->id)
				->groupBy('ordenes_productos.idProducto')
				->get();

	// Filtar los productos que ya no existen en la DB
	$filtered = $results->filter(function ($value, $key) {
	    if(Producto::where('id','=', $value->producto)->count() > 0){
	    	//Sustituir el id por el nombre del producto
	    	$producto = Producto::find($value->producto);
	    	$value->producto = $producto->codigo;
	    	$value->categoria = $producto->categoria->nombre_categoria;
	    	return $value;
	    }
	});

	$filtered2 = $filtered->groupBy('categoria');
	$total_cant = 0;
	$array = [];
	$collection = collect();
	$i = 0;
	foreach ($filtered2 as $key => $value) {
		foreach ($value as $producto) {
			$total_cant = $total_cant + $producto->cant_producto;
		}
		$array2 = ['categoria' => $key, 'cantidad_total' => $total_cant];
		$collection->push($array2);
		$total_cant = 0;
	}

	// $cant_producto = Charts::create('bar', 'highcharts')
	// 		->title('Cantidad Total por Producto')
	// 		->elementLabel('Total')
	// 		->labels($filtered->pluck('producto'))
	// 		->values($filtered->pluck('cant_producto'))
	// 		->legend(false)
	// 		->responsive(true);

	$cant_categoria = Charts::create('pie', 'highcharts')
			->title('Total por Categoria')
			->elementLabel('Total')
			->labels($collection->pluck('categoria'))
			->values($collection->pluck('cantidad_total'))
			->legend(false)
			->responsive(true);


	return view('ventas.stats.stats', compact('empresa','cant_ordenes','rev_orden','cant_producto','cant_categoria'));
	}
}