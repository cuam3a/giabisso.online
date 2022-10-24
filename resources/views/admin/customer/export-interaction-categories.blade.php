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
            <th class="titleMain" colspan="9">Home Express Center - Listado de usuarios por categoría {{$daterange}}-</th>
        </tr>
        <tr class="title">
            <th>Nombre</th>
            <th>Correo electrónico</th>
            <th>Categoría</th>
            <th>Vistas</th>
        </tr>
    </thead>
    <tbody>
        @foreach($users as $user)
        <tr class="bodyText">
            <td>{{$user->fullname}}</td>
            <td>{{$user->email}}</td>
            <td>{{$user->category}}</td>
            <td style="text-align:center">{{$user->views}}</td>
        </tr>
        @endforeach
    </tbody>   
</table>