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
            <th>Nombre</th>
            <th>Correo</th>
            <th>Total</th>
            <th>Fecha</th>
            <th>Productos</th>
        </tr>
    </thead>
    <tbody class="bodyText">
        @foreach($carts as $cart)
        <tr>
            <td>{{$cart->fullname}}</td>
            <td>{{$cart->email}}</td>
            <td>{{$cart->total}}</td>
            <td>{{$cart->date_updated}}</td>
            <?php
                $products = unserialize($cart->content);
                $products = $products->map(function ($item, $key) {
                return $item->name;
            });
            ?>
            <td style="wrap-text: true">
                @foreach($products as $key => $product)            
                    {!! $product."<br />"!!}
                @endforeach    
            </td>
        </tr>
        @endforeach
    </tbody>   
</table>