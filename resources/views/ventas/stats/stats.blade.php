@extends('layout.master')
    @section('page-title')
        <h2 class="title bold">Métricas <i class="fa fa-line-chart"></i> <small>{{$empresa}}</small></h2>
    @endsection

    @section('add-styles')
        {!! Charts::styles() !!}
    @endsection

    @section('content')
    @role('admin'||'gerente_general')

        <div class="col-lg-12">
            <section class="box ">
                <div class="content-body">
                    <div class="row">
                        {!! $cant_ordenes->html() !!}
                    </div>
                </div>
            </section>
        </div>

        <div class="col-lg-12">
            <section class="box ">
                <div class="content-body">
                    <div class="row">
                        {!! $rev_orden->html() !!}
                    </div>
                </div>
            </section>
        </div>

        <div class="col-lg-6">
            <section class="box ">
                <div class="content-body">
                    <div class="row"> 
                        {!! $cant_categoria->html() !!}
                    </div>
                </div>
            </section>
        </div>

     @endsection  
    @endrole

@section('add-plugins')
    {!! Charts::scripts() !!}
    {!! $cant_ordenes->script() !!}
    {!! $rev_orden->script() !!}

    {!! $cant_categoria->script() !!}
@endsection