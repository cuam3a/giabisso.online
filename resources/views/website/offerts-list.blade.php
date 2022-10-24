@extends('layouts.website')

@section('content')
<?php
$query = $_GET;
$query['view'] = 'list';
$list_url = http_build_query($query);
$query['view'] = 'grid';
$grid_url = http_build_query($query);
?>

<div id="wrap" class="mt-5" style=" z-index:1; position:relative">
	<div class="one-whole mb-2"><h1 class="text-center"><i class="fa fa-tag"></i>   PRODUCTOS EN OFERTA</h1></div>
    <hr/>
    <div id="content" class="grid">
        @if($products->count()>0)

        @foreach($products as $product)
        <div class="grid__item one-third product-cell__container" style="height:450px">
            <div class="product-cell--plain" data-productid="prod7750078" data-type="" data-skuid="" data-swatchid="">
                <a name="prod7750078"
                    href="{{ route('product-detail', ['id' => $product->id,'slug' => $product->slug]) }}">
                    <div class="product-cell__image-container--plain" style="height: 300px; align-content:center">
                        <div class="js-line-item__image" style="height: 100%;">
                            <img class="pdp__line-item__thumbnail lazy" src="{{$product->image}}"
                                alt="MANGO DE REPUESTO PARA HACHA MICHIGAN 34 3/4 DE PULGADA" style="height: 100%;">
                        </div>
                    </div>
                </a>
            </div>
            <div class="product-cell__title--plain--rh product-cell__title--gallery">
                <a href="{{ route('product-detail', ['id' => $product->id,'slug' => $product->slug]) }}">
                    <div style="height:40px">{{ $product->name }}</div>
                    <div class="price product-cell__price">
                        <div class="product-price--table">
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
                                @if($product->getOfferPercent() > 0)
                                <span class="text-danger">- {{$product->getOfferPercent()}} %</span>
                                @endif
                            </div>

                            <div class="product-price__price">
                                <span class="product-price__amount text-danger">
                                    <span class="currency-label"></span><!-- to remove spaces between spans-->
                                    <span class="price-currency">$</span>
                                    @if($product->offer_price > 0 )
                                    {{$product->offer_price}}
                                    @endif
                                </span>
                                <span class="product-price__label">Precio Oferta
                                </span>
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
                            @foreach($price_customer as $item)
                            @if($item->product_id == $product->id)
                            @if(($item->price < $product->offer_price && $product->offer > 0) || ($item->price <
                                    $product->regular_price))
                                    <div class="product-price__price">
                                        <span class="product-price__amount--member">
                                            <span class="currency-label"></span><!-- to remove spaces between spans-->
                                            <span class="price-currency">$</span>
                                            {{$item->price}}
                                        </span>
                                        <span class="product-price__label--member">Precio Especial</span>
                                    </div>
                                    @endif
                                    @endif
                                    @endforeach
                                    @endif
                                    @endif
                                    @endif

                        </div>
                        <div class="product-price__message">
                        </div>
                    </div>
                </a>
            </div>
        </div>
        @endforeach

        @else
        <div class="grid__item one-whole product-cell__container text-center" style="height:350px">
            <div class="one-whole">
                <div class="p-3"><img src="/website/img/sin-prods_busqueda.png"></div>
                <h1 class="text-center">No se encontraron productos</h1>
            </div>
        </div>
        @endif
    </div>
    @if($products->total()>12)
    <div class="row">
        <div class="col-md-12">
            <nav class="navbar paginado" aria-label="Page navigation example">
                <span class="mostrando">Mostrando <b>{{($products->currentPage()-1)*12+$products->count()}}</b> de
                    <b>{{$products->total()}}</b></span>
                <a href="{{ $products->appends($_GET)->previousPageUrl() }}" class="previousBtn"
                    @if($products->currentPage()==1) style="visibility:hidden" @endif><i
                        class="la la-angle-left"></i>Anterior</a>
                {{ $products->appends($_GET)->links() }}
                <a href="{{ $products->appends($_GET)->nextPageUrl() }}" class="nextBtn" @if(!$products->hasMorePages())
                    style="visibility:hidden" @endif>Siguiente<i class="la la-angle-right"></i></a>
            </nav>
        </div>
    </div>
    @endif
</div>
@stop

@section('js')
<script src="/js/maskmoney/src/jquery.maskMoney.js" type="text/javascript"></script>
<script type="text/javascript">
//$('input[name="minprice"],input[name="maxprice"]').maskMoney();
$('.sltOrderProducts').change(function() {
    if (!$('input[name="orderby"]').length) {
        $('<input>').attr({
            type: 'hidden',
            name: 'orderby'
        }).appendTo('.frmMenuSearch');
    }
    $('input[name="orderby"]').val($(this).val());
    $('.frmMenuSearch').submit();
});

$('.btnFilter').click(function() {
    // if($('input[name="mob-maxprice"]').val() != ''){
    //     $('input[name="maxprice"]').val($('input[name="mob-maxprice"]').val())
    // }

    // if($('input[name="mob-minprice"]').val() != ''){
    //     $('input[name="minprice"]').val($('input[name="mob-minprice"]').val())
    // }

    // if($('.frmMenuSearch input[name="minprice"],.frmMenuSearch input[name="maxprice"]').length>0){
    //     $('.frmMenuSearch input[name="minprice"]').replaceWith($('.section.prices input[name="minprice"]').clone());
    //     $('.frmMenuSearch input[name="maxprice"]').replaceWith($('.section.prices input[name="maxprice"]').clone());

    // }else{
    //     $('.section.prices input[name="minprice"]').clone().appendTo('.frmMenuSearch');
    //     $('.section.prices input[name="maxprice"]').clone().appendTo('.frmMenuSearch');
    // }

    if (!$('.frmMenuSearch input[name="maxprice"]').length) {
        $('<input>').attr({
            type: 'hidden',
            name: 'maxprice'
        }).appendTo('.frmMenuSearch');
    }

    if (!$('.frmMenuSearch input[name="minprice"]').length) {
        $('<input>').attr({
            type: 'hidden',
            name: 'minprice'
        }).appendTo('.frmMenuSearch');
    }

    $('.frmMenuSearch input[name="maxprice"]').val($('.section.prices input[name="maxprice"]').val())
    $('.frmMenuSearch input[name="minprice"]').val($('.section.prices input[name="minprice"]').val())

    if ($(this).data('is-mobile')) {
        $('.frmMenuSearch input[name="maxprice"]').val($('#mob-maxprice').val());
        $('.frmMenuSearch input[name="minprice"]').val($('#mob-minprice').val());
    }

    $('.frmMenuSearch').submit();
});

$(".toggle-list-product").on("click", function(e) {
    e.preventDefault();
    var ul = $(this).siblings('ul');
    ul.toggle();
})

$(".btnMobileFilters").on("click", function() {
    $(".filter-options").css('visibility', 'visible');
})


$(".btnMobileOrder").on("click", function() {
    $(".order-options").css('visibility', 'visible');
})
</script>
@stop