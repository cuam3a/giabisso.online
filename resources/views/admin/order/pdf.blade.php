<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<head>
<style type="text/css">
@import url('https://fonts.googleapis.com/css?family=Source+Sans+Pro:200,600');
@page {
    margin: 1em 2em;
}
    body{
        font-family: 'Source Sans Pro', sans-serif;
        font-size:12px;
    }
    td,tr,.title{
        font-weight:200;
    }
    span,.title,b,h2{
        color:#231f20!important;
        font-weight:600!important;
    }
    h2{
        margin:0;
    }
    .float-right{
        float:right;
    }
    .page-break {
        page-break-after: always;
    }
    table{
        border-collapse: collapse;
        width:100%;
    }
    th{
        text-align: center;
    }
    .title{
        background-color:#f2f3f4;
        padding:5px 5px 8px;
    }
    .backGrey{
        background-color:#f2f3f4;
    }
    .bodyText{
        font-size: 10;
    }
    td{
        padding:2px 6px;
    }
    .text-center{
        text-align:center;
    }
    .text-right{
        text-align:right;
    }
    .bothLines td{
        border-bottom:1px solid #bcbdc0!important;
        border-top:1px solid #bcbdc0;
    }
    .products td{
        border-bottom:1px solid #bcbdc0!important;
        padding:10px;
    }
    .products.line td{
        border-top:1px solid #bcbdc0!important;
    }
    .rowPad td{
        padding:10px 0;
    }
</style>
</head>
<table style="margin-bottom:8px">
        <tr>
            <td colspan="3" rowspan="3"><img src="{{public_path().'/img/hec_full_borde.png'}}" style="height:60px"></td>
            <td colspan="1" class="text-right">Pedido: </td>
            <td colspan="1" class="text-right" style="width:10%"><span>{{$order->folio()}}</span></td>
        </tr>
        <tr>
            <td colspan="1" class="text-right">Fecha: </td>
            <td colspan="1" class="text-right" style="width:10%"><span>{{ $order->date_created_day()}}</span></td>
        </tr>
        <tr>
            <td colspan="1" class="text-right">Hora: </td>
            <td colspan="1" class="text-right" style="width:10%"><span>{{ $order->date_created_time()}}</span></td>
        </tr>
        <tr class="bothLines"><td colspan="5">Número de guia: {{$order->tracking_number or '-'}} ({{ $order->order_shipment_type->text or ''}})</td></tr>
        <tr><td colspan="5"></td></tr>
    </table>
<table>
        <tr>
            <td colspan="5" class="title"><span>Datos de envío</span></td>
        </tr>
        <tr>
            <td colspan="5" style="padding-top:10px;"><span>Nombre: </span>{{$order->fullname()}}</td>
        </tr>
        <tr>
            <td colspan="5"><span>Correo: </span>{{$order->email}}</td>
        </tr>
        <tr>
            <td colspan="5"><span>Teléfono: </span> {{$order->phone}} <span  style="margin-left:8px">Celular:</span>{{$order->phone}}</td>
        </tr>
        <tr>
            <td colspan="5"><span>Dirección: </span>{!! str_replace("<br>"," ",$order->fullAddress())!!}</td>
        </tr>
        <tr>
            <td colspan="5">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="5" class="title"><span>Datos de facturación</span></td>
        </tr>
        @if($order->invoice_require)
            <tr>
                <td colspan="5" style="padding-top:10px"><span>Razón social: </span>{{$order->f_name}}</td>
            </tr>
            <tr>
                <td colspan="5"><span>RFC: </span>{{$order->f_name}}</td>
            </tr>
            <tr>
                <td colspan="5"><span>Correo:</span> {{$order->f_email}} <span style="margin-left:8px">Teléfono: </span>{{$order->f_phone}}</td>
            </tr>
            <tr>
                <td colspan="5"><span>Dirección: </span>{!! str_replace("<br>"," ",$order->fullAddressInvoice())!!}</td>
            </tr>
             <tr>
                <td colspan="5">&nbsp;</td>
            </tr>
        @else
            <tr>
                <td colspan="5">No solicitado</td>
            </tr>
        @endif
        <tr>
            <td colspan="5" class="title"><span>Pago</span></td>
        </tr>
        <tr class="rowPad">
            <td style="width:25%;">Estatus de envio</td><td class="text-right" style="width:25%;padding-right:30px"><span>{{App\Models\Order::$status[$order->status]}}</span></td>
            <td style="width:25%;padding-left:30px">Estatus de pago</td><td class="text-right" style="width:25%"><span>{{App\Models\Order::$payment[$order->payment_status]}}</span></td>
        </tr>
        <tr class="bothLines">
            <td colspan="1">Total</td>
            <td colspan="4"><h2 style="text-align:right">{{dinero($order->total)}}</h2></td>
        </tr>
        <tr>
            <td colspan="5">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="5">Detalle de pedido: <b class="float-right">{{$order->folio()}}</span></td>
        </tr>
        <table>
        <tr>
            <td>Cantidad</td>
            <td>SKU / # Producto</td>
            <td>Nombre</td>
            <td class="text-center">Precio unitario</td>
            <td class="text-right">Importe</td>
        </tr>
        @foreach($order->order_details as $detail)
            <tr @if(!$loop->last) class="products line" @else class="products" @endif>
                <td>{{$detail->quantity}}</td>
                <td>{{$detail->sku}} @if($detail->product_number!=null) {{ ' / '.$detail->product_number }} @endif</td>
                <td>{{$detail->name}}</td>
                <td class="text-center">{{dinero($detail->unit_price)}}</td>
                <td class="text-right">{{dinero($detail->amount)}}</td>
            </tr>  
        @endforeach
        <tr class="backGrey"><td colspan="5">Subtotal:<span class="float-right">{{dinero($order->subtotal)}}</span></td></tr>    
        <tr><td colspan="2">Envio:</td><td colspan="3" class="text-right">{{dinero($order->shipping)}}</td></tr>
        <tr class="backGrey"><td colspan="5">Total:<span class="float-right">{{dinero($order->total)}}</span></td></tr>
</table>
</html>