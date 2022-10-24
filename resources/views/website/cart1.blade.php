@extends('layouts.website')
@section('extra-head')
    <link rel="stylesheet" href="{{ asset('/website/css/cart.css') }}">
    <link rel="stylesheet" href="{{ asset('/website/css/cart-sidebar.css') }}">
@stop
@section('content')
    <nav aria-label="breadcrumb" role="navigation">
        <div class="container">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                <li class="breadcrumb-item active" aria-current="page">Pedido</li>
            </ol>
        </div>
    </nav>
    <section class="cart">
        <div class="container">
        @if(Session::has('flash_message'))
            <div class="row">
                <div class="col-12">       
                    <div class="alert alert-{{Session::get('flash_type')}} alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button><strong>{{Session::get('flash_title')}}</strong> {!!Session::get('flash_message')!!}</div>
                </div>
            </div>
        @endif
            <div class="row">
                @if($cartBasket->count()==0)
                    <div class="col-12 col-sm-12 d-flex flex-column flex-sm-row justify-content-center border no-products p-5 mb-3  align-items-center">
                        <div class="p-3"><img src="/website/img/sin-prods_carrito.png"></div>
                        <div class="d-flex flex-column align-items-center align-items-sm-start">
                            <h4 class="font-weight-bold">Aún no tienes productos en tu pedido</h4>
                            <p>Conoce nuestros productos y promociones!</p>
                            <a href="{{route('category-products')}}">Ir a productos</a>
                        </div>
                    </div>
                @else
                <div class="col-12 col-sm-12 col-md-8"> 
                    <a class="mt-1 mb-1 btn btn-sm btn-primary float-right text-white" href="{{route('export-order-pdf')}}"><i class="fa fa-download"></i> Cotización</a>
                </div>
                <div class="col-12 col-sm-12 col-md-8"> 
                    <div class="col-12 col-sm-12 title-row">
                        <div class="col-6 col-sm-12 col-md-5 col-lg-5">PRODUCTOS</div>
                        <div class="col-2 hidden-sm-down" style="text-align:right">PRECIO</div>
                        <div class="col-2 hidden-sm-down ">CANTIDAD</div>
                        <div class="col-2 hidden-sm-down">TOTAL</div>
                        <div class="col-1 hidden-sm-down">&nbsp;</div>
                    </div>
                    <form action="{{route('update-product-cart')}}" method="POST" class="frmCart">
                    @foreach($cartBasket as $cartItem)
                             {{ csrf_field() }}
                            <div class="col-12 col-sm-12 products">                        
                                <div class="row item">
                                    <div class="col-3 col-md-2 image align-self-center">
                                        <a href="{{ route('product-detail', ['id' => $cartItem->options->product_id,'slug' => $cartItem->options->slug]) }}">
                                            <img src="{{ $cartItem->options->image }}" alt="{{$cartItem->name}}" class="img-fluid">
                                        </a>
                                    </div>
                                    <div class="col-9 col-md-3 description">
                                        <a href="{{ route('product-detail', ['id' => $cartItem->options->product_id,'slug' => $cartItem->options->slug]) }}">
                                            <div class="text">{{$cartItem->name}}</div>
                                            <h4 class="title">{{$cartItem->brand}}</h4>
                                        </a>
                                    </div>
                                    <div class="product-prices col-12 col-md-7">                      
                                        <div class="col-3 col-md-4 prices order-2 order-md-1">{{dinero($cartItem->price)}}</div>
                                        <div class="col-6 col-md-3 order-3 order-md-2">
                                            <div class="options">
                                                <div class="quantity">
                                                    <div class="input-group number-input">
                                                        {{-- <span class="input-group-addon product-rest">-</span> --}}
                                                        <input name="rowId-{{$cartItem->id}}" type="hidden" class="form-control" value="{{$cartItem->rowId}}">
                                                        <input name="quantity-{{$cartItem->id}}" max="{{$cartItem->options->stock}}" type="number" class="form-control" aria-label="Quantity" value="{{$cartItem->qty}}" min="1">
                                                        {{-- <span class="input-group-addon product-add">+</span> --}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-3 col-md-4 total order-4 order-md-3">{{dinero($cartItem->price*$cartItem->qty)}}</div>
                                        <div class="col-12 col-md-1 actions order-md-4 desktop">
                                                <a href="#" class="void btnDelete" data-id="{{$cartItem->rowId}}" data-url="{{ route('delete-product-from-cart', [$cartItem->rowId]) }}"><i class="la la-close" aria-hidden="true"></i></span></a>
                                            </div>
                                    </div>
                                    <div class="col-12 text-center col-md-1 actions order-md-4 mobile">
                                        <a href="#" class="void btnDelete" style="color:red; width:100%" data-id="{{$cartItem->rowId}}" data-url="{{ route('delete-product-from-cart', [$cartItem->rowId]) }}"><i class="la la-close" aria-hidden="true"></i><span class="visible-xs-*">Quitar producto</span></span></a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    <button type="submit" class="mt-4 btn btn-primary float-right">Actualizar pedido</button>
                </form>
                </div><!--col-8-->
                {{-- {{Cart::content()}} --}}
                <div class="col-12 col-sm-4 col-md-4 col-">
                    <div class="cart-detail">
                        <h4 class="wborder col-12">TOTAL</h4>
                        <div class="cart-content">
                            <div class="item delivery">
                                <div class="name col-4">Subtotal</div>
                                <div class="price col-8">{{dinero(Cart::subtotal())}}</div>
                            </div>
                        </div><!--/cart-content-->
                        <div class="deliveries">
                            <div class="item">
                                <div class="name">Envío</div><div class="price">{{dinero(Session::get('shipping'))}}</div>
                            </div>
                            <div class="col-md-12">
                                <div class="name"><small style="font-size:13px">Tiempo de entrega: {{Session::get('shipping_description')}}</small></div>
                            </div>
                            @if($free_shipment)
                                @if($free_shipment->value =='ON')
                                    <div style="color:#5aa407;padding:0 20px 20px;"><b>Envío gratis</b> en compras mayores a {!!dinero($amount->value)!!}</div>
                                @endif
                            @endif
                            <!--<div id="accordion" role="tablist">
                                    <div class="card">
                                        <div class="card-header" role="tab" id="headingOne">
                                            <h5 class="mb-0">
                                                <a data-toggle="collapse" href="#collapseOne" role="button" aria-expanded="true" aria-controls="collapseOne">
                                                <input class="form-check-input" type="radio" name="metodo1" id="metodo1" value="1" checked>
                                                Método 1
                                                <div class="arrow"></div>   
                                                </a>
                                            </h5>
                                        </div>

                                        <div id="collapseOne" class="collapse show" role="tabpanel" aria-labelledby="headingOne" data-parent="#accordion">
                                        <div class="card-body">
                                            Anim pariatur cliche reprehenderit, enim eiusmod high life accusamu</div>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-header" role="tab" id="headingTwo">
                                        <h5 class="mb-0">
                                            <a class="collapsed" data-toggle="collapse" href="#collapseTwo" role="button" aria-expanded="false" aria-controls="collapseTwo">
                                            <input class="form-check-input" type="radio" name="metodo1" id="metodo1" value="1" checked>
                                            Método 2
                                            <div class="arrow"></div>   
                                            </a>
                                        </h5>
                                        </div>
                                        <div id="collapseTwo" class="collapse" role="tabpanel" aria-labelledby="headingTwo" data-parent="#accordion">
                                        <div class="card-body">
                                            Anim pariatur cliche reprehenderit, enim eiusmod high life accusamu</div>
                                        </div>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-header" role="tab" id="headingThree">
                                        <h5 class="mb-0">
                                            <a class="collapsed" data-toggle="collapse" href="#collapseThree" role="button" aria-expanded="false" aria-controls="collapseThree">
                                            <input class="form-check-input" type="radio" name="metodo1" id="metodo1" value="1" checked>
                                            Método 3
                                            <div class="arrow"></div>   
                                            </a>
                                        </h5>
                                        </div>
                                        <div id="collapseThree" class="collapse" role="tabpanel" aria-labelledby="headingThree" data-parent="#accordion">
                                        <div class="card-body">
                                            Anim pariatur cliche reprehenderit, enim eiusmod high life accusamu</div>
                                        </div>
                                    </div>
                            </div>-->
                        <div class="total"> 
                            <div class="name">Total</div>
                            <div class="price">{{dinero(Session::get('total')) }}</div>
                        </div>
                        {!! $shoppingcart->present()->registerToCheckOut() !!}
                        <a href="/tienda" class="btn btn-default btn-block">SEGUIR COMPRANDO</a>
                    </div>
                </div><!--col-3-->                
                @endif
            </div>
        </div>
</section>
@stop

@section('js')
<script>
 $('.collapse').on('show.bs.collapse', function () {
    $(this).siblings('.card-header').addClass('active');
  });

  $('.collapse').on('hide.bs.collapse', function () {
    $(this).siblings('.card-header').removeClass('active');
  });
   $(".frmCart").on( "click","a.btnDelete", function() {
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
            if(result.value){   
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