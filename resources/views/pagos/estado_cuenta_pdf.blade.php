<!DOCTYPE html>
<html>
<header>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        @import "https://fonts.googleapis.com/css?family=Montserrat:300,400,700";
        .page-break {
            page-break-after: always;
        }
        body {
          padding: 0 2em;
          font-family: Montserrat, sans-serif;
          -webkit-font-smoothing: antialiased;
          text-rendering: optimizeLegibility;
          color: #505458;
          background: #fffff;
        }

        h1 {
          font-weight: normal;
          letter-spacing: -1px;
          color: #34495E;
        }
        .bold{
            font-weight: bold;
        }
        .rwd-table {
          background: #eaeaea;
          color: rgba(118, 118, 118, 1.0);
          border-radius: .4em;
          overflow: hidden;
        }
        .rwd-table th{
            background: #1fb5ac;
        }
        .rwd-table th, .rwd-table td {
            padding: 1em !important;
        }
        .rwd-table th, .rwd-table td:before {
            height: 10px;
          color: white;
        }
        .blue{
            background: #23b7e5 !important;
        }
        .yellow{
            background: #fdb45c !important;
        }
        .red{
            background: #f05050 !important;
        }

    </style>
</header>
    <body>
        <div class="row">
            <img src="{{ base_path() }}/assets/images/cintillo_control.png" alt="bf_cintillo" width="700px" />
            <div class="col-md-12 col-sm-12 col-xs-12">
                <center>
                    <h1>Estado de Cuenta</h1><br>
                </center>
                <h2>Cliente: {{$cliente->empresa}}</h2>
                <h2>Monto Total: ${{number_format($monto_factura,2,'.',',')}}</h2>
                <label>Fecha: {{date('d-m-Y')}}</label>
                <div class="table-responsive">
                    <table class="rwd-table">
                        <thead>
                            <tr>
                                <th><h4>Fiscal #</h4></th>
                                <th><h4>Control #</h4></th>
                                <th><h4>Condición</h4></th>
                                <th><h4>Fecha</h4></th>
                                <th class="blue"><h4>30 días</h4></th>
                                <th class="yellow"><h4>60 días</h4></th>
                                <th class="red"><h4>90 días o mas</h4></th>
                                <!-- <td class="text-right"><h4>Totales</h4></td> -->
                            </tr>
                        </thead>
                        <tbody>
                        @if(count($facturas) > 0)
                            @php
                                $total_30dias = 0;
                                $total_60dias = 0;
                                $total_90dias = 0;
                            @endphp
                            @foreach ($facturas as $factura)
                                    <tr>
                                        <td class="bold">{{ $factura->num_fiscal }}</td>
                                        <td class="text-center">{{ $factura->num_factura }}</td>
                                        <td class="text-center">{{ $factura->condicion }}</td>
                                        <td class="text-center">{{ $factura->created_at->format('d/m/Y') }}</td>
                                        @php
                                        $date1 = new DateTime($factura->created_at->format('Y-m-d'));
                                        $date2 = new DateTime(date('Y-m-d', strtotime('TODAY')));
                                        $interval = $date1->diff($date2);
                                        @endphp
                                        
                                        @if ($interval->m <= 1)
                                                <!-- Plazo hasta 30 dias -->
                                                @if($interval->m == 0 && $interval->d >= 0)
                                                    @php ($total_30dias = $total_30dias + $factura->monto_factura)
                                                    <td class="text-center text-info">${{ $factura->getAmountFormatted() }}</td>
                                                    <td class="text-center">&nbsp;</td>
                                                    <td class="text-center">&nbsp;</td> 
                                                @elseif ($interval->m == 1 && $interval->d == 0)
                                                    @php ($total_30dias = $total_30dias + $factura->monto_factura)
                                                    <td class="text-center text-info">${{ $factura->getAmountFormatted()  }}</td>
                                                    <td class="text-center">&nbsp;</td>
                                                    <td class="text-center">&nbsp;</td> 
                                                <!-- Plazo hasta 60 dias  -->
                                                @elseif($interval->m == 1 && $interval->d > 0)
                                                    @php ($total_60dias = $total_60dias + $factura->monto_factura)
                                                    <td class="text-center">&nbsp;</td>
                                                    <td class="text-center text-warning">${{ $factura->getAmountFormatted()  }}</td>
                                                    <td class="text-center">&nbsp;</td>
                                                @endif
                                        @elseif ($interval->m == 2)
                                                @if ($interval->d == 0)
                                                    @php ($total_60dias = $total_60dias + $factura->monto_factura)
                                                    <td class="text-center">&nbsp;</td>
                                                    <td class="text-center text-warning">${{ $factura->getAmountFormatted()  }}</td>
                                                    <td class="text-center">&nbsp;</td> 
                                                @else
                                                    @php ($total_60dias = $total_60dias + $factura->monto_factura)
                                                    <td class="text-center">&nbsp;</td>
                                                    <td class="text-center text-warning">${{ $factura->getAmountFormatted()  }}</td>
                                                    <td class="text-center">&nbsp;</td>  
                                                @endif
                                        @else
                                                @if ($interval->d == 0)
                                                    @php ($total_90dias = $total_90dias + $factura->monto_factura)
                                                    <td class="text-center">&nbsp;</td>
                                                    <td class="text-center">&nbsp;</td>
                                                    <td class="text-right text-danger">${{ $factura->getAmountFormatted() }}</td> 
                                                @else
                                                    @php ($total_90dias = $total_90dias + $factura->monto_factura)
                                                    <td class="text-center">&nbsp;</td>
                                                    <td class="text-center">&nbsp;</td>
                                                    <td class="text-right text-danger">${{ $factura->getAmountFormatted()  }}</td>
                                                @endif
                                        @endif            
                                    </tr>
                            @endforeach
                            <tr>
                                <td class="thick-line">Totales</td>
                                <td class="thick-line"></td>
                                <td class="thick-line"></td>
                                <td class="thick-line"></td>
                                <td class="thick-line text-center"><h4 style='margin:0px;' class="text-info">${{number_format($total_30dias,2,'.',',')}}</h4></td>
                                <td class="thick-line text-center"><h4 style='margin:0px;' class="text-warning">${{number_format($total_60dias,2,'.',',') }}</h4></td>
                                <td class="thick-line text-right"><h4 style='margin:0px;' class="text-danger">${{number_format($total_90dias,2,'.',',') }}</h4></td>
                            </tr>
                            @else
                                <tr>
                                    <td colspan="6">
                                        <h2 class="bold text-success text-center"><i class="fa fa-check-circle" aria-hidden="true" style="font-size:30px"></i> No tiene facturas pendientes</h2>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div> 
    </body>
</html>