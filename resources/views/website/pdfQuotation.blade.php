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
            <td colspan="2" class="text-right">Cotización</td>
        </tr>
        <tr>
            <td colspan="1" class="text-right">Fecha: </td>
            <td colspan="1" class="text-right" style="width:10%"><span>{{ date('d-m-Y')}}</span></td>
        </tr>
        <tr>
            <td colspan="1" class="text-right">Hora: </td>
            <td colspan="1" class="text-right" style="width:10%"><span>{{ date('H:i:s')}}</span></td>
        </tr>
        <tr><td colspan="5"></td></tr>
    </table>
    <table>
    <tr>
        <td colspan="5" class="title"><span>Cliente</span></td>
        </tr>
        <tr>
            <td colspan="5" style="padding-top:10px;"><span>Nombre: </span>{{$customer->f_name}}</td>
        </tr>
        <tr>
            <td colspan="5" style=""><span>RFC: </span>{{$customer->f_rfc  }}</td>
        </tr>
        <tr>
            <td colspan="5"><span>Dirección: </span>{{$customer->f_address}} <span  style="margin-left:8px">C.P.:</span> {{$customer->f_zipcode}}</td>
        </tr>
        <tr>
            <td colspan="5"><span>Ciudad: </span>{{$customer->getCity()}} <span  style="margin-left:8px">Estado:</span> {{$customer->getState()}}</td>
        </tr>
        <tr>
            <td colspan="5" style="padding-top:10px;"><span>Contacto: </span>{{$customer->fullname()}}</td>
        </tr>
        <tr>
            <td colspan="5"><span>Teléfono: </span> {{$customer->phone}} <span  style="margin-left:8px">Celular:</span> {{$customer->cell_phone}}</td>
        </tr>
        <tr>
            <td colspan="5"><span>Correo: </span>{{$customer->email}}</td>
        </tr>
        <tr>
            <td colspan="5">&nbsp;</td>
        </tr>
        <tr>
        <td colspan="5" class="title text-center"><span>Detalles del pedido</span></td>
        </tr>
    </table>
<table>
        
        <tr>
            <td>Cantidad</td>
            <td>Nombre</td>
            <td class="text-center">Precio unitario</td>
            <td class="text-center">Total</td>
        </tr>
        @foreach($car as $detail)
            <tr>
                <td>{{$detail->qty}}</td>
                <td>{{$detail->name}}</td>
                <td class="text-center">{{dinero($detail->price)}}</td>
                <td class="text-right">{{dinero($detail->price * $detail->qty)}}</td>
            </tr>  
        @endforeach
        <tr>
            <td colspan="5">&nbsp;</td>
        </tr>

        <tr class="backGrey"><td colspan="5">Subtotal:<span class="float-right">{{ dinero($subtotal) }}</span></td></tr>    
        <tr class="backGrey"><td colspan="5">Envio:<span class="float-right">{{ dinero($shipmentTotal)}}</span></td></tr>
        <tr class="backGrey"><td colspan="5">Total:<span class="float-right">{{ dinero($subtotal + $shipmentTotal)}}</span></td></tr>
</table>
</html>