<!-- Datos personales -->
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
    
    .subtitle{
        font-size: 10;
    }

    .bodyText{
        font-size: 10;
    }
</style>

<!-- Pedidos -->
<table>
    <thead>
        <tr class="titleMain">
            <th colspan="9">Home Express Center</th>
        </tr>
        <tr>
            <th class="title" colspan="9">Listado de Pedidos</th>
        </tr>
        <tr class="subtitle">
            <th>Folio</th>
            <th>Nombre</th>
            <th>Correo</th>
            <th>Celular</th>
            <th>Teléfono</th>
            <th>Estatus pedido</th>
            <th>Estatus pago</th>
            <th>Total</th>
            <th>Fecha de pedido</th>
            <th>Fecha de pago</th>
            <th>Requiere factura</th>
            <th>Dirección</th>
            <th>Ciudad</th>
            <th>Estado</th>
        </tr>
    </thead>
    <tbody class="bodyText">
        @foreach($orders as $order)
        <tr>
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
            <td>{{$order->address()}}</td>
            <td>{{$order->City->name }}</td>
            <td>{{$order->State->name}}</td>
        </tr>
        @endforeach
    </tbody>   
</table>