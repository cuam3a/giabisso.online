<style>
    th{
    text-align: center;
    }

    .titleMain{
        font-size: 16;
    }
    .title{
        font-size: 14;
    }
    .bodyText{
        font-size: 10;
    }
</style>
<table>
    <thead>
        <tr>
            <th class="titleMain" colspan="9">Justo a Tiempo -Listado de Pedidos-</th>
        </tr>
        <tr class="title">
            <th>Folio</th>
            <th>Nombre</th>
            <th>Correo</th>
            <th>Celular</th>
            <th>Telefono</th>
            <th>Estatus pedido</th>
            <th>Estatus pago</th>
            <th>Pago</th>
            <th>Fecha de pedido</th>
            <th>Fecha de pago</th>
            <th>Requiere factura</th>
            <th>Ciudad</th>
            <th>Estado</th>
        </tr>
    </thead>
    <tbody>
        @foreach($orders as $order)
        <tr class="bodyText">
            <td>{{$order->folio()}}</td>
            <td>{{$order->fullname()}}</td>
            <td>{{$order->email}}</td>
            <td>{{$order->cell_phone}}</td>
            <td>{{$order->phone}}</td>
            <td>{{$order->getStatusTextAttribute()}}</td>
            <td>{{$order->getPaymentTextAttribute()}}</td>
            <td>{{$order->total}}</td>
            <td>{{$order->created_at}}</td>
            <td>{{$order->date_payment()}}</td>
            <td>{{$order->getInvoiceRequiredAttribute()}}</td>
            <td>{{$order->City->name }}</td>
            <td>{{$order->State->name}}</td>
        </tr>
        @endforeach
    </tbody>   
</table>