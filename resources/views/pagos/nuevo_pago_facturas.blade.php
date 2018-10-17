@extends('layout.master')

    @section('page-title')
        <h2 class="title bold">Pagos</h2>
    @endsection

    @section('panel-title')
       <h2 class="title pull-left">Nuevo pago de | <strong>{{ $cliente->empresa }}</strong></h2>
    @endsection

@section('add-styles')
    <link href="{{ asset('assets/plugins/messenger/css/messenger.css') }}" rel="stylesheet" type="text/css" media="screen"/>
    <link href="{{ asset('assets/plugins/messenger/css/messenger-theme-future.css') }}" rel="stylesheet" type="text/css" media="screen"/>
    <link href="{{ asset('assets/plugins/messenger/css/messenger-theme-flat.css') }}" rel="stylesheet" type="text/css" media="screen"/>        
    <link href="{{ asset('assets/plugins/messenger/css/messenger-theme-block.css') }}" rel="stylesheet" type="text/css" media="screen"/>

    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-multiselect.css') }}" type="text/css"/>
@endsection

@section('content')

    <section class="box primary">
        <!--  PANEL HEADER    -->      
        <header class="panel_header">
            <h2 class="title pull-left">@yield('panel-title')</h2>
            <!--<div class="actions panel_actions pull-right">
                <i class="box_toggle fa fa-chevron-down"></i>
                <i class="box_setting fa fa-cog" data-toggle="modal" href="#section-settings"></i>
                <i class="box_close fa fa-times"></i>
            </div> -->
        </header>
        <div class="content-body">    
                <div class="row">
              {!! Form::open(array('id' => 'pago_form','route' => ['pagos.nuevo_pago', $cliente->id], 'method'=>'POST','class' => 'form-inline')) !!}

                <div class="well transparent">
                    <div class="row">
                        <div class="col-md-12  col-sm-12 col-xs-12">
                            <h2 class="bold">Controles</h2>
                            <p><label>Seleccione los controles</label></p>
                                <div class="pull-left">
                                    {!! Form::select('facturas[]', $facturas, null, ['multiple' => true, 'id' => 'facturas', 'class' => 'form-control col-md-6']) !!}
                                </div>
                                <div class="pull-right top15">
                                    <h3 id="monto_total">Monto Total: $0.00</h3><span id="loading" hidden="true">Calculando...</span>
                                </div>
                        </div>
                    </div>
                </div>
                <div class="clearfix top15"></div>
                <!-- BOTONES -->
                <div class="row top15">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                        <button type="submit" class="btn btn-primary right15"><i class="fa fa-arrow-right"></i> Siguiente</button>
                         <a type="button" class="btn" href="{{ URL::previous() }}">Cancelar</a>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </section>
@endsection

@section('add-plugins')

        <!-- OTHER SCRIPTS INCLUDED ON THIS PAGE - START --> 
    <script type="text/javascript" src="{{ asset('assets/js/bootstrap-multiselect.js') }}"></script>
    <script src="{{ asset('assets/plugins/messenger/js/messenger.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/plugins/messenger/js/messenger-theme-future.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/plugins/messenger/js/messenger-theme-flat.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/messenger.js') }}" type="text/javascript"></script><!-- OTHER SCRIPTS INCLUDED ON THIS PAGE - END --> 
    <!-- JS NECESARIO PARA ORDENES - START --> 
    <script type="text/javascript">


    $(document).ready(function() {
        $('#facturas').multiselect({
            includeSelectAllOption: true,
            selectAllText: 'Seleccionar todas',
            selectAllValue: 'select-all-value',
            buttonText: function(options, select) {
                if (options.length === 0) {
                    return 'Ningun control seleccionado...';
                }
                else if (options.length > 4) {
                    return 'Mas de 4 controles seleccionados';
                }
                 else {
                     var labels = [];
                     options.each(function() {
                         if ($(this).attr('label') !== undefined) {
                             labels.push($(this).attr('label'));
                         }
                         else {
                             labels.push($(this).html());
                         }
                     });
                     return labels.join(', ') + '';
                 }
            }
        });
        $('#facturas').on('change',function(){
            var id_facturas = $('select[id=facturas]').val();
             $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                url: '/check_monto',
                data: {"idFacturas": id_facturas},
                // beforeSend: function(){
                //     $('#loading').show();
                // },
                success: function(data, textStatus){
                    $('#monto_total').html('Monto Total: $'+data);
                },
                complete: function(jqXHR, textStatus){
                    if(textStatus == "error"){
                        $('#monto_total').html('Monto Total: $0.00');
                    }
                }
            });
        });
    });
    $( document ).ajaxStart(function() {
      $( "#loading" ).show();
    });
    $( document ).ajaxStop(function() {
      $( "#loading" ).hide();
    });
    </script> 
    <!-- JS NECESARIO PARA ORDENES - END --> 
@endsection