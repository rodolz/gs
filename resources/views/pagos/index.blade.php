@extends('layout.master')


    @section('page-title')
        <h2 class="title bold">Pagos</h2>
    @endsection

    @section('panel-title')
       <h2 class="title pull-left">Lista de Pagos</h2>
    @endsection

@section('content')
    <section class="box primary">
        <!--  PANEL HEADER    -->
        <header class="panel_header">
            @yield('panel-title')
            <!--<div class="actions panel_actions pull-right">
                <i class="box_toggle fa fa-chevron-down"></i>
                <i class="box_setting fa fa-cog" data-toggle="modal" href="#section-settings"></i>
                <i class="box_close fa fa-times"></i>
            </div> -->
        </header>
        <div class="content-body">
    {{--         <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="pull-left">
                        <h2 class="bold">Pagos</h2>
                    </div>
                </div>
            </div> --}}

            <div class="row top15">
                	<div class="table-responsive">
                        <table id="tabla_pagos" class="table table-hover">
                        <thead>
                            <tr>
                            </tr>
                    	</thead>
                        <tbody>
                        </tbody>
                        </table>
                    </div>
            </div>
        </div>
    </section>
@endsection

@section('add-plugins')
<script type="text/javascript">

    $(function(){
        $('#tabla_pagos').DataTable({
            aaSorting: [],
            language: {
                "decimal": ",",
                "thousands": "."
            },
            processing: true,
            bServerSide: true,
            ajax: '{!! URL::asset('pagos/postdata') !!}',
            columns: [
                {data: 'cliente', name: 'cliente', title: 'Cliente', searchable: true, orderable:true },
                {data: 'numero_referencia', name: 'numero_referencia', title: '# Referencia' },
                {data: 'descripcion', name: 'descripcion', title: 'Descripcion'},
                {
                    data: 'monto_pago', name: 'monto_pago', title: 'Monto',
                    render: $.fn.dataTable.render.number( ',', '.', 2, '$' )
                },
                {data: 'banco', name: 'banco', title: 'Banco'},
                {data: 'fecha', name: 'fecha', title: 'Fecha'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });
    });
    $(document).ready(function(){
        $("#delete").submit(function( event ) {
            event.preventDefault();
            alert("YES");
            swal({
                title: 'Are you sure?',
                text: "Please click confirm to delete this item",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: true
            }).then(function() {
                    $("#delete_pago").off("submit").submit();
            }, function(dismiss) {
                // dismiss can be 'cancel', 'overlay',
                // 'close', and 'timer'
                if (dismiss === 'cancel') {
                    swal('Cancelled', 'Delete Cancelled :)', 'error');
                }
            })
        });
    });
</script>

@endsection
