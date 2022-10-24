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
<table>
    <thead>
        <tr class="titleMain">
            <th colspan="7">Home Express Center</th>
        </tr>
        <tr class="title" >
            <th colspan="7">Clientes</th>
        </tr>
        <tr class="subtitle">
            <th>Id</th>
            <th>Nombre</th>
            <th>Correo</th>
            <th>Celular</th>
            <th>Teléfono</th>
            <th>Fecha de Registro</th>
            <th>Direcciones</th>
        </tr>
    </thead>
    <tbody class="bodyText">
        @foreach($customer as $customers)
        <tr>
            <td>{{$customers->id}}</td>
            <td>{{$customers->fullname()}}</td>
            <td>{{$customers->email}}</td>
            <td>{{$customers->cell_phone}}</td>
            <td>{{$customers->phone}}</td>
            <td>{{$customers->created_at}}</td>
            @foreach($customers->address_book as $address)
            <td>{!!$address->fullAddress()!!}</td>
             @endforeach    
        </tr>
        @endforeach
    </tbody>   
</table>