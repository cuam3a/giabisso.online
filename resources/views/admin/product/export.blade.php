<head>
    <meta charset="utf-8">
  </head>
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
            <th class="titleMain" colspan="9">Justo a Tiempo -Listado de Productos-</th>
        </tr>
        <tr class="title">
            <th>SKU</th>
            <th>No. Artículo</th>
            <th>Nombre</th>
            <th>Marca</th>
            <th>Precio regular</th>
            <th>Precio promoción</th>
            <th>Inventario</th>
            <th>Estatus</th>
            <th>Categoría/Subcategoria</th>
            <th>Inicio promoción</th>
            <th>Fin promoción</th>
            <th>Descripción</th>
            <th>Especificaciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach($products as $product)
        <tr class="bodyText">
            <td style="text-align:center">&nbsp;{{ $product->sku }}</td>
            <td>{{$product->product_number}}</td>
            <td>{{$product->name}}</td>
            <td>{{$product->brand}}</td>
            <td>{{$product->regular_price_s}}</td> 
            <td>{{$product->offer_price_s}}</td>
            <td>{{$product->stock}}</td>
            <td>{{$product->getStatusTextAttribute()}}</td>
            <td>{{$product->category}}</td>
            <td>{{$product->offer_date_start == null ? '' : $product->offer_date_start->format('d/m/Y H:i')}}</td>
            <td>{{$product->offer_date_end == null ? '' : $product->offer_date_end->format('d/m/Y H:i')}}</td>
            <td>{{$product->description}}</td>
            <td>{{$product->specifications}}</td>
        </tr>
        @endforeach
    </tbody> 
</table> 