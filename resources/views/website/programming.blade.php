@extends('layouts.website')
@section('extra-head')
<link rel="stylesheet" href="{{ asset('/website/css/cart.css') }}">
<link rel="stylesheet" href="{{ asset('/website/css/cart-sidebar.css') }}">
@stop
@section('content')
<section class="cart mt-5">
    <div class="pl-5">
        @if(Session::has('flash_message'))
        <div class="row">
            <div class="col-12">
                <div class="alert alert-{{Session::get('flash_type')}} alert-dismissible"><button type="button"
                        class="close" data-dismiss="alert" aria-label="Cerrar"><span
                            aria-hidden="true">&times;</span></button><strong>{{Session::get('flash_title')}}</strong>
                    {!!Session::get('flash_message')!!}</div>
            </div>
        </div>
        @endif
        <div class="row">
            <div class="text-danger"> Nota: La Fecha de Entrega debe de ser mayor a 7 días</div>
            @if(count($orderProg)==0)
            <div
                class="col-12 col-sm-12 d-flex flex-column flex-sm-row justify-content-center border no-products p-5 mb-3  align-items-center">
                <div class="p-3"><img src="/website/img/sin-prods_carrito.png"></div>
                <div class="d-flex flex-column align-items-center align-items-sm-start">
                    <h4 class="font-weight-bold">Aún no tienes productos en tu pedido</h4>
                    <p>Conoce nuestros productos y promociones!</p>
                    <a href="{{route('category-products')}}">Ir a productos</a>
                </div>
            </div>
            @else
            <!--<div class="col-12 col-sm-12 col-md-8"> 
                    <a class="mt-1 mb-1 btn btn-sm btn-primary float-right text-white" href="{{route('export-order-pdf')}}"><i class="fa fa-download"></i> Cotización</a>
                </div>-->
            <div class="col-md-12 form-inline">
                <div class="col-md-10"></div>
                <div class="col-md-2">
                    <button class="button--primary col-md-12 text-center" id="btnCambios">Guardar Cambios</button>
                </div>
            </div>

            <div class="col-md-12 mt-2">
                <table class="table col-md-12">
                    <tr class="text-center">
                        <th>PRODUCTOS</th>
                        <th>PRECIO</th>
                        <th>CANTIDAD</th>
                        <th>TOTAL</th>
                        <th>FECHA ENTREGA</th>
                        <th></th>
                    </tr>
                    <form action="{{route('update-product-programming-admin')}}" method="POST" class="frmCart">
                        @foreach($orderProg as $orderItem)
                        {{ csrf_field() }}
                        <tr>
                            <td>{{ $orderItem->name }}</td>
                            <td class="text-right">{{ dinero($orderItem->unit_price) }}</td>
                            <td><input name="quantity-{{$orderItem->id}}" type="number"
                                    class="form-control form-control-sm text-right" aria-label="Quantity"
                                    value="{{$orderItem->quantity}}" min="1"></td>
                            <td class="text-right">{{dinero($orderItem->unit_price*$orderItem->quantity)}}</td>
                            <td><input name="date_send-{{$orderItem->id}}" class="form-control form-control-sm text-center dateForm"
                                    type="date" value="{{$orderItem->date_send}}" /></td>
                            <td><a href="#" class="void btnDelete" style="color:red; width:100%"
                                    data-id="{{$orderItem->id}}"
                                    data-url="{{ route('delete-product-from-programming', [$orderItem->id]) }}"><i
                                        class="fa fa-close" aria-hidden="true"></i></a></td>
                        </tr>

                        @endforeach
                        <!--<button type="submit" class="mt-4 btn btn-primary float-right">Actualizar pedido</button>-->
                    </form>
                </table>
            </div>
            <div class="col-md-12 form-inline">
                <div class="col-md-8"></div>
                <div class="col-md-2 text-right font-weight-bold">TOTAL PEDIDO:</div>
                <div class="col-md-2">
                    <input class="form-control form-control-sm col-md-12 text-center" value="{{ dinero($total) }}" readonly />
                </div>
            </div>
            <div class="col-md-12 form-inline mt-2">
                <div class="col-md-10"></div>
                <div class="col-md-2">
                    <a class="button--primary col-md-12 text-center btnSendOrder" href="{{ route('create-orderProgramming') }}">Enviar
                        Pedido</a>
                </div>
            </div>
            @endif
        </div>
    </div>
</section>
@stop

@section('js')
<script>
$('.collapse').on('show.bs.collapse', function() {
    $(this).siblings('.card-header').addClass('active');
});

$('.collapse').on('hide.bs.collapse', function() {
    $(this).siblings('.card-header').removeClass('active');
});
$(".btnDelete").on("click", function() {
    swal({
        title: '¿Eliminar?',
        text: "Se eliminará el producto del pedido ¿Desea continuar?",
        type: 'warning',
        html: '<p>Se eliminará el producto del pedido<br> ¿Desea continuar?</p>',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, eliminarlo',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.value) {
            var newForm = $('<form>', {
                'action': $(this).data('url'),
                'method': 'GET'
            });
            newForm.appendTo('body').submit();
        }
    });
});

$("#btnCambios").on("click", () => {
    $(".frmCart").submit();
})

$(".btnSendOrder").on("click", (e) => {
    debugger
    let dates = $(".dateForm");
    let now = new Date();
    var dateComp = new Date(now.getFullYear(),now.getMonth(),now.getDate()+7);
    
    let valid = true;
    if(typeof dates != "undefined" && dates.length > 0){
        $.each(dates, function( index, value ) {
            var mydate = new Date($(value).val());
            if(mydate < dateComp){
                $(value).addClass("border-danger");
                valid = false;
            }
        });
    }
    
    if(!valid){
        e.preventDefault();
    }
    
})
</script>
@stop