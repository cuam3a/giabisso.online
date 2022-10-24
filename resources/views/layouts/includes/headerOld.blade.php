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

<div class="header__container" style="background-color:rgba(13, 13, 50, 0.85); position:relative">
    <div class="header-wrapper menuBig">
        <div class="header-wrapper__ie-fix"></div>
        <div id="header" class="header">
            <div class="logo-wrapper">
                <a href="{{ route('website-home') }}" class="logo" alt="RH Logo" title="Inicio">
                    <img src="{{ asset('/website/img/Logo-JAT.png') }}" alt="..." class="mb-2" width="90" height="50" />
                </a>
            </div>
            <div class="uber-nav-wrapper">
                <nav id="uber-nav">
                    <ul>
                        <li id="uber-nav-rh-link" class="js-uber-nav__brand uber-nav__brand site-hidden">
                            <a href="//www.restorationhardware.com">

                            </a>
                        </li>
                        <li id="uber-nav-rhmo-link" class="js-uber-nav__brand uber-nav__brand ">
                            <a href="{{route('concepto')}}">
                                CONCEPTO
                            </a>
                        </li>
                        <li id="uber-nav-rhbc-link" class="js-uber-nav__brand uber-nav__brand ">
                            <a href="{{route('nosotros')}}">
                                NOSOTROS
                            </a>
                        </li>
                        <li id="uber-nav-rhtn-link" class="js-uber-nav__brand uber-nav__brand ">
                            <a href="{{route('politica-precios')}}">
                                POLITICA DE PRECIOS
                            </a>
                        </li>
                        <li id="uber-nav-design-link" class="js-uber-nav__brand uber-nav__brand">
                            <a href="{{route('ventajas')}}">
                                VENTAJAS
                            </a>
                        </li>
                        <li id="uber-nav-design-link" class="js-uber-nav__brand uber-nav__brand">
                            <a href="{{route('beneficios')}}">
                                BENEFICIOS
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
            <div class="utility noprint  dark-bar">
                <div class="utility-commerce">
                    <ul id="header-utils" class="commerce-utils">
                        <li><a class="btnSearch"><i class="fa fa-search text-white"></i></a></li>
                        <li><a href="{{route('programming')}}">PROGRAMACIÓN
                                ({{ $orderProgrammerViews->count($customer_id) }})</a></li>
                        <li><a href="{{route('cart')}}">PEDIDOS ({{Cart::count()}})</a></li>
                        @if(!Auth::guard('customer-web')->check())
                        <li><a href="{{ route('website-login') }}">MI CUENTA</a></li>
                        @else
                        <li><a href="{{route('my-account')}}">{{Auth::user()->name}}</a></li>
                        @endif
                    </ul>
                    <div class="js-search-form search-form" hidden id="searchForm">
                        <form method="get" action="{{route('search-products',$params)}}" formid="header-searchform"
                            name="header-searchform" class="hasrequired header-search">
                            <input title="search" placeholder="Buscar.." type="text" maxlength="255" name="s"
                                autocomplete="off" class="search-input js-search-input required"
                                value="{{@$_GET['s']}}">
                            <div class="search-input-container">
                                <button id="search-input-submit" class="search-input-submit" name="go" priority="-10"
                                    alt="Go">Go</button>
                            </div>
                            <div class="search-typeahead-dropped hidden">
                                <div class="x-close js-close-search" title="Close" role="button"
                                    aria-label="Close Search" tabindex="0">
                                </div>
                                <div class="search-typeahead-results">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <nav role="navigation" class="main-navigation top-level-subnav-border " style="z-index:2">
            <ul class="top-level-subnav shop-nav">
                <li class="trigger-subnav-category trigger-subnav-category-float ">
                    <div class="subnav-category-text parent-li" style="">
                        <div class="subnav-category-underline"></div>
                        COCINA
                        @php($subcategories = $categoriesList->subCategoriesWithProductsAndStock2("COCINA"))
                        <div class="bottomlvl-cat-header-wrapper child-li border border-secondary"
                            style="visibility: hidden; height: 443px; z-index:3; position:absolute">
                            <ul style="height: 443px;">
                                @foreach($subcategories as $subcategory)
                                <li style="" class="bottomlvl-cat-header" id="cat160024-cat1481016"
                                    data-catid="cat1481016">
                                    <a class="text-capitalize"
                                        href="{{route('category-products',['slug' => 'cocina','child' => $subcategory->slug])}}">{{$subcategory->name}}</a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </li>
                <li id="cat160024" class="trigger-subnav-category trigger-subnav-category-float ">
                    <div class="subnav-category-text parent-li" style="">
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
                                        href="{{route('category-products',['slug' => 'lavanderia','child' => $subcategory->slug])}}">{{$subcategory->name}}</a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </li>
                <li id="cat160024" class="trigger-subnav-category trigger-subnav-category-float">
                    <div class="subnav-category-text parent-li" style="">
                        <div class="subnav-category-underline"></div>
                        CLOSETS
                        @php($subcategories = $categoriesList->subCategoriesWithProductsAndStock2("CLOSETS"))
                        <div class="bottomlvl-cat-header-wrapper child-li border border-secondary"
                            style="visibility: hidden; height: 443px;">
                            <ul style="height: 443px;">
                                @foreach($subcategories as $subcategory)
                                <li style="" class="bottomlvl-cat-header" id="cat160024-cat1481016"
                                    data-catid="cat1481016">
                                    <a class="text-capitalize"
                                        href="{{route('category-products',['slug' => 'closets','child' => $subcategory->slug])}}">{{$subcategory->name}}</a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </li>
                <li id="cat160024" class="trigger-subnav-category trigger-subnav-category-float">
                    <div class="subnav-category-text parent-li" style="">
                        <div class="subnav-category-underline"></div>
                        HERRAMIENTA
                        @php($subcategories = $categoriesList->subCategoriesWithProductsAndStock2("HERRAMIENTA"))
                        <div class="bottomlvl-cat-header-wrapper child-li border border-secondary"
                            style="visibility: hidden; height: 800px;">
                            <ul style="height: 800px;">
                                @foreach($subcategories as $subcategory)
                                <li style="" class="bottomlvl-cat-header" id="cat160024-cat1481016"
                                    data-catid="cat1481016">
                                    <a class="text-capitalize"
                                        href="{{route('category-products',['slug' => 'herramienta','child' => $subcategory->slug])}}">{{$subcategory->name}}</a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </li>
                <li id="cat160024" class="trigger-subnav-category trigger-subnav-category-float">
                    <div class="subnav-category-text parent-li" style="">
                        <div class="subnav-category-underline"></div>
                        AUTOMOTRIZ
                        @php($subcategories = $categoriesList->subCategoriesWithProductsAndStock2("AUTOMOTRIZ"))
                        <div class="bottomlvl-cat-header-wrapper child-li border border-secondary"
                            style="visibility: hidden; height: 550px;">
                            <ul style="height: 550px;">
                                @foreach($subcategories as $subcategory)
                                <li style="" class="bottomlvl-cat-header" id="cat160024-cat1481016"
                                    data-catid="cat1481016">
                                    <a class="text-capitalize"
                                        href="{{route('category-products',['slug' => 'automotriz','child' => $subcategory->slug])}}">{{$subcategory->name}}</a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </li>
                <li id="cat160024" class="trigger-subnav-category trigger-subnav-category-float">
                    <div class="subnav-category-text parent-li" style="">
                        <div class="subnav-category-underline"></div>
                        FERRETERIA
                        @php($subcategories = $categoriesList->subCategoriesWithProductsAndStock2("FERRETERIA"))
                        <div class="bottomlvl-cat-header-wrapper child-li border border-secondary"
                            style="visibility: hidden; height: 800px;">
                            <ul style="height: 800px;">
                                @foreach($subcategories as $subcategory)
                                <li style="" class="bottomlvl-cat-header" id="cat160024-cat1481016"
                                    data-catid="cat1481016">
                                    <a class="text-capitalize"
                                        href="{{route('category-products',['slug' => 'ferreteria','child' => $subcategory->slug])}}">{{$subcategory->name}}</a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </li>
                <li id="cat160024" class="trigger-subnav-category trigger-subnav-category-float">
                    <div class="subnav-category-text parent-li" style="">
                        <div class="subnav-category-underline"></div>
                        IMPULSO
                        @php($subcategories = $categoriesList->subCategoriesWithProductsAndStock2("IMPULSO"))
                        <div class="bottomlvl-cat-header-wrapper child-li border border-secondary"
                            style="visibility: hidden; height: 443px;">
                            <ul style="height: 443px;">
                                @foreach($subcategories as $subcategory)
                                <li style="" class="bottomlvl-cat-header" id="cat160024-cat1481016"
                                    data-catid="cat1481016">
                                    <a class="text-capitalize"
                                        href="{{route('category-products',['slug' => 'impulso','child' => $subcategory->slug])}}">{{$subcategory->name}}</a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </li>
                <li id="cat160024" class="trigger-subnav-category trigger-subnav-category-float">
                    <div class="subnav-category-text parent-li" style="">
                        <div class="subnav-category-underline"></div>
                        ELECTRICO
                        @php($subcategories = $categoriesList->subCategoriesWithProductsAndStock2("ELECTRICO"))
                        <div class="bottomlvl-cat-header-wrapper child-li border border-secondary"
                            style="visibility: hidden; height: 650px;">
                            <ul style="height: 650px;">
                                @foreach($subcategories as $subcategory)
                                <li style="" class="bottomlvl-cat-header" id="cat160024-cat1481016"
                                    data-catid="cat1481016">
                                    <a class="text-capitalize"
                                        href="{{route('category-products',['slug' => 'electrico','child' => $subcategory->slug])}}">{{$subcategory->name}}</a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </li>
                <li id="cat160024" class="trigger-subnav-category trigger-subnav-category-float">
                    <div class="subnav-category-text parent-li" style="">
                        <div class="subnav-category-underline"></div>
                        PINTURA
                        @php($subcategories = $categoriesList->subCategoriesWithProductsAndStock2("PINTURA"))
                        <div class="bottomlvl-cat-header-wrapper child-li border border-secondary"
                            style="visibility: hidden; height: 460px;">
                            <ul style="height: 460px;">
                                @foreach($subcategories as $subcategory)
                                <li style="" class="bottomlvl-cat-header" id="cat160024-cat1481016"
                                    data-catid="cat1481016">
                                    <a class="text-capitalize"
                                        href="{{route('category-products',['slug' => 'pintura','child' => $subcategory->slug])}}">{{$subcategory->name}}</a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </li>
                <li id="cat160024" class="trigger-subnav-category trigger-subnav-category-float">
                    <div class="subnav-category-text parent-li" style="">
                        <div class="subnav-category-underline"></div>
                        <a class="text-white" href="{{route('offers-products')}}">OFERTAS</a>
                    </div>
                </li>
            </ul>
            <ul class="top-level-subnav shop-nav">
                <li id="cat160024" class="trigger-subnav-category trigger-subnav-category-float">
                    <div class="subnav-category-text parent-li" style="">
                        <div class="subnav-category-underline"></div>
                        JARDIN
                        @php($subcategories = $categoriesList->subCategoriesWithProductsAndStock2("JARDIN"))
                        <div class="bottomlvl-cat-header-wrapper child-li border border-secondary"
                            style="visibility: hidden; height: 443px;">
                            <ul style="height: 443px;">
                                @foreach($subcategories as $subcategory)
                                <li style="" class="bottomlvl-cat-header" id="cat160024-cat1481016"
                                    data-catid="cat1481016">
                                    <a class="text-capitalize"
                                        href="{{route('category-products',['slug' => 'jardin','child' => $subcategory->slug])}}">{{$subcategory->name}}</a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </li>
                <li id="cat160024" class="trigger-subnav-category trigger-subnav-category-float">
                    <div class="subnav-category-text parent-li" style="">
                        <div class="subnav-category-underline"></div>
                        LIMPIEZA
                        @php($subcategories = $categoriesList->subCategoriesWithProductsAndStock2("LIMPIEZA"))
                        <div class="bottomlvl-cat-header-wrapper child-li border border-secondary"
                            style="visibility: hidden; height: 480px;">
                            <ul style="height: 480px;">
                                @foreach($subcategories as $subcategory)
                                <li style="" class="bottomlvl-cat-header" id="cat160024-cat1481016"
                                    data-catid="cat1481016">
                                    <a class="text-capitalize"
                                        href="{{route('category-products',['slug' => 'limpieza','child' => $subcategory->slug])}}">{{$subcategory->name}}</a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </li>
                <li id="cat160024" class="trigger-subnav-category trigger-subnav-category-float">
                    <div class="subnav-category-text parent-li" style="">
                        <div class="subnav-category-underline"></div>
                        ILUMINACIÓN
                        @php($subcategories = $categoriesList->subCategoriesWithProductsAndStock2("ILUMINACION"))
                        <div class="bottomlvl-cat-header-wrapper child-li border border-secondary"
                            style="visibility: hidden; height: 443px;">
                            <ul style="height: 443px;">
                                @foreach($subcategories as $subcategory)
                                <li style="" class="bottomlvl-cat-header" id="cat160024-cat1481016"
                                    data-catid="cat1481016">
                                    <a class="text-capitalize"
                                        href="{{route('category-products',['slug' => 'iluminacion','child' => $subcategory->slug])}}">{{$subcategory->name}}</a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </li>
                <li id="cat160024" class="trigger-subnav-category trigger-subnav-category-float">
                    <div class="subnav-category-text parent-li" style="">
                        <div class="subnav-category-underline"></div>
                        PLOMERIA
                        @php($subcategories = $categoriesList->subCategoriesWithProductsAndStock2("PLOMERIA"))
                        <div class="bottomlvl-cat-header-wrapper child-li border border-secondary"
                            style="visibility: hidden; height: 500px;">
                            <ul style="height: 500px;">
                                @foreach($subcategories as $subcategory)
                                <li style="" class="bottomlvl-cat-header" id="cat160024-cat1481016"
                                    data-catid="cat1481016">
                                    <a class="text-capitalize"
                                        href="{{route('category-products',['slug' => 'plomeria','child' => $subcategory->slug])}}">{{$subcategory->name}}</a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </li>
                <li id="cat160024" class="trigger-subnav-category trigger-subnav-category-float">
                    <div class="subnav-category-text parent-li" style="">
                        <div class="subnav-category-underline"></div>
                        VENTILACIÓN
                        @php($subcategories = $categoriesList->subCategoriesWithProductsAndStock2("VENTILACION"))
                        <div class="bottomlvl-cat-header-wrapper child-li border border-secondary"
                            style="visibility: hidden; height: 443px;">
                            <ul style="height: 443px;">
                                @foreach($subcategories as $subcategory)
                                <li style="" class="bottomlvl-cat-header" id="cat160024-cat1481016"
                                    data-catid="cat1481016">
                                    <a class="text-capitalize"
                                        href="{{route('category-products',['slug' => 'ventilacion','child' => $subcategory->slug])}}">{{$subcategory->name}}</a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </li>
                <li id="cat160024" class="trigger-subnav-category trigger-subnav-category-float">
                    <div class="subnav-category-text parent-li" style="">
                        <div class="subnav-category-underline"></div>
                        EXTERIOR
                        @php($subcategories = $categoriesList->subCategoriesWithProductsAndStock2("EXTERIOR"))
                        <div class="bottomlvl-cat-header-wrapper child-li border border-secondary"
                            style="visibility: hidden; height: 443px;">
                            <ul style="height: 443px;">
                                @foreach($subcategories as $subcategory)
                                <li style="" class="bottomlvl-cat-header" id="cat160024-cat1481016"
                                    data-catid="cat1481016">
                                    <a class="text-capitalize"
                                        href="{{route('category-products',['slug' => 'exterior','child' => $subcategory->slug])}}">{{$subcategory->name}}</a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </li>
                <li id="cat160024" class="trigger-subnav-category trigger-subnav-category-float">
                    <div class="subnav-category-text parent-li" style="">
                        <div class="subnav-category-underline"></div>
                        MANTENIMIENTO
                        @php($subcategories = $categoriesList->subCategoriesWithProductsAndStock2("MANTENIMIENTO"))
                        <div class="bottomlvl-cat-header-wrapper child-li border border-secondary"
                            style="visibility: hidden; height: 443px;">
                            <ul style="height: 443px;">
                                @foreach($subcategories as $subcategory)
                                <li style="" class="bottomlvl-cat-header" id="cat160024-cat1481016"
                                    data-catid="cat1481016">
                                    <a class="text-capitalize"
                                        href="{{route('category-products',['slug' => 'mantenimiento','child' => $subcategory->slug])}}">{{$subcategory->name}}</a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </li>
                <li id="cat160024" class="trigger-subnav-category trigger-subnav-category-float">
                    <div class="subnav-category-text parent-li" style="">
                        <div class="subnav-category-underline"></div>
                        MATERIAL CONSTRUCCIÓN
                        @php($subcategories = $categoriesList->subCategoriesWithProductsAndStock2("MATERIAL
                        CONSTRUCCION"))
                        <div class="bottomlvl-cat-header-wrapper child-li border border-secondary"
                            style="visibility: hidden; height: 443px;">
                            <ul style="height: 443px;">
                                @foreach($subcategories as $subcategory)
                                <li style="" class="bottomlvl-cat-header" id="cat160024-cat1481016"
                                    data-catid="cat1481016">
                                    <a class="text-capitalize"
                                        href="{{route('category-products',['slug' => 'material contruccion','child' => $subcategory->slug])}}">{{$subcategory->name}}</a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </li>
                <li id="cat160024" class="trigger-subnav-category trigger-subnav-category-float">
                    <div class="subnav-category-text parent-li" style="">
                        <div class="subnav-category-underline"></div>
                        OUTDOOR
                        @php($subcategories = $categoriesList->subCategoriesWithProductsAndStock2("OUTDOOR"))
                        <div class="bottomlvl-cat-header-wrapper child-li border border-secondary"
                            style="visibility: hidden; height: 443px;">
                            <ul style="height: 443px;">
                                @foreach($subcategories as $subcategory)
                                <li style="" class="bottomlvl-cat-header" id="cat160024-cat1481016"
                                    data-catid="cat1481016">
                                    <a class="text-capitalize"
                                        href="{{route('category-products',['slug' => 'outdoor','child' => $subcategory->slug])}}">{{$subcategory->name}}</a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </li>
                <li id="cat160024" class="trigger-subnav-category trigger-subnav-category-float">
                    <div class="subnav-category-text parent-li" style="">
                        <div class="subnav-category-underline"></div>
                        CHAPAS
                        @php($subcategories = $categoriesList->subCategoriesWithProductsAndStock2("CHAPAS"))
                        <div class="bottomlvl-cat-header-wrapper child-li border border-secondary"
                            style="visibility: hidden; height: 443px;">
                            <ul style="height: 443px;">
                                @foreach($subcategories as $subcategory)
                                <li style="" class="bottomlvl-cat-header" id="cat160024-cat1481016"
                                    data-catid="cat1481016">
                                    <a class="text-capitalize"
                                        href="{{route('category-products',['slug' => 'chapas','child' => $subcategory->slug])}}">{{$subcategory->name}}</a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </li>
                <li id="cat160024" class="trigger-subnav-category trigger-subnav-category-float">
                    <div class="subnav-category-text" style="">
                        <div class="subnav-category-underline"></div>
                        <a class="text-white" href="{{route('settlement-products')}}">PRODUCTOS LIQUIDACIÓN</a>
                    </div>
                </li>
            </ul>
        </nav>
    </div>

    <div class="menuLittle">
        <nav class="navbar navbar-expand-lg navbar-light pb-5"
            style="background-color:rgba(13, 13, 50, 0.85); height: auto">
            <div class="logo-wrapper">
                <a href="{{ route('website-home') }}" class="logo" alt="RH Logo" title="Inicio">
                    <img src="{{ asset('/website/img/Logo-JAT.png') }}" alt="..." class="mb-2" width="90" height="50" />
                </a>
            </div>
            <button class="navbar-toggler mt-4 bg-light" type="button" data-toggle="collapse"
                data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul>
                    <li id="" class="nav-item ">
                        <a class="nav-link text-white text-center" href="{{route('concepto')}}">
                            <h1 style="font:20px/18px 'minion-pro', Times, 'Times New Roman'">CONCEPTO</h1>
                        </a>
                    </li>
                    <li id="uber-nav-rhbc-link" class="nav-item text-white">
                        <a class="nav-link text-white text-center" href="{{route('nosotros')}}">
                            <h1 style="font:20px/18px 'minion-pro', Times, 'Times New Roman'">NOSOTROS</h1>
                        </a>
                    </li>
                    <li id="uber-nav-rhtn-link" class="nav-item text-white">
                        <a class="nav-link text-white text-center" href="{{route('politica-precios')}}">
                            <h1 style="font:20px/18px 'minion-pro', Times, 'Times New Roman'">POLITICA DE PRECIOS</h1>
                        </a>
                    </li>
                    <li id="uber-nav-design-link" class="nav-item text-white">
                        <a class="nav-link text-white text-center" href="{{route('ventajas')}}">
                            <h1 style="font:20px/18px 'minion-pro', Times, 'Times New Roman'">VENTAJAS</h1>
                        </a>
                    </li>
                    <li id="uber-nav-design-link" class="nav-item text-white">
                        <a class="nav-link text-white text-center" href="{{route('beneficios')}}">
                            <h1 style="font:20px/18px 'minion-pro', Times, 'Times New Roman'">BENEFICIOS</h1>
                        </a>
                    </li>
                    <li class="nav-item text-white">
                        <a class="nav-link text-white text-center" href="{{route('programming')}}">
                            <h1 style="font:20px/18px 'minion-pro', Times, 'Times New Roman'">PROGRAMACIÓN
                                ({{ $orderProgrammerViews->count($customer_id) }})</h1>
                        </a>
                    </li>
                    <li>
                        <a class="nav-link text-white text-center" href="{{route('cart')}}">
                            <h1 style="font:20px/18px 'minion-pro', Times, 'Times New Roman'">PEDIDOS
                                ({{Cart::count()}})</h1>
                        </a>
                    </li>
                    @if(!Auth::guard('customer-web')->check())
                    <li>
                        <a class="nav-link text-white text-center" href="{{ route('website-login') }}">
                            <h1 style="font:20px/18px 'minion-pro', Times, 'Times New Roman'">MI CUENTA</h1>
                        </a>
                    </li>
                    @else
                    <li>
                        <a class="nav-link text-white text-center" href="{{route('my-account')}}">
                            <h1 style="font:20px/18px 'minion-pro', Times, 'Times New Roman'">{{Auth::user()->name}}
                            </h1>
                        </a>
                    </li>
                    @endif
                </ul>
                <hr class="bg-light">
                <ul>
                    <li id="" class="nav-item ">
                        <a class="nav-link text-white text-center" data-toggle="collapse" href="#collapseCocina"
                            role="button" aria-expanded="false" aria-controls="collapseCocina">
                            <h1 style="font:20px/18px 'minion-pro', Times, 'Times New Roman'">COCINA</h1>
                        </a>
                        <div class="collapse" id="collapseCocina">
                            @php($subcategories = $categoriesList->subCategoriesWithProductsAndStock2("COCINA"))
                            <ul>
                                @foreach($subcategories as $subcategory)
                                <li class="nav-item">
                                    <a class="nav-link text-white text-center" href="{{route('category-products',['slug' => 'cocina','child' => $subcategory->slug])}}">
                                        <h1 style="font:20px/18px 'minion-pro', Times, 'Times New Roman'">{{$subcategory->name}}</h1>
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </li>
                    <li id="" class="nav-item ">
                        <a class="nav-link text-white text-center" data-toggle="collapse" href="#collapseLavanderia"
                            role="button" aria-expanded="false" aria-controls="collapseLavanderia">
                            <h1 style="font:20px/18px 'minion-pro', Times, 'Times New Roman'">LAVANDERIA</h1>
                        </a>
                        <div class="collapse" id="collapseLavanderia">
                            @php($subcategories = $categoriesList->subCategoriesWithProductsAndStock2("LAVANDERIA"))
                            <ul>
                                @foreach($subcategories as $subcategory)
                                <li class="nav-item">
                                    <a class="nav-link text-white text-center" href="{{route('category-products',['slug' => 'lavanderia','child' => $subcategory->slug])}}">
                                        <h1 style="font:20px/18px 'minion-pro', Times, 'Times New Roman'">{{$subcategory->name}}</h1>
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </li>
                    <li id="" class="nav-item ">
                        <a class="nav-link text-white text-center" data-toggle="collapse" href="#collapseClosets"
                            role="button" aria-expanded="false" aria-controls="collapseClosets">
                            <h1 style="font:20px/18px 'minion-pro', Times, 'Times New Roman'">CLOSETS</h1>
                        </a>
                        <div class="collapse" id="collapseClosets">
                            @php($subcategories = $categoriesList->subCategoriesWithProductsAndStock2("CLOSETS"))
                            <ul>
                                @foreach($subcategories as $subcategory)
                                <li class="nav-item">
                                    <a class="nav-link text-white text-center" href="{{route('category-products',['slug' => 'closets','child' => $subcategory->slug])}}">
                                        <h1 style="font:20px/18px 'minion-pro', Times, 'Times New Roman'">{{$subcategory->name}}</h1>
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </li>
                    <li id="" class="nav-item ">
                        <a class="nav-link text-white text-center" data-toggle="collapse" href="#collapseHerramienta"
                            role="button" aria-expanded="false" aria-controls="collapseHerramienta">
                            <h1 style="font:20px/18px 'minion-pro', Times, 'Times New Roman'">HERRAMIENTA</h1>
                        </a>
                        <div class="collapse" id="collapseHerramienta">
                            @php($subcategories = $categoriesList->subCategoriesWithProductsAndStock2("HERRAMIENTA"))
                            <ul>
                                @foreach($subcategories as $subcategory)
                                <li class="nav-item">
                                    <a class="nav-link text-white text-center" href="{{route('category-products',['slug' => 'herramienta','child' => $subcategory->slug])}}">
                                        <h1 style="font:20px/18px 'minion-pro', Times, 'Times New Roman'">{{$subcategory->name}}</h1>
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </li>
                    <li id="" class="nav-item ">
                        <a class="nav-link text-white text-center" data-toggle="collapse" href="#collapseAutomotriz"
                            role="button" aria-expanded="false" aria-controls="collapseAutomotriz">
                            <h1 style="font:20px/18px 'minion-pro', Times, 'Times New Roman'">AUTOMOTRIZ</h1>
                        </a>
                        <div class="collapse" id="collapseAutomotriz">
                            @php($subcategories = $categoriesList->subCategoriesWithProductsAndStock2("AUTOMOTRIZ"))
                            <ul>
                                @foreach($subcategories as $subcategory)
                                <li class="nav-item">
                                    <a class="nav-link text-white text-center" href="{{route('category-products',['slug' => 'automotriz','child' => $subcategory->slug])}}">
                                        <h1 style="font:20px/18px 'minion-pro', Times, 'Times New Roman'">{{$subcategory->name}}</h1>
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </li>
                    <li id="" class="nav-item ">
                        <a class="nav-link text-white text-center" data-toggle="collapse" href="#collapseFerreteria"
                            role="button" aria-expanded="false" aria-controls="collapseFerreteria">
                            <h1 style="font:20px/18px 'minion-pro', Times, 'Times New Roman'">FERRETERIA</h1>
                        </a>
                        <div class="collapse" id="collapseFerreteria">
                            @php($subcategories = $categoriesList->subCategoriesWithProductsAndStock2("FERRETERIA"))
                            <ul>
                                @foreach($subcategories as $subcategory)
                                <li class="nav-item">
                                    <a class="nav-link text-white text-center" href="{{route('category-products',['slug' => 'ferreteria','child' => $subcategory->slug])}}">
                                        <h1 style="font:20px/18px 'minion-pro', Times, 'Times New Roman'">{{$subcategory->name}}</h1>
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </li>
                    <li id="" class="nav-item ">
                        <a class="nav-link text-white text-center" data-toggle="collapse" href="#collapseImpulso"
                            role="button" aria-expanded="false" aria-controls="collapseImpulso">
                            <h1 style="font:20px/18px 'minion-pro', Times, 'Times New Roman'">IMPULSO</h1>
                        </a>
                        <div class="collapse" id="collapseImpulso">
                            @php($subcategories = $categoriesList->subCategoriesWithProductsAndStock2("IMPULSO"))
                            <ul>
                                @foreach($subcategories as $subcategory)
                                <li class="nav-item">
                                    <a class="nav-link text-white text-center" href="{{route('category-products',['slug' => 'impulso','child' => $subcategory->slug])}}">
                                        <h1 style="font:20px/18px 'minion-pro', Times, 'Times New Roman'">{{$subcategory->name}}</h1>
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </li>
                    <li id="" class="nav-item ">
                        <a class="nav-link text-white text-center" data-toggle="collapse" href="#collapseElectrico"
                            role="button" aria-expanded="false" aria-controls="collapseElectrico">
                            <h1 style="font:20px/18px 'minion-pro', Times, 'Times New Roman'">ELECTRICO</h1>
                        </a>
                        <div class="collapse" id="collapseElectrico">
                            @php($subcategories = $categoriesList->subCategoriesWithProductsAndStock2("ELECTRICO"))
                            <ul>
                                @foreach($subcategories as $subcategory)
                                <li class="nav-item">
                                    <a class="nav-link text-white text-center" href="{{route('category-products',['slug' => 'electrico','child' => $subcategory->slug])}}">
                                        <h1 style="font:20px/18px 'minion-pro', Times, 'Times New Roman'">{{$subcategory->name}}</h1>
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </li>
                    <li id="" class="nav-item ">
                        <a class="nav-link text-white text-center" data-toggle="collapse" href="#collapsePintura"
                            role="button" aria-expanded="false" aria-controls="collapsePintura">
                            <h1 style="font:20px/18px 'minion-pro', Times, 'Times New Roman'">PINTURA</h1>
                        </a>
                        <div class="collapse" id="collapsePintura">
                            @php($subcategories = $categoriesList->subCategoriesWithProductsAndStock2("PINTURA"))
                            <ul>
                                @foreach($subcategories as $subcategory)
                                <li class="nav-item">
                                    <a class="nav-link text-white text-center" href="{{route('category-products',['slug' => 'pintura','child' => $subcategory->slug])}}">
                                        <h1 style="font:20px/18px 'minion-pro', Times, 'Times New Roman'">{{$subcategory->name}}</h1>
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </li>
                    <li id="" class="nav-item ">
                        <a class="nav-link text-white text-center" data-toggle="collapse" href="#collapseJardin"
                            role="button" aria-expanded="false" aria-controls="collapseJardin">
                            <h1 style="font:20px/18px 'minion-pro', Times, 'Times New Roman'">JARDIN</h1>
                        </a>
                        <div class="collapse" id="collapseJardin">
                            @php($subcategories = $categoriesList->subCategoriesWithProductsAndStock2("JARDIN"))
                            <ul>
                                @foreach($subcategories as $subcategory)
                                <li class="nav-item">
                                    <a class="nav-link text-white text-center" href="{{route('category-products',['slug' => 'jardin','child' => $subcategory->slug])}}">
                                        <h1 style="font:20px/18px 'minion-pro', Times, 'Times New Roman'">{{$subcategory->name}}</h1>
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </li>
                    <li id="" class="nav-item ">
                        <a class="nav-link text-white text-center" data-toggle="collapse" href="#collapseLimpieza"
                            role="button" aria-expanded="false" aria-controls="collapseLimpieza">
                            <h1 style="font:20px/18px 'minion-pro', Times, 'Times New Roman'">LIMPIEZA</h1>
                        </a>
                        <div class="collapse" id="collapseLimpieza">
                            @php($subcategories = $categoriesList->subCategoriesWithProductsAndStock2("LIMPIEZA"))
                            <ul>
                                @foreach($subcategories as $subcategory)
                                <li class="nav-item">
                                    <a class="nav-link text-white text-center" href="{{route('category-products',['slug' => 'limpieza','child' => $subcategory->slug])}}">
                                        <h1 style="font:20px/18px 'minion-pro', Times, 'Times New Roman'">{{$subcategory->name}}</h1>
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </li>
                    <li id="" class="nav-item ">
                        <a class="nav-link text-white text-center" data-toggle="collapse" href="#collapseIluminacion"
                            role="button" aria-expanded="false" aria-controls="collapseIluminacion">
                            <h1 style="font:20px/18px 'minion-pro', Times, 'Times New Roman'">ILUMINACIÓN</h1>
                        </a>
                        <div class="collapse" id="collapseIluminacion">
                            @php($subcategories = $categoriesList->subCategoriesWithProductsAndStock2("ILUMINACION"))
                            <ul>
                                @foreach($subcategories as $subcategory)
                                <li class="nav-item">
                                    <a class="nav-link text-white text-center" href="{{route('category-products',['slug' => 'iluminacion','child' => $subcategory->slug])}}">
                                        <h1 style="font:20px/18px 'minion-pro', Times, 'Times New Roman'">{{$subcategory->name}}</h1>
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </li>
                    <li id="" class="nav-item ">
                        <a class="nav-link text-white text-center" data-toggle="collapse" href="#collapsePlomeria"
                            role="button" aria-expanded="false" aria-controls="collapsePlomeria">
                            <h1 style="font:20px/18px 'minion-pro', Times, 'Times New Roman'">PLOMERIA</h1>
                        </a>
                        <div class="collapse" id="collapsePlomeria">
                            @php($subcategories = $categoriesList->subCategoriesWithProductsAndStock2("PLOMERIA"))
                            <ul>
                                @foreach($subcategories as $subcategory)
                                <li class="nav-item">
                                    <a class="nav-link text-white text-center" href="{{route('category-products',['slug' => 'plomeria','child' => $subcategory->slug])}}">
                                        <h1 style="font:20px/18px 'minion-pro', Times, 'Times New Roman'">{{$subcategory->name}}</h1>
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </li>
                    <li id="" class="nav-item ">
                        <a class="nav-link text-white text-center" data-toggle="collapse" href="#collapseVentilacion"
                            role="button" aria-expanded="false" aria-controls="collapseVentilacion">
                            <h1 style="font:20px/18px 'minion-pro', Times, 'Times New Roman'">VENTILACIÓN</h1>
                        </a>
                        <div class="collapse" id="collapseVentilacion">
                            @php($subcategories = $categoriesList->subCategoriesWithProductsAndStock2("VENTILACION"))
                            <ul>
                                @foreach($subcategories as $subcategory)
                                <li class="nav-item">
                                    <a class="nav-link text-white text-center" href="{{route('category-products',['slug' => 'ventilacion','child' => $subcategory->slug])}}">
                                        <h1 style="font:20px/18px 'minion-pro', Times, 'Times New Roman'">{{$subcategory->name}}</h1>
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </li>
                    <li id="" class="nav-item ">
                        <a class="nav-link text-white text-center" data-toggle="collapse" href="#collapseExterior"
                            role="button" aria-expanded="false" aria-controls="collapseExterior">
                            <h1 style="font:20px/18px 'minion-pro', Times, 'Times New Roman'">EXTERIOR</h1>
                        </a>
                        <div class="collapse" id="collapseExterior">
                            @php($subcategories = $categoriesList->subCategoriesWithProductsAndStock2("EXTERIOR"))
                            <ul>
                                @foreach($subcategories as $subcategory)
                                <li class="nav-item">
                                    <a class="nav-link text-white text-center" href="{{route('category-products',['slug' => 'exterior','child' => $subcategory->slug])}}">
                                        <h1 style="font:20px/18px 'minion-pro', Times, 'Times New Roman'">{{$subcategory->name}}</h1>
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </li>
                    <li id="" class="nav-item ">
                        <a class="nav-link text-white text-center" data-toggle="collapse" href="#collapseMantenimiento"
                            role="button" aria-expanded="false" aria-controls="collapseMantenimiento">
                            <h1 style="font:20px/18px 'minion-pro', Times, 'Times New Roman'">MANTENIMIENTO</h1>
                        </a>
                        <div class="collapse" id="collapseMantenimiento">
                            @php($subcategories = $categoriesList->subCategoriesWithProductsAndStock2("MANTENIMIENTO"))
                            <ul>
                                @foreach($subcategories as $subcategory)
                                <li class="nav-item">
                                    <a class="nav-link text-white text-center" href="{{route('category-products',['slug' => 'mantenimiento','child' => $subcategory->slug])}}">
                                        <h1 style="font:20px/18px 'minion-pro', Times, 'Times New Roman'">{{$subcategory->name}}</h1>
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </li>
                    <li id="" class="nav-item ">
                        <a class="nav-link text-white text-center" data-toggle="collapse" href="#collapseMaterialContruccion"
                            role="button" aria-expanded="false" aria-controls="collapseMaterialContruccion">
                            <h1 style="font:20px/18px 'minion-pro', Times, 'Times New Roman'">MATERIAL CONSTRUCCIÓN</h1>
                        </a>
                        <div class="collapse" id="collapseMaterialContruccion">
                            @php($subcategories = $categoriesList->subCategoriesWithProductsAndStock2("MATERIAL CONSTRUCCION"))
                            <ul>
                                @foreach($subcategories as $subcategory)
                                <li class="nav-item">
                                    <a class="nav-link text-white text-center" href="{{route('category-products',['slug' => 'material contruccion','child' => $subcategory->slug])}}">
                                        <h1 style="font:20px/18px 'minion-pro', Times, 'Times New Roman'">{{$subcategory->name}}</h1>
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </li>
                    <li id="" class="nav-item ">
                        <a class="nav-link text-white text-center" data-toggle="collapse" href="#collapseOutdoor"
                            role="button" aria-expanded="false" aria-controls="collapseOutdoor">
                            <h1 style="font:20px/18px 'minion-pro', Times, 'Times New Roman'">OUTDOOR</h1>
                        </a>
                        <div class="collapse" id="collapseOutdoor">
                            @php($subcategories = $categoriesList->subCategoriesWithProductsAndStock2("OUTDOOR"))
                            <ul>
                                @foreach($subcategories as $subcategory)
                                <li class="nav-item">
                                    <a class="nav-link text-white text-center" href="{{route('category-products',['slug' => 'outdoor','child' => $subcategory->slug])}}">
                                        <h1 style="font:20px/18px 'minion-pro', Times, 'Times New Roman'">{{$subcategory->name}}</h1>
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </li>
                    <li id="" class="nav-item ">
                        <a class="nav-link text-white text-center" data-toggle="collapse" href="#collapseChapas"
                            role="button" aria-expanded="false" aria-controls="collapseChapas">
                            <h1 style="font:20px/18px 'minion-pro', Times, 'Times New Roman'">CHAPAS</h1>
                        </a>
                        <div class="collapse" id="collapseChapas">
                            @php($subcategories = $categoriesList->subCategoriesWithProductsAndStock2("CHAPAS"))
                            <ul>
                                @foreach($subcategories as $subcategory)
                                <li class="nav-item">
                                    <a class="nav-link text-white text-center" href="{{route('category-products',['slug' => 'chapas','child' => $subcategory->slug])}}">
                                        <h1 style="font:20px/18px 'minion-pro', Times, 'Times New Roman'">{{$subcategory->name}}</h1>
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </li>
                    <li id="" class="nav-item ">
                        <a class="nav-link text-white text-center" href="{{route('settlement-products')}}">
                            <h1 style="font:20px/18px 'minion-pro', Times, 'Times New Roman'">PRODUCTOS LIQUIDACIÓN</h1>
                        </a>
                    </li>
                    <li id="" class="nav-item ">
                        <a class="nav-link text-white text-center" href="{{route('offers-products')}}">
                            <h1 style="font:20px/18px 'minion-pro', Times, 'Times New Roman'">OFERTAS</h1>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</div>