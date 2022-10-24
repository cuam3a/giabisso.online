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
            <div class="col-md-12">
                <div class="alert alert-{{Session::get('flash_type')}} alert-dismissible"><button type="button"
                        class="close" data-dismiss="alert" aria-label="Cerrar"><span
                            aria-hidden="true">&times;</span></button><strong>{{Session::get('flash_title')}}</strong>
                    {!!Session::get('flash_message')!!}</div>
            </div>
        </div>
        @endif
        <div class="row">
            @if($cartBasket->count()==0)
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
            <div class="col-md-12 form-inline">
                <div class="col-md-6"></div>
                <div class="col-md-4 mb-2">
                    @if(Auth::guard('customer-web')->check())
                    <a class="button--primary" href="{{route('export-order-pdf')}}"><i class="fa fa-download"></i>
                        Cotización</a>
                    @endif
                </div>

                <div class="col-md-8">
                    <div class="col-md-12 title-row form-inline text-center">
                        <div class="col-md-4 ">PRODUCTOS</div>
                        <div class="col-md-3 hidden-sm-down">PRECIO</div>
                        <div class="col-md-2 hidden-sm-down ">CANTIDAD</div>
                        <div class="col-md-2 hidden-sm-down">TOTAL</div>
                        <div class="col-md-1 hidden-sm-down">&nbsp;</div>
                    </div>
                    <form action="{{route('update-product-cart')}}" method="POST" class="frmCart">
                        @foreach($cartBasket as $cartItem)
                        {{ csrf_field() }}
                        <div class="col-md-12 products">
                            <div class="row item">
                                <div class="col-md-12 form-inline">
                                    <div class="col-md-5 form-inline mt-3">
                                        <div class="col-md-4 image align-self-center">
                                            <a
                                                href="{{ route('product-detail', ['id' => $cartItem->options->product_id,'slug' => $cartItem->options->slug]) }}">
                                                <img src="{{ $cartItem->options->image }}" alt="{{$cartItem->name}}"
                                                    class="img-fluid">
                                            </a>
                                        </div>
                                        <div class="col-md-8 description">
                                            <a
                                                href="{{ route('product-detail', ['id' => $cartItem->options->product_id,'slug' => $cartItem->options->slug]) }}">
                                                <div class="text">{{$cartItem->name}}</div>
                                                <h4 class="title">{{$cartItem->brand}}</h4>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="product-prices col-md-7 form-inline">
                                        <div class=" col-md-4 prices order-2 order-md-1">
                                            {{dinero($cartItem->price)}}
                                        </div>
                                        <div class="col-md-3 order-3 order-md-2">
                                            <div class="options">
                                                <div class="quantity">
                                                    <div class="input-group number-input">
                                                        {{-- <span class="input-group-addon product-rest">-</span> --}}
                                                        <input name="rowId-{{$cartItem->id}}" type="hidden"
                                                            class="form-control form-control-sm"
                                                            value="{{$cartItem->rowId}}">
                                                        <input name="quantity-{{$cartItem->id}}" max="" type="number"
                                                            class="form-control form-control-sm" aria-label="Quantity"
                                                            value="{{$cartItem->qty}}" min="1">
                                                        {{-- <span class="input-group-addon product-add">+</span> --}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 total order-4 order-md-3">
                                            {{dinero($cartItem->price*$cartItem->qty)}}</div>
                                        <div class="col-md-1 actions order-md-4 desktop">
                                            <a href="#" class="void btnDelete" data-id="{{$cartItem->rowId}}"
                                                data-url="{{ route('delete-product-from-cart', [$cartItem->rowId]) }}"><i
                                                    class="la la-close" aria-hidden="true"></i></span></a>
                                        </div>
                                        <div class="text-center col-md-1 actions order-md-4 mobile">
                                            <a href="#" class="void btnDelete" style="color:red; width:100%"
                                                data-id="{{$cartItem->rowId}}"
                                                data-url="{{ route('delete-product-from-cart', [$cartItem->rowId]) }}"><i
                                                    class="fa fa-close" aria-hidden="true"></i></a>
                                        </div>
                                    </div>


                                </div>


                            </div>
                        </div>
                        @endforeach
                        <div class="col-md-12 form-inline">
                            <div class="col-md-9"></div>
                            <div class="col-md-3 mb-2">
                                <button type="submit" class="button--primary">Actualizar pedido</button>
                            </div>
                        </div>
                    </form>
                </div>
                <!--col-8-->
                {{-- {{Cart::content()}} --}}
                <div class="col-sm-4 col-md-4">
                    <div class="col-md-12">
                        <h4 class="col-md-12">TOTAL</h4>
                        <hr />
                        <div class="col-md-12 form-inline" style="font-size:15px">
                            <div class="name col-md-4 font-weight-bold text-right">Subtotal</div>
                            <div class="price col-md-8 text-right">{{dinero(Cart::subtotal())}}</div>
                        </div>
                        <div class="col-md-12 form-inline mt-2" style="font-size:15px">
                            <div class="name col-md-4 font-weight-bold text-right">Envío</div>
                            <div class="price col-md-8 text-right">{{dinero(Session::get('shipping'))}}</div>
                        </div>
                        <div class="col-md-12 form-inline mt-2">
                            <div class="name"><small style="font-size:10px">Tiempo de entrega:
                                    {{Session::get('shipping_description')}}</small></div>
                            @if($free_shipment)
                                @if($free_shipment->value =='ON')
                                <div style="color:#5aa407;padding:0 20px 20px;"><b>Envío gratis</b> en compras mayores a
                                    {!!dinero($amount->value)!!}</div>
                                @endif
                            @endif
                        </div>
                        <hr />
                        <div class="col-md-12 form-inline mt-2" style="font-size:15px">
                            <div class="name col-md-4 font-weight-bold text-right">Total</div>
                            <div class="price col-md-8 text-right">{{dinero(Session::get('total')) }}</div>
                        </div>
                        <!--/cart-content-->
                        <div class="deliveries mt-4">
                            {!! $shoppingcart->present()->registerToCheckOut() !!}
                            <a href="/tienda" class="button--primary col-md-12 text-center mt-2">SEGUIR COMPRANDO</a>
                        </div>
                    </div>
                    <!--col-3-->
                    @endif
                </div>
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
$(".frmCart").on("click", "a.btnDelete", function() {
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
</script>
@stop