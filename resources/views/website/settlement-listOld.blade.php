@extends('layouts.website')

@section('content')
<?php
$query = $_GET;
$query['view'] = 'list';
$list_url = http_build_query($query);
$query['view'] = 'grid';
$grid_url = http_build_query($query);
?>

<?php
    $customer_id = 0;
    if(Auth::guard('customer-web')->check()){
        $customer_id = Auth::guard('customer-web')->user()->id;
    }
    $orderProgrammerViews = new App\Models\OrderProgrammingViews;
    $categories = App\Models\Category::where('parent_id',null)->orderBy('order','ASC')->get();
    $categoriesList = new App\Models\Category;
    $params = Route::current() ? array_key_exists('id',Route::current()->parameters()) ? [] : Route::current()->parameters() : [];
?>

<div id="wrap" class="mt-5" style=" z-index:1; position:relative">
<div class="one-whole mb-2"><h1 class="text-center"><i class="fa fa-tag"></i>   PRODUCTOS EN LIQUIDACIÓN</h1></div>
    <hr/>
    <nav role="navigation" class="main-navigation top-level-subnav-border " style="z-index:2">
        <ul class="top-level-subnav shop-nav">
            <li class="trigger-subnav-category trigger-subnav-category-float ">
                <div class="subnav-Liquidacioncategory-text parent-li" style="font-size:10px">
                    <div class="subnav-category-underline"></div>
                    COCINA
                    @php($subcategories = $categoriesList->subCategoriesWithProductsAndStock2("COCINA"))
                    <div class="bottomlvl-cat-header-wrapper child-li border border-secondary"
                        style="visibility: hidden; height: 443px; z-index:3; position:absolute">
                        <ul style="height: 443px;">
                            @foreach($subcategories as $subcategory)
                            <li style="" class="bottomlvl-cat-header" id="cat160024-cat1481016" data-catid="cat1481016">
                                <a class="text-capitalize"
                                    href="{{route('settlement-products',['slug' => $subcategory->id ])}}">{{$subcategory->name}}</a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </li>
            <li id="cat160024" class="trigger-subnav-category trigger-subnav-category-float ">
                <div class="subnav-Liquidacioncategory-text parent-li" style="font-size:10px">
                    <div class="subnav-category-underline"></div>
                    LAVANDERIA
                    @php($subcategories = $categoriesList->subCategoriesWithProductsAndStock2("LAVANDERIA"))
                    <div class="bottomlvl-cat-header-wrapper child-li border border-secondary"
                        style="visibility: hidden; height: 443px;">
                        <ul style="height: 443px;">
                            @foreach($subcategories as $subcategory)
                            <li style="" class="bottomlvl-cat-header default-li-selected" id="cat160024-cat1481016"
                                data-catid="cat1481016">
                                <a class="text-capitalize"
                                    href="{{route('settlement-products',['slug' => $subcategory->id ])}}">{{$subcategory->name}}</a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </li>
            <li id="cat160024" class="trigger-subnav-category trigger-subnav-category-float">
                <div class="subnav-Liquidacioncategory-text parent-li" style="font-size:10px">
                    <div class="subnav-category-underline"></div>
                    CLOSETS
                    @php($subcategories = $categoriesList->subCategoriesWithProductsAndStock2("CLOSETS"))
                    <div class="bottomlvl-cat-header-wrapper child-li border border-secondary"
                        style="visibility: hidden; height: 443px;">
                        <ul style="height: 443px;">
                            @foreach($subcategories as $subcategory)
                            <li style="" class="bottomlvl-cat-header" id="cat160024-cat1481016" data-catid="cat1481016">
                                <a class="text-capitalize"
                                    href="{{route('settlement-products',['slug' => $subcategory->id ])}}">{{$subcategory->name}}</a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </li>
            <li id="cat160024" class="trigger-subnav-category trigger-subnav-category-float">
                <div class="subnav-Liquidacioncategory-text parent-li" style="font-size:10px">
                    <div class="subnav-category-underline"></div>
                    HERRAMIENTA
                    @php($subcategories = $categoriesList->subCategoriesWithProductsAndStock2("HERRAMIENTA"))
                    <div class="bottomlvl-cat-header-wrapper child-li border border-secondary"
                        style="visibility: hidden; height: 800px;">
                        <ul style="height: 800px;">
                            @foreach($subcategories as $subcategory)
                            <li style="" class="bottomlvl-cat-header" id="cat160024-cat1481016" data-catid="cat1481016">
                                <a class="text-capitalize"
                                    href="{{route('settlement-products',['slug' => $subcategory->id ])}}">{{$subcategory->name}}</a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </li>
            <li id="cat160024" class="trigger-subnav-category trigger-subnav-category-float">
                <div class="subnav-Liquidacioncategory-text parent-li" style="font-size:10px">
                    <div class="subnav-category-underline"></div>
                    AUTOMOTRIZ
                    @php($subcategories = $categoriesList->subCategoriesWithProductsAndStock2("AUTOMOTRIZ"))
                    <div class="bottomlvl-cat-header-wrapper child-li border border-secondary"
                        style="visibility: hidden; height: 550px;">
                        <ul style="height: 550px;">
                            @foreach($subcategories as $subcategory)
                            <li style="" class="bottomlvl-cat-header" id="cat160024-cat1481016" data-catid="cat1481016">
                                <a class="text-capitalize"
                                    href="{{route('settlement-products',['slug' => $subcategory->id ])}}">{{$subcategory->name}}</a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </li>
            <li id="cat160024" class="trigger-subnav-category trigger-subnav-category-float">
                <div class="subnav-Liquidacioncategory-text parent-li" style="font-size:10px">
                    <div class="subnav-category-underline"></div>
                    FERRETERIA
                    @php($subcategories = $categoriesList->subCategoriesWithProductsAndStock2("FERRETERIA"))
                    <div class="bottomlvl-cat-header-wrapper child-li border border-secondary"
                        style="visibility: hidden; height: 800px;">
                        <ul style="height: 800px;">
                            @foreach($subcategories as $subcategory)
                            <li style="" class="bottomlvl-cat-header" id="cat160024-cat1481016" data-catid="cat1481016">
                                <a class="text-capitalize"
                                    href="{{route('settlement-products',['slug' => $subcategory->id ])}}">{{$subcategory->name}}</a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </li>
            <li id="cat160024" class="trigger-subnav-category trigger-subnav-category-float">
                <div class="subnav-Liquidacioncategory-text parent-li" style="font-size:10px">
                    <div class="subnav-category-underline"></div>
                    IMPULSO
                    @php($subcategories = $categoriesList->subCategoriesWithProductsAndStock2("IMPULSO"))
                    <div class="bottomlvl-cat-header-wrapper child-li border border-secondary"
                        style="visibility: hidden; height: 443px;">
                        <ul style="height: 443px;">
                            @foreach($subcategories as $subcategory)
                            <li style="" class="bottomlvl-cat-header" id="cat160024-cat1481016" data-catid="cat1481016">
                                <a class="text-capitalize"
                                    href="{{route('settlement-products',['slug' => $subcategory->id ])}}">{{$subcategory->name}}</a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </li>
            <li id="cat160024" class="trigger-subnav-category trigger-subnav-category-float">
                <div class="subnav-Liquidacioncategory-text parent-li" style="font-size:10px">
                    <div class="subnav-category-underline"></div>
                    ELECTRICO
                    @php($subcategories = $categoriesList->subCategoriesWithProductsAndStock2("ELECTRICO"))
                    <div class="bottomlvl-cat-header-wrapper child-li border border-secondary"
                        style="visibility: hidden; height: 650px;">
                        <ul style="height: 650px;">
                            @foreach($subcategories as $subcategory)
                            <li style="" class="bottomlvl-cat-header" id="cat160024-cat1481016" data-catid="cat1481016">
                                <a class="text-capitalize"
                                    href="{{route('settlement-products',['slug' => $subcategory->id ])}}">{{$subcategory->name}}</a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </li>
            <li id="cat160024" class="trigger-subnav-category trigger-subnav-category-float">
                <div class="subnav-Liquidacioncategory-text parent-li" style="font-size:10px">
                    <div class="subnav-category-underline"></div>
                    PINTURA
                    @php($subcategories = $categoriesList->subCategoriesWithProductsAndStock2("PINTURA"))
                    <div class="bottomlvl-cat-header-wrapper child-li border border-secondary"
                        style="visibility: hidden; height: 460px;">
                        <ul style="height: 460px;">
                            @foreach($subcategories as $subcategory)
                            <li style="" class="bottomlvl-cat-header" id="cat160024-cat1481016" data-catid="cat1481016">
                                <a class="text-capitalize"
                                    href="{{route('settlement-products',['slug' => $subcategory->id ])}}">{{$subcategory->name}}</a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </li>
        </ul>
        <ul class="top-level-subnav shop-nav" style="z-index:1">
            <li id="cat160024" class="trigger-subnav-category trigger-subnav-category-float">
                <div class="subnav-Liquidacioncategory-text parent-li" style="font-size:10px">
                    <div class="subnav-category-underline"></div>
                    JARDIN
                    @php($subcategories = $categoriesList->subCategoriesWithProductsAndStock2("JARDIN"))
                    <div class="bottomlvl-cat-header-wrapper child-li border border-secondary"
                        style="visibility: hidden; height: 443px;">
                        <ul style="height: 443px;">
                            @foreach($subcategories as $subcategory)
                            <li style="" class="bottomlvl-cat-header" id="cat160024-cat1481016" data-catid="cat1481016">
                                <a class="text-capitalize"
                                    href="{{route('settlement-products',['slug' => $subcategory->id ])}}">{{$subcategory->name}}</a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </li>
            <li id="cat160024" class="trigger-subnav-category trigger-subnav-category-float">
                <div class="subnav-Liquidacioncategory-text parent-li" style="font-size:10px">
                    <div class="subnav-category-underline"></div>
                    LIMPIEZA
                    @php($subcategories = $categoriesList->subCategoriesWithProductsAndStock2("LIMPIEZA"))
                    <div class="bottomlvl-cat-header-wrapper child-li border border-secondary"
                        style="visibility: hidden; height: 480px;">
                        <ul style="height: 480px;">
                            @foreach($subcategories as $subcategory)
                            <li style="" class="bottomlvl-cat-header" id="cat160024-cat1481016" data-catid="cat1481016">
                                <a class="text-capitalize"
                                    href="{{route('settlement-products',['slug' => $subcategory->id ])}}">{{$subcategory->name}}</a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </li>
            <li id="cat160024" class="trigger-subnav-category trigger-subnav-category-float">
                <div class="subnav-Liquidacioncategory-text parent-li" style="font-size:10px">
                    <div class="subnav-category-underline"></div>
                    ILUMINACIÓN
                    @php($subcategories = $categoriesList->subCategoriesWithProductsAndStock2("ILUMINACION"))
                    <div class="bottomlvl-cat-header-wrapper child-li border border-secondary"
                        style="visibility: hidden; height: 443px;">
                        <ul style="height: 443px;">
                            @foreach($subcategories as $subcategory)
                            <li style="" class="bottomlvl-cat-header" id="cat160024-cat1481016" data-catid="cat1481016">
                                <a class="text-capitalize"
                                    href="{{route('settlement-products',['slug' => $subcategory->id ])}}">{{$subcategory->name}}</a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </li>
            <li id="cat160024" class="trigger-subnav-category trigger-subnav-category-float">
                <div class="subnav-Liquidacioncategory-text parent-li" style="font-size:10px">
                    <div class="subnav-category-underline"></div>
                    PLOMERIA
                    @php($subcategories = $categoriesList->subCategoriesWithProductsAndStock2("PLOMERIA"))
                    <div class="bottomlvl-cat-header-wrapper child-li border border-secondary"
                        style="visibility: hidden; height: 500px;">
                        <ul style="height: 500px;">
                            @foreach($subcategories as $subcategory)
                            <li style="" class="bottomlvl-cat-header" id="cat160024-cat1481016" data-catid="cat1481016">
                                <a class="text-capitalize"
                                    href="{{route('settlement-products',['slug' => $subcategory->id ])}}">{{$subcategory->name}}</a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </li>
            <li id="cat160024" class="trigger-subnav-category trigger-subnav-category-float">
                <div class="subnav-Liquidacioncategory-text parent-li" style="font-size:10px">
                    <div class="subnav-category-underline"></div>
                    VENTILACIÓN
                    @php($subcategories = $categoriesList->subCategoriesWithProductsAndStock2("VENTILACION"))
                    <div class="bottomlvl-cat-header-wrapper child-li border border-secondary"
                        style="visibility: hidden; height: 443px;">
                        <ul style="height: 443px;">
                            @foreach($subcategories as $subcategory)
                            <li style="" class="bottomlvl-cat-header" id="cat160024-cat1481016" data-catid="cat1481016">
                                <a class="text-capitalize"
                                    href="{{route('settlement-products',['slug' => $subcategory->id ])}}">{{$subcategory->name}}</a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </li>
            <li id="cat160024" class="trigger-subnav-category trigger-subnav-category-float">
                <div class="subnav-Liquidacioncategory-text parent-li" style="font-size:10px">
                    <div class="subnav-category-underline"></div>
                    EXTERIOR
                    @php($subcategories = $categoriesList->subCategoriesWithProductsAndStock2("EXTERIOR"))
                    <div class="bottomlvl-cat-header-wrapper child-li border border-secondary"
                        style="visibility: hidden; height: 443px;">
                        <ul style="height: 443px;">
                            @foreach($subcategories as $subcategory)
                            <li style="" class="bottomlvl-cat-header" id="cat160024-cat1481016" data-catid="cat1481016">
                                <a class="text-capitalize"
                                   href="{{route('settlement-products',['slug' => $subcategory->id ])}}">{{$subcategory->name}}</a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </li>
            <li id="cat160024" class="trigger-subnav-category trigger-subnav-category-float">
                <div class="subnav-Liquidacioncategory-text parent-li" style="font-size:10px">
                    <div class="subnav-category-underline"></div>
                    MANTENIMIENTO
                    @php($subcategories = $categoriesList->subCategoriesWithProductsAndStock2("MANTENIMIENTO"))
                    <div class="bottomlvl-cat-header-wrapper child-li border border-secondary"
                        style="visibility: hidden; height: 443px;">
                        <ul style="height: 443px;">
                            @foreach($subcategories as $subcategory)
                            <li style="" class="bottomlvl-cat-header" id="cat160024-cat1481016" data-catid="cat1481016">
                                <a class="text-capitalize"
                                    href="{{route('settlement-products',['slug' => $subcategory->id ])}}">{{$subcategory->name}}</a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </li>
            <li id="cat160024" class="trigger-subnav-category trigger-subnav-category-float">
                <div class="subnav-Liquidacioncategory-text parent-li" style="font-size:10px">
                    <div class="subnav-category-underline"></div>
                    MATERIAL CONSTRUCCIÓN
                    @php($subcategories = $categoriesList->subCategoriesWithProductsAndStock2("MATERIAL CONSTRUCCION"))
                    <div class="bottomlvl-cat-header-wrapper child-li border border-secondary"
                        style="visibility: hidden; height: 443px;">
                        <ul style="height: 443px;">
                            @foreach($subcategories as $subcategory)
                            <li style="" class="bottomlvl-cat-header" id="cat160024-cat1481016" data-catid="cat1481016">
                                <a class="text-capitalize"
                                    href="{{route('settlement-products',['slug' => $subcategory->id ])}}">{{$subcategory->name}}</a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </li>
            <li id="cat160024" class="trigger-subnav-category trigger-subnav-category-float">
                <div class="subnav-Liquidacioncategory-text parent-li" style="font-size:10px">
                    <div class="subnav-category-underline"></div>
                    OUTDOOR
                    @php($subcategories = $categoriesList->subCategoriesWithProductsAndStock2("OUTDOOR"))
                    <div class="bottomlvl-cat-header-wrapper child-li border border-secondary"
                        style="visibility: hidden; height: 443px;">
                        <ul style="height: 443px;">
                            @foreach($subcategories as $subcategory)
                            <li style="" class="bottomlvl-cat-header" id="cat160024-cat1481016" data-catid="cat1481016">
                                <a class="text-capitalize"
                                    href="{{route('settlement-products',['slug' => $subcategory->id ])}}">{{$subcategory->name}}</a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </li>
            <li id="cat160024" class="trigger-subnav-category trigger-subnav-category-float">
                <div class="subnav-Liquidacioncategory-text parent-li" style="font-size:10px">
                    <div class="subnav-category-underline"></div>
                    CHAPAS
                    @php($subcategories = $categoriesList->subCategoriesWithProductsAndStock2("CHAPAS"))
                    <div class="bottomlvl-cat-header-wrapper child-li border border-secondary"
                        style="visibility: hidden; height: 443px;">
                        <ul style="height: 443px;">
                            @foreach($subcategories as $subcategory)
                            <li style="" class="bottomlvl-cat-header" id="cat160024-cat1481016" data-catid="cat1481016">
                                <a class="text-capitalize"
                                    href="{{route('settlement-products',['slug' => $subcategory->id ])}}">{{$subcategory->name}}</a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </li>
        </ul>
    </nav>
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