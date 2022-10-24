@extends('layouts.website')
@section('extra-head')
    <meta name="twitter:card" content="summary" />
    <meta name="twitter:site" content="{{ENV('ENV_TWITTER_SITE')}}" />
    <meta name="twitter:creator" content="{{ENV('ENV_TWITTER_CREATOR')}}"/>    
    <meta name="pinterest-rich-pin" content="true" /><!--pinterest-->
    <meta property="product:price:amount" content="{{$product->final_price}}" /><!--pinterest-->
    <meta property="product:price:currency" content="MXN" /><!--pinterest-->
    <meta property="og:url" content="{{route('product-detail', [$product->id, $product->slug])}}" />
    <meta property="og:title" content="{{$product->name }}" />
    <meta property="og:type"   content="website" /> 
    <meta property="og:description"  content="Conoce nuestros productos y promociones!">
    <meta property="og:image" content="{{ $product->main_image()}}" />
    <meta property="og:site_name" content="Home Express Center" />
    <link rel="stylesheet" type="text/css" href="{{ asset('/website/css/product-detail.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/website/plugins/slick/slick/slick.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('/website/plugins/slick/slick/slick-theme.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('/website/plugins/VenoBox-master/venobox/venobox.css')}}" media="screen" />
@stop

@section('content')
    <nav aria-label="breadcrumb" role="navigation">
        <div class="container">
            <ol class="breadcrumb">
               <li class="breadcrumb-item"><a href="{{route('category-products')}}">Tienda</a></li>
                @php($slug = '')
                @foreach($product->breadcrumb() as $breadcrumb)
                @if($slug == '')
                @php($slug = $slug.$breadcrumb->slug)
                @else
                    @php($slug = $slug."/".$breadcrumb->slug)
                    
                @endif
                <li class="breadcrumb-item"><a href="{{route('category-products', ['slug'=> $slug])}}">{{$breadcrumb->name }}</a></li>
                @endforeach
               <li class="breadcrumb-item active" aria-current="page">{{$product->name}}</li>
            </ol>
        </div>
    </nav>
    <?php
    ?>
    <div class="container m-top-40">
        @if(Session::has('flash_message'))
            <div class="alert alert-{{Session::get('flash_type')}} alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button><strong>{{Session::get('flash_title')}}</strong> {!!Session::get('flash_message')!!}</div>
        @endif
        <div class="row detail">
            <div class="col-md-6" style="overflow:hidden">
                <div class="big-image-carousel">
                    @if($product->image)
                        <a class="img_popup" href="{{ $product->main_image() }}" data-gall="myGallery" data-title="{{htmlspecialchars($product->name)}}" >
                        <div><img src="{{ $product->main_image() }}" alt="{{$product->name}}"  class="main-image"></div>
                        </a>
                        @foreach($product->images as $image)  
                            <a class="img_popup" href="{{$image->path}}" data-gall="{{$product->slug}}" data-title="{{htmlspecialchars($product->name)}}">
                            <div><img src="{{$image->path}}" alt="{{$product->name}}"  class="main-image"></div>
                            </a>
                        @endforeach
                    @endif
                </div>
                <div class="multiple-items">
                    @if($product->image)
                    <!--data-title="{{htmlspecialchars($product->name)}}-->
                        <div><img src="{{ $product->main_image() }}" alt="{{$product->name}}"  class="main-image"></div>
                        @foreach($product->images as $image)
                            <div><img src="{{$image->path}}" alt="{{$product->name}}"  class="main-image"></div>
                        @endforeach
                    @endif
                </div>
            </div>
            <div class="col-md-6">
                <h1>{{$product->name}}</h1>
                <select class="averageRating" id="rating">
                    <option value=""></option>
                    @for( $i = 1 ; $i < 6 ; $i++)
                        <option value="{{ $i }}" @if ($i == $product->rating) selected @endif >{{ $i }}</option>
                    @endfor
                </select> 

                <br>

                @if ( $valorar > 0 )
                    <button class="btn btn-primary" id="btn-evalua"> <i class="fa fa-star"></i> Evalua este producto</button>
                @endif

                <div class="description">
                    {!! $product->description !!}
                </div>
                
                @if($product->liquidado == 1)
                    <div class="regular_price @if($product->liquidado_price > 0 ) withOffer @endif">${{$product->regular_price}} <span class="currency">MXN</span></div>
                    @if($product->liquidado_price > 0)
                        <div class="offer_price">${{$product->liquidado_price}} <span class="currency">MXN</span></div>
                    @endif
                @else
                    <div class="regular_price @if($product->offer_price && $product->offer > 0) withOffer @endif">${{$product->regular_price}} <span class="currency">MXN</span></div>
                    @if($product->offer_price && $product->offer > 0)
                        <div class="offer_price">${{$product->offer_price}} <span class="currency">MXN</span></div>
                    @endif

                    @if(Auth::guard('customer-web')->check())
                        @if($price_customer != null)
                            @if(($price_customer->price < $product->offer_price && $product->offer > 0) || ($price_customer->price < $product->regular_price))
                                <div class="h5 font-weight-bold mt-2 text-primary"> Precio Especial: ${{$price_customer->price}} MXN</div>
                            @endif  
                        @endif   
                    @endif
                @endif

                @if($product->stock)

                    <div>
                         Disponibles <b> {{ $product->stock }} </b>
                    </div>

                <div class="options">
                    <div class="quantity">
                        <div class="input-group number-input">
                            <span class="input-group-addon product-rest">-</span>
                            <input type="number" class="form-control" name="quantity" aria-label="Quantity" aria-describedby="quantity" value="1" min="1" max="{{ $maxQty }}">
                            <span class="input-group-addon product-add">+</span>
                        </div>
                    </div>
                    <a href="#" class="green add-to-cart">
                        <img src="{{ asset('/website/img/hec_order.svg') }}" class="inline-svg svg-white" alt="Agregar"><span>Agregar a Pedido</span>
                    </a>
                    @if(Auth::guard('customer-web')->check())
                        <a href="#" class="green add-to-programming">
                            <img src="{{ asset('/website/img/hec_order_programming.svg') }}" class="inline-svg svg-white" alt="Agregar"><span>Agregar a Programación</span>
                        </a>
                    @endif
                    <!--<a href="#" class="refresh">
                        <img src="{{ asset('/website/img/hec_refresh.svg') }}" class="inline-svg svg-green" alt="Actualizar">
                    </a>-->
                    <a href="#" class="favorite void @if($product->myfavorite()) myfavorite @endif"  data-toggle="tooltip" title=" @if($product->myfavorite()) Eliminar de favoritos @else Agregar a Favoritos @endif">
                        <img src="{{ asset('/website/img/hec_fav.svg') }}" class="inline-svg svg-green" alt="Favorito">
                    </a>
                </div>
                @else
                <div class="text-danger pt-2"><span>Producto agotado</span></div>
                @endif

                <!-- aqui la valoración -->
                
                <div class="share">
                    <div class="text">Compartir en:</div>
                    <ul class="list-inline">
                        @foreach($product->shareLinks() as $social => $data)
                        <li class="list-inline-item">
                            <a href="{{$data['url']}}" class="item" @if($data['target'])target="_blank"@else target="_self"@endif>
                                <i class="fa fa-{{$data['icon']}}"></i>{{$data['label']}}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="container m-top-40 details">
        <div class="row">
            <div class="col-12">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Información</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Especificaciones</a>
                    </li>

                    
                    <li class="nav-item">
                        <a class="nav-link" id="rating-tab" data-toggle="tab" href="#ratingTab" role="tab" aria-controls="ratingTab" aria-selected="false">Calificaciones</a>
                    </li>
                    
                    </ul>
                    <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                        {!! $product->description !!}
                    </div>
                    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        {!! $product->specifications !!}
                    </div>
                    
                    <div class="tab-pane fade" id="ratingTab" role="tabpanel" aria-labelledby="rating-tab">
                        @include('layouts.includes.rating')
                    </div>
                    
                </div>
            </div>
        </div>

         <div class="row">
            <div class="col-12">
                @include('layouts.includes.quenstion-answer')
            </div>
        </div>


    </div>
    <!--@include('layouts.includes.questions')-->
    <div class="recomended m-top-100">
        <div class="container">
            <div class="products-grid">
                <h1 class="title">Productos similares</h1>
                <div class="owl-carousel owl-theme">
                    @foreach($related_products as $r_product)
                        <div class="col-md-12">
                            <a href="{{ route('product-detail', ['id' => $r_product->id,'slug' => $r_product->slug]) }}">
                                <div class="item">
                                    <div class="image">
                                        <img src="{{$r_product->image}}" alt="{{$product->name}}" class="img-fluid">
                                    </div>
                                    @if($r_product->getOfferPercent() > 0)
                                    <div class="discount">
                                           - {{$r_product->getOfferPercent()}} %
                                    </div>
                                    @endif
                                    <div class="description">{{$r_product->name}}</div>
                                        <div class="text-center">
                                            <select class="averageRating" id="rating">
                                                <option value=""></option>
                                                @for( $i = 1 ; $i < 6 ; $i++)
                                                    <option value="{{ $i }}" @if ($i == $r_product->rating) selected @endif >{{ $i }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                    <div class="prices">
                                        <div class="col label-price">Precio</div>                    
                                        <div class="col regular_price @if($r_product->offer_price && $product->offer > 0) withOffer @endif">${{$r_product->regular_price}} <span class="currency">MXN</span></div>
                                        @if($r_product->offer_price && $product->offer > 0)
                                            <div class="col offer_price">${{$r_product->offer_price}} <span class="currency">MXN</span></div>
                                        @endif 
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    @include('layouts.includes.suscribe')
    @include('layouts.includes.brands')
    @if ( $valorar > 0 )
        @include('layouts.includes.you-rating')
    @endif

@stop

@section('js')
<script type="text/javascript" src="{{ asset('/website/plugins/slick/slick/slick.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/website/plugins/VenoBox-master/venobox/venobox.min.js')}}"></script>
<script>
$(document).ready(function() {


    $("#btn-evalua").on("click",function(){
        $("#modal-rating").modal("show");
    });


    $('.img_popup').venobox({
         spinColor: '#5aa407',
         spinner: 'three-bounce',
         titleattr: 'data-title',
         titlePosition:'bottom'
    });

    $( "body" ).click(function( event ) {
        if($(event.target).attr('class') === 'vbox-container'){
            $(".vbox-close").click();
        }
    });

});

    $("#btn-save-rating").on("click",function(){
        if($("#valor").val() != "" && $("#review").val() != "" ){
            $("#form-rating").submit();
        }else{
            alert("Recuerde que debe seleccionar una estrella cuando menos y escribir una reseña para poder guardar");
        }
    });

    $('.favorite').click(function() {
        event.preventDefault();
        $(this).find('img').css('display','none').append('<i class="fa fa-spinner fa-spin"></i>');
        $.ajax({
            method: "GET",
            url: "{{route('update-favorites',['product' => $product->id])}}",
          }).done(function(result) {
            if(result.is_logged){
                if(result.isFavorite === true){                
                    $('.favorite').addClass('myfavorite');                    
                    $('.favorite').attr('data-original-title','Eliminar de favoritos');            
                }else{
                    $('.favorite').removeClass('myfavorite');                
                    $('.favorite').attr('data-original-title','Agregar a favoritos');
                }
            }else{
                $('.modalLogin').trigger('click');
            }
        });
    })
    $('.add-to-cart').click(function(){
        event.preventDefault();
        var newForm = jQuery('<form>', {
            'action': "{{route('add-product-to-cart',['product' => $product->id])}}",
            'method': 'POST'
        }).append(jQuery('<input>', {
            'name': '_token',
            'value': "{{ csrf_token() }}",
            'type': 'hidden'
        })).append(jQuery('<input>', {
            'name': 'quantity',
            'value': $('input[name="quantity"]').val(),
            'type': 'hidden'
        }));
        newForm.appendTo('body').submit();
        
        $('#frmCartItem').submit();
    });
    
    //GCUAMEA
    $('.add-to-programming').click(function(){
        event.preventDefault();
        var newForm = jQuery('<form>', {
            'action': "{{route('add-product-to-programming',['product' => $product->id])}}",
            'method': 'POST'
        }).append(jQuery('<input>', {
            'name': '_token',
            'value': "{{ csrf_token() }}",
            'type': 'hidden'
        })).append(jQuery('<input>', {
            'name': 'quantity',
            'value': $('input[name="quantity2"]').val(),
            'type': 'hidden'
        }));
        newForm.appendTo('body').submit();
        
        $('#frmCartItem').submit();
    });

    $('.big-image-carousel').slick({
        infinite: true,
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: true,
        fade: true,
        asNavFor: '.multiple-items'
    });

    $('.multiple-items').slick({
        infinite: true,
        slidesToShow: 3,
        slidesToScroll: 1,
        asNavFor: '.big-image-carousel',
        focusOnSelect: true,
        centerMode: true,
    });

    $('.owl-carousel').owlCarousel({
        loop:true,
        margin:10,
        nav:true,
        autoplay: true,
        navText: ['<i class="fa fa-angle-left"></i>','<i class="fa fa-angle-right"></i>'],
        responsive:{
            0:{
                items:1
            },
            600:{
                items:2
            },
            992:{
                items:3
            },
            1200:{
                items:4
            }
        }
    });

    $(".averageRating").barrating('show', {
        theme: 'fontawesome-stars',
        readonly: true,
        onSelect: function(value, text, event) {
            if (typeof(event) !== 'undefined') {
            // rating was selected by a user
            console.log(event.target);
            } else {
            // rating was selected programmatically
            // by calling `set` method
            }
        }
    });

    $(".rateInput").barrating('show', {
        theme: 'fontawesome-stars',
        onSelect: function(value, text, event) {
            if (typeof(event) !== 'undefined') {
            // rating was selected by a user
            console.log(event.target);
            } else {
            // rating was selected programmatically
            // by calling `set` method
            }
        }
    });
</script>
@stop