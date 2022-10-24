@extends('layouts.website')

@section('content')

    <div id="wrap" class="mt-5">
        <div id="main-body">
            <div id="content">
                <div class="grid">
                    <div class="grid__item two-thirds">
                        <div id="js-pdp-img" class="pdp__img">
                            <div class="">
                                <div class="pdp__img__no-frame zoom-trigger ">
                                    <div class="pdp__img__img-wrapper">
                                        <div class="icon-hero-image-video pdp__hero-image-video-icon hidden js-hero-video-icon"></div>
                                        <img id="default-photo" class="pdp__img__default lazy" src="{{ $product->main_image() }}" style="display: inline-block;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="js-pdp-desc" class="grid__item one-third pdp__desc--core">
                        <div id="js-pdp-desc-wrapper" class="pdp__desc__inner-wrapper">
                            <div class="pdp__desc__display-name--core">
                                <h1>{{$product->name}}</h1>
                            </div>

                            <div class="pdp__desc__price--core js-pdp-desc-price">
                                @if($product->liquidado == 1)
                                    <div class="product-price__price">
                                        <span class="product-price__amount">
                                            <span class="currency-label"></span><!-- to remove spaces between spans-->
                                            <span class="price-currency">$</span>
                                            @if($product->liquidado_price > 0 )
                                                <s>{{$product->regular_price}}</s> 
                                            @endif
                                        </span>
                                        <span class="product-price__label">Precio Regular</span>
                                        </div>
                                        @if($product->liquidado_price > 0)
                                            <div class="product-price__price text-danger">
                                                <span class="product-price__amount--member">
                                                    <span class="currency-label"></span><!-- to remove spaces between spans-->
                                                    <span class="price-currency">$</span>
                                                    {{$product->liquidado_price}}
                                                </span>
                                                <span class="product-price__label--member">Precio Liquidación</span>
                                            </div>
                                        @endif
                                @else                 
                                    @if($product->offer_price && $product->offer > 0)
                                        <div class="product-price__price">
                                            <span class="product-price__amount">
                                                <span class="currency-label"></span><!-- to remove spaces between spans-->
                                                <span class="price-currency">$</span>
                                                <s>{{$product->regular_price}}</s> 
                                            </span>
                                            <span class="product-price__label">Precio Regular</span>
                                        </div>
                                        <div class="product-price__price">
                                            <span class="product-price__amount">
                                                <span class="currency-label"></span><!-- to remove spaces between spans-->
                                                <span class="price-currency">$</span>
                                                @if($product->liquidado_price > 0 )
                                                    {{$product->offer_price}}
                                                @endif
                                            </span>
                                            <span class="product-price__label">Precio Oferta</span>
                                        </div>
                                    @else
                                        <div class="product-price__price">
                                            <span class="product-price__amount">
                                                <span class="currency-label"></span><!-- to remove spaces between spans-->
                                                <span class="price-currency">$</span>
                                                {{$product->regular_price}}
                                            </span>
                                            <span class="product-price__label">Precio Regular</span>
                                        </div>
                                    @endif

                                    @if(Auth::guard('customer-web')->check())
                                        @if($price_customer != null)
                                            @if(($price_customer->price < $product->offer_price && $product->offer > 0) || ($price_customer->price < $product->regular_price))
                                                <div class="product-price__price">
                                                    <span class="product-price__amount--member">
                                                        <span class="currency-label"></span><!-- to remove spaces between spans-->
                                                        <span class="price-currency">$</span>
                                                            {{$price_customer->price}}
                                                        </span>
                                                    <span class="product-price__label--member">Precio Especial</span>
                                                </div>
                                            @endif
                                        @endif
                                    @endif
                                @endif
                            </div>
                            <div class="product-price__message"></div>
						</div>
                        <p class="pdp__desc__text--no-header--core js-pdp-desc-text">
                            {!! $product->description !!}
                        </p>
                        <h4 class="pdp-std__desc__header--rollup--core js-pdp-header js-toggle" id="details">
                            Ver Mas Detalles +
                        </h4>
                        <div class="rollup" id="features" hidden>
                            {!! $product->specifications !!}
                        </div>
                        <div class="item-controls">
                            <fieldset class="line-item__item-qty"><label class="label">Cant.</label>
                                <select class="form-control form-control-sm" id="quantity" name="quantity" data-maxval="40">
                                    <option value="1" selected="selected">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="6">6</option>
                                    <option value="7">7</option>
                                    <option value="8">8</option>
                                    <option value="9">9</option>
                                    <option value="10">10</option>
                                    <option value="11">11</option>
                                    <option value="12">12</option>
                                    <option value="13">13</option>
                                    <option value="14">14</option>
                                    <option value="15">15</option>
                                    <option value="16">16</option>
                                    <option value="17">17</option>
                                    <option value="18">18</option>
                                    <option value="19">19</option>
                                    <option value="20">20</option>
                                    <option value="21">21</option>
                                    <option value="22">22</option>
                                    <option value="23">23</option>
                                    <option value="24">24</option>
                                    <option value="25">25</option>
                                    <option value="26">26</option>
                                    <option value="27">27</option>
                                    <option value="28">28</option>
                                    <option value="29">29</option>
                                    <option value="30">30</option>
                                    <option value="31">31</option>
                                    <option value="32">32</option>
                                    <option value="33">33</option>
                                    <option value="34">34</option>
                                    <option value="35">35</option>
                                    <option value="36">36</option>
                                    <option value="37">37</option>
                                    <option value="38">38</option>
                                    <option value="39">39</option>
                                    <option value="40">40</option>
                                </select>
                            </fieldset>
                            <fieldset class="line-item__item-add">
                                    <button class="js-item-add-button js-item-add-primary pdp-cart-btn__inline button--primary add-to-cart" data-list-type="standard" data-product-id="prod2170124">Agregar a Pedido</button>
                            </fieldset>
                        </div>
                        @if(Auth::guard('customer-web')->check())
                        <div class="item-controls mt-2">
                            <fieldset class="line-item__item-qty"><label class="label">Cant.</label>
                                <select class="form-control form-control-sm" id="quantity2" name="quantity2" data-maxval="40">
                                    <option value="1" selected="selected">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="6">6</option>
                                    <option value="7">7</option>
                                    <option value="8">8</option>
                                    <option value="9">9</option>
                                    <option value="10">10</option>
                                    <option value="11">11</option>
                                    <option value="12">12</option>
                                    <option value="13">13</option>
                                    <option value="14">14</option>
                                    <option value="15">15</option>
                                    <option value="16">16</option>
                                    <option value="17">17</option>
                                    <option value="18">18</option>
                                    <option value="19">19</option>
                                    <option value="20">20</option>
                                    <option value="21">21</option>
                                    <option value="22">22</option>
                                    <option value="23">23</option>
                                    <option value="24">24</option>
                                    <option value="25">25</option>
                                    <option value="26">26</option>
                                    <option value="27">27</option>
                                    <option value="28">28</option>
                                    <option value="29">29</option>
                                    <option value="30">30</option>
                                    <option value="31">31</option>
                                    <option value="32">32</option>
                                    <option value="33">33</option>
                                    <option value="34">34</option>
                                    <option value="35">35</option>
                                    <option value="36">36</option>
                                    <option value="37">37</option>
                                    <option value="38">38</option>
                                    <option value="39">39</option>
                                    <option value="40">40</option>
                                </select>
                            </fieldset>
                            <fieldset class="line-item__item-add">
                                    <button class="js-item-add-button js-item-add-primary pdp-cart-btn__inline button--primary add-to-programming" data-list-type="standard" data-product-id="prod2170124">Agregar a Programación</button>
                            </fieldset>
                        </div>
                        @endif
                        <div class="js-trigger-anchor">
                            <div id="js-pdp-panel" class="pdp-std__panel--core js-panel-anchor">
			                    <div id="js-swatch-container" class="pdp__panel__swatches-container">
		                        </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="twelve" id="line-items">
                    <h3 class="pdp__line-item__display-name--core js-item-product-name mt-5"> ARTICULOS SIMILARES</h3>
                    <hr/>
                    @foreach($related_products as $r_product)
                        <div class="group-notflush-bottom group-notflush-top group full js-line-item pdp__line-item--core" id="lineitem-prod2170124" data-productid="prod2170124" data-is-cog="true" data-sku-id="cog-sku3480003" data-full-sku-id="57070708 CLCH" data-colorizable="true">
                            <div class="grid">
                                <div class="grid__item one-sixth">
                                    <div class="js-line-item__image">
                                        <img class="pdp__line-item__thumbnail lazy" src="{{$r_product->image}}" alt="{{$r_product->name}}">
                                    </div>
                                </div>
                                <div class="grid__item five-sixths">
                                    <div class="js-item-desc">
                                        <div class="group group-notflush-bottom">
                                            <h3 class="pdp__line-item__display-name--core js-item-product-name">
                                                <a href="{{ route('product-detail', ['id' => $r_product->id,'slug' => $r_product->slug]) }}">{{$r_product->name}}</a>
                                            </h3>
                                            <div class="pdp__line-item__price">
                                                <div class="js-pdp-line-item-price">
                                                    <div class="product-price--table">
                                                    @if($r_product->liquidado == 1)
                                                        <div class="product-price__price">
                                                            <span class="product-price__amount">
                                                                <span class="currency-label"></span><!-- to remove spaces between spans-->
                                                                <span class="price-currency">$</span>
                                                                @if($r_product->liquidado_price > 0 )
                                                                    <s>{{$r_product->regular_price}}</s> 
                                                                @endif
                                                            </span>
                                                            <span class="product-price__label">Precio Regular</span>
                                                            </div>
                                                            @if($r_product->liquidado_price > 0)
                                                                <div class="product-price__price text-danger">
                                                                    <span class="product-price__amount--member">
                                                                        <span class="currency-label"></span><!-- to remove spaces between spans-->
                                                                        <span class="price-currency">$</span>
                                                                        {{$r_product->liquidado_price}}
                                                                    </span>
                                                                    <span class="product-price__label--member">Precio Liquidación</span>
                                                                </div>
                                                            @endif
                                                    @else                 
                                                        @if($r_product->offer_price && $r_product->offer > 0)
                                                            <div class="product-price__price">
                                                                <span class="product-price__amount">
                                                                    <span class="currency-label"></span><!-- to remove spaces between spans-->
                                                                    <span class="price-currency">$</span>
                                                                    <s>{{$r_product->regular_price}}</s> 
                                                                </span>
                                                                <span class="product-price__label">Precio Regular</span>
                                                            </div>
                                                            <div class="product-price__price">
                                                                <span class="product-price__amount">
                                                                    <span class="currency-label"></span><!-- to remove spaces between spans-->
                                                                    <span class="price-currency">$</span>
                                                                    @if($r_product->liquidado_price > 0 )
                                                                        {{$r_product->offer_price}}
                                                                    @endif
                                                                </span>
                                                                <span class="product-price__label">Precio Oferta</span>
                                                            </div>
                                                        @else
                                                            <div class="product-price__price">
                                                                <span class="product-price__amount">
                                                                    <span class="currency-label"></span><!-- to remove spaces between spans-->
                                                                    <span class="price-currency">$</span>
                                                                    {{$r_product->regular_price}}
                                                                </span>
                                                                <span class="product-price__label">Precio Regular</span>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    </div>
                                                </div>
                                                <div class="bs-promo-message"></div>
                                            </div>
                                            <div class="merch-message js-merch-message"></div>
                                        </div>
                                    </div>
                                    <div class="item-info line-item__info">
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
<script type="text/javascript" src="{{ asset('/website/plugins/slick/slick/slick.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/website/plugins/VenoBox-master/venobox/venobox.min.js')}}"></script>
<script>
$(document).ready(function() {
    $("#details").on("click", () => {
        if($("#features").prop( "hidden" )){
            $("#features").prop("hidden",false);
        }else{
            $("#features").prop("hidden",true);
        }   
    });

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
            'value': $('#quantity').val(),
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
            'value': $('#quantity2').val(),
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