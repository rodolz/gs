<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFacturasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facturas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('num_factura')->unique();
            $table->integer('idOrden')->unique();
            $table->integer('idCliente');
            $table->string('condicion');
            $table->integer('itbms');
            $table->float('monto_factura',10,2);
            $table->integer('idFacturaEstado')->unsigned();
            $table->integer('num_fiscal')->unsigned()->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('facturas');
    }
}
