@extends('layouts.website')
@section('extra-head')
    <link rel="stylesheet" href="{{ asset('/website/css/products-menu.css') }}">
    <link rel="stylesheet" href="{{ asset('/website/css/product-list.css') }}">
@stop
@section('content')
<?php
$query = $_GET;
$query['view'] = 'list';
$list_url = http_build_query($query);
$query['view'] = 'grid';
$grid_url = http_build_query($query);
?>
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                @include('layouts.includes.products-menu')
            </div>
            <div class="col-md-12 mobile">
                <nav aria-label="breadcrumb" role="navigation">
                    <div class="container">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('category-products')}}">Tienda</a></li>
                            <li class="breadcrumb-item"><a href="{{route('category-products', ['slug'=> $slug])}}">{{$slug or ''}} {{$child}}</a></li>
                        </ol>
                    </div>
                </nav>
            </div>
            <div class="col-md-9 page-search">
                <nav class="navbar d-flex align-items-stretch" style="height:50px;">
                    <div class="d-flex flex-row align-items-center">
                        <div class="d-none d-sm-block"><a href="{{ route('category-products',Route::current()->parameters()).'?'.$grid_url }}" class="btn @if(!isset($_GET['view']) || $_GET['view'] == 'grid') btn-outline-success @else btn-outline-default @endif" id="btngrid"><img src="{{ asset('/website/img/hec_grid.svg') }}" class="inline-svg"></a></div>
                        <div class="d-none d-sm-block" style="margin:0 15px;"><a href="{{ route('category-products',Route::current()->parameters()).'?'.$list_url}}" class="btn @if(isset($_GET['view']) && $_GET['view'] == 'list') btn-outline-success @else btn-outline-default @endif" id="btnlist"><img src="{{ asset('/website/img/hec_list.svg') }}" class="inline-svg"></a></div>
                        <div class="d-none d-sm-block">
                            <select class="form-control sltOrderProducts" placeholder="Ordenar por:">
                                @foreach($filterOrderBy as $value => $text)
                                    <option value="{{$value}}" @if(isset($_GET['orderby']) && $_GET['orderby'] == $value) selected @endif>{{$text}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="d-flex flex-row align-items-center"><span class="navbar-text">Mostrando <b>{{($products->currentPage()-1)*12+$products->count()}}</b> de <b>{{$products->total()}}</b></span></div>
                </nav>
                @if($products->count()>0)
                    <div class="row products-grid @if(isset($_GET['view']) && $_GET['view'] == 'list') products-list @endif">
                        @foreach($products as $product)
                            @include('layouts.includes.product-item')
                        @endforeach
                    </div>
                @else
                  <div class="col-12 col-sm-12 d-flex flex-row align-items-center border no-products mt-4">
                        <div class="p-3"><img src="/website/img/sin-prods_busqueda.png"></div>
                        <div class="d-flex flex-column">
                            <h4 class="font-weight-bold">No se encontraron productos</h4>
                            <p>Intenta con otra búsqueda.</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        @if($products->total()>12)
        <div class="row">  
            <div class="col-md-12">
                <nav class="navbar paginado" aria-label="Page navigation example">
                    <span class="mostrando">Mostrando <b>{{($products->currentPage()-1)*12+$products->count()}}</b> de <b>{{$products->total()}}</b></span>                    
                    <a href="{{ $products->appends($_GET)->previousPageUrl() }}" class="previousBtn" @if($products->currentPage()==1) style="visibility:hidden" @endif><i class="la la-angle-left"></i>Anterior</a>
                    {{ $products->appends($_GET)->links() }}
                    <a href="{{ $products->appends($_GET)->nextPageUrl() }}" class="nextBtn" @if(!$products->hasMorePages()) style="visibility:hidden" @endif>Siguiente<i class="la la-angle-right"></i></a>
                </nav>
            </div>          
        </div>
        @endif        
    </div>
    @include('layouts.includes.mobile.product-filters')
    @include('layouts.includes.suscribe')
    @include('layouts.includes.brands')
@stop

@section('js')
<script src="/js/maskmoney/src/jquery.maskMoney.js" type="text/javascript"></script>
<script type="text/javascript">
//$('input[name="minprice"],input[name="maxprice"]').maskMoney();
$('.sltOrderProducts').change(function(){
    if(!$('input[name="orderby"]').length){
        $('<input>').attr({
            type: 'hidden',
            name: 'orderby'
        }).appendTo('.frmMenuSearch');
    }
    $('input[name="orderby"]').val($(this).val());
    $('.frmMenuSearch').submit();
});

$('.btnFilter').click(function(){
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

    if(!$('.frmMenuSearch input[name="maxprice"]').length){
        $('<input>').attr({
            type: 'hidden',
            name: 'maxprice'
        }).appendTo('.frmMenuSearch');
    }

    if(!$('.frmMenuSearch input[name="minprice"]').length){
        $('<input>').attr({
            type: 'hidden',
            name: 'minprice'
        }).appendTo('.frmMenuSearch');
    }
    
    $('.frmMenuSearch input[name="maxprice"]').val($('.section.prices input[name="maxprice"]').val())
    $('.frmMenuSearch input[name="minprice"]').val($('.section.prices input[name="minprice"]').val())

    if($(this).data('is-mobile')){
        $('.frmMenuSearch input[name="maxprice"]').val($('#mob-maxprice').val());
        $('.frmMenuSearch input[name="minprice"]').val($('#mob-minprice').val());
    }

    $('.frmMenuSearch').submit();
});

$(".toggle-list-product").on("click",function(e){
    e.preventDefault();
    var ul = $(this).siblings('ul');
    ul.toggle();
})

$(".btnMobileFilters").on("click",function(){
    $(".filter-options").css('visibility', 'visible');
})


$(".btnMobileOrder").on("click",function(){
    $(".order-options").css('visibility', 'visible');
})


</script>
@stop