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
{{-- <div class="contact-div">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-xs-12">
                <div class="row no-gutters">
                    <div class="col-md-6 col-xs-12" style="padding-top:5px;">
                        <span>
                            @if($global_contact_phone != '')
                            <span style="margin-left:10px">
                                <i style="color:#5aa407" class="la la-phone-square"></i> 
                                <a href="tel:{{$global_contact_phone}}">
                                        <b>{{"(".substr($global_contact_phone, 0, 3).") ".substr($global_contact_phone, 3, 3).".".substr($global_contact_phone,6,2).".".substr($global_contact_phone,8)}}</b>
                                </a>
                            </span>
                            @endif
                            @if($global_contact_phone_2 != '')
                            <span style="margin-left:10px">
                                <a href="tel:{{$global_contact_phone_2}}">
                                        <b>{{"(".substr($global_contact_phone_2, 0, 3).") ".substr($global_contact_phone_2, 3, 3).".".substr($global_contact_phone_2,6,2).".".substr($global_contact_phone_2,8)}}</b>
                                </a>
                            </span>
                            @endif
                        </span>
                        @if($global_contact_email != '')
                            <span style="margin-left:10px">
                                <i style="color:#5aa407"  class="la la-envelope"></i> 
                                <a href="mailto:{{$global_contact_email}}">
                                    <b>{{$global_contact_email}}</b>
                                </a>
                            </span>
                        @endif
                    </div>
                    <div class="col-md-6 col-xs-12 text-right">
                        <!-- Social links -->
                        @if($has_social_links == 1)
                            <span style="color:#757575">Síguenos en:</span>
                            @foreach($global_social_links as $key=>$link)
                                @if($link != '')
                                    <a style="font-size:26px; color:#5aa407" href="{{$link}}"><i class="la la-{{$key}}"></i></a>
                                @endif
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> --}}

<div class="header" style="background-color:#0D0D32">
    <div class="row no-gutters d-lg-none">
        
        <div class="col-2">
             <button class="item navbar-toggler bg-green pull btnMobileCategories" onclick="myFunction()" type="button" aria-expanded="false" aria-label="Toggle navigation">
            {{-- <button class="item navbar-toggler bg-green pull" type="button" data-toggle="collapse" data-target="#main_menu_mobile" aria-controls="main_menu_mobile" aria-expanded="false" aria-label="Toggle navigation"> --}}
                <img src="{{ asset('/website/img/menublanco-01.png') }}" alt="Menú">
            </button>
        </div>

        <div class="col-1">
            <div class="item">
                <a class="search-link">
                    <img src="{{ asset('/website/img/ico-hec-04.png') }}" alt="Buscar">
                </a>
            </div>
        </div>

        <div class="col-3">
            <div class="logo">
            <!--LOGO-->
                <a href="{{ route('website-home') }}">
                    <img src="{{ asset('/website/img/Logo-JAT.png') }}" alt="Justo a Tiempo" />
                </a>
                <!--<a href="{{ route('website-home') }}"><h3 class="font-weight-bold mt-4">Justo a Tiempo</h3></a>-->
            </div>
        </div>
    
        <div class="col-2">
            <div class="item">
                <a href="{{route('cart')}}">
                    <img src="{{ asset('/website/img/ico-hec-26.png') }}" alt="Carrito">
                    <span class="badge badge-success">{{Cart::count()}}</span>
                </a>
            </div>
        </div>
        <div class="col-2">
            <div class="item">
                <a href="{{route('programming')}}">
                    <img src="{{ asset('/website/img/ico-hec-27.png') }}" alt="Carrito">
                    <span class="badge badge-success">{{ $orderProgrammerViews->count($customer_id) }}</span>
                </a>
            </div>
        </div>

        <div class="col-2" id="mobile-account">

            @if(!Auth::guard('customer-web')->check())
            <div class="dropdown">
                <a href="{{ route('website-login') }}" class="btn btn-secondary dropdown-toggle my-account-btn"><img src="{{ asset('/website/img/ico-hec-07.png') }}" alt="Mi cuenta"></a>
            </div>
            @else
            <div class="dropdown">
                <a class="btn btn-secondary dropdown-toggle my-account-btn" type="button" id="my_account" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img src="{{ asset('/website/img/ico-hec-07.png') }}" alt="Mi cuenta">
                </a>
                <div class="dropdown-menu my-account-menu" aria-labelledby="my_account">
                    <a class="dropdown-item" href="{{route('my-account')}}">Mi cuenta</a>
                    <a class="dropdown-item" href="{{route('logout-customer')}}">Cerrar sesión</a>
                </div>
            </div>
            @endif
        </div>

    </div>

    <div class="row no-gutters d-lg-none header-search">
        <div class="col-1">
            <div class="item">
                <a class="close-search">
                    <i class="fa fa-times"></i>
                </a>
            </div>
        </div>
        <div class="col-9">
            <div class="item">
                <input class="search-mobile" type="text" placeholder="Buscar Productos" autocomplete="off"/>
            </div>
        </div>
        <div class="col-2">
            <div class="item btnMobile">
                <img src="{{ asset('/website/img/ico-hec-04.png') }}" alt="Buscar">
            </div>
        </div>
    </div>

    <div class="row no-gutters d-lg-none">
        <div class="col-12">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="collapse navbar-collapse" id="main_menu_mobile">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item active">
                            <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Link</a>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </div>

    <div class="container" style="background-color:#0D0D32">
        <div class="row d-none d-lg-flex">
            <div class="col-2">
                <div class="logo">
                <!--LOGO-->
                    <a href="{{ route('website-home') }}">
                        <img src="{{ asset('/website/img/Logo-JAT.png') }}" alt="Justo a Tiempo" />
                    </a>
                    <!--<a href="{{ route('website-home') }}"><h3 class="font-weight-bold mt-4">Justo a Tiempo</h3></a>-->
                </div>
            </div>
            <div class="col-10">
                <div class="row">
                    <div class="col-lg-5 m-top-10 text-white">
                        Contacto:
                        @if($global_contact_phone != '')
                        <span>
                            <i style="color:#5aa407" class="la la-phone-square"></i> 
                            <a href="tel:{{$global_contact_phone}}">
                                    <b>{{"(".substr($global_contact_phone, 0, 3).") ".substr($global_contact_phone, 3, 3).".".substr($global_contact_phone,6,2).".".substr($global_contact_phone,8)}}</b>
                            </a>
                        </span>
                        @endif
                        @if($global_contact_phone_2 != '')
                        <span >
                            <a href="tel:{{$global_contact_phone_2}}">
                                    <b>{{"(".substr($global_contact_phone_2, 0, 3).") ".substr($global_contact_phone_2, 3, 3).".".substr($global_contact_phone_2,6,2).".".substr($global_contact_phone_2,8)}}</b>
                            </a>
                        </span>
                        @endif
                        @if($global_contact_email != '')
                        <p>Correo: 
                            <i style="color:#5aa407" class="la la-envelope"></i>
                            <a href="mailto:{{$global_contact_email}}"> 
                                <b>{{$global_contact_email}}</b>
                            </a>
                        </p>
                        @endif
                    </div>
                    <div class="col-lg-7">
                        <nav class="navbar navbar-expand-lg navbar-light bg-light pull-right">
                            <div class="collapse navbar-collapse" id="main_menu">
                                <ul class="navbar-nav mr-auto" style="background-color:#91CD3E">
                                    <li class="nav-item d-flex align-items-cente">
                                        <a class="nav-link" href="{{route('concepto')}}">
                                            <span class="text-white">CONCEPTO</span>
                                        </a>
                                    </li>
                                    <li class="nav-item d-flex align-items-cente">
                                        <a class="nav-link" href="{{route('nosotros')}}">
                                            <span class="text-white">NOSOTROS</span>
                                        </a>
                                    </li>
                                    <li class="nav-item d-flex align-items-cente">
                                        <a class="nav-link" href="{{route('politica-precios')}}">
                                            <span class="text-white">POLÍTICA DE PRECIOS</span>
                                        </a>
                                    </li>
                                    <li class="nav-item d-flex align-items-cente">
                                        <a class="nav-link" href="{{route('ventajas')}}">
                                            <span class="text-white">VENTAJAS</span>
                                        </a>
                                    </li>
                                    <li class="nav-item d-flex align-items-cente">
                                        <a class="nav-link" href="{{route('beneficios')}}">
                                            <span class="text-white">BENEFICIOS</span>
                                        </a>
                                    </li>
                                    <li class="nav-item d-flex align-items-cente">
                                        <a class="nav-link" href="{{route('contacto')}}">
                                            <span class="text-white">CONTACTO</span>
                                        </a>
                                    </li>
                                    <li class="nav-item d-flex align-items-cente"><!-- clase active disponible-->
                                        <a  @if(Auth::guard('customer-web')->check()) class="nav-link" href="{{route('my-account')}}#mis-pedidos" @else href="#" data-msg="Accede a tu cuenta para ver tus pedidos" data-title="Pedidos" class="nav-link void modalLogin" @endif>
                                            <span class="text-white">MIS PEDIDOS</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </nav>
                    </div>

                </div>
                <div class="row m-top-10">
                    <div class="col-lg-2">
                        <div class="dropdown item" style="background-color:#91CD3E">
                            <button class="btn dropdown-toggle btn-menu-categories text-white" type="button" id="store" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="background-color:#91CD3E" >
                                <span >Productos</span>
                                {{-- <img src="{{ asset('/website/img/ico-hec-08.png') }}" alt="Productos"> --}}
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="store">

                                @php($parentcategories = $categoriesList->categoriesWithProductsAndStock())
                                
                                @foreach($parentcategories as $category)
                                
                                    @php($subcategories = $categoriesList->subCategoriesWithProductsAndStock($category->id))

                                    <li @if(count($subcategories) > 0) class="dropdown-submenu text-white" @endif>    
                                        <a href="{{route('category-products',['slug' => $category->slug])}}" class="dropdown-item" data-toggle="dropdown">{{$category->name}}</a>
                                        <ul class="dropdown-menu" @if(count($parentcategories) < count($subcategories) ) style="height:auto" @endif>
                                            @foreach($subcategories as $subcategory)
                                                <li><a href="{{route('category-products',['slug' => $category->slug,'child' => $subcategory->slug])}}" class="dropdown-item">{{$subcategory->name}}</a></li>
                                            @endforeach
                                        </ul>
                                    </li>
                                        
                                    
                                @endforeach
                                {{-- @foreach($categories as $category)
                                    <li @if($category->subcategory->count() > 0) class="dropdown-submenu" @endif>
                                        <a href="{{route('category-products',['slug' => $category->slug])}}" class="dropdown-item" data-toggle="dropdown">{{$category->name}}</a>
                                        @if($category->subcategory->count() > 0)                                        
                                        <ul class="dropdown-menu">
                                            @foreach($category->subcategory as $subcategory)
                                                @if($subcategory->productsActivesAndStock->count() > 0)
                                                    <li><a href="{{route('category-products',['slug' => $category->slug,'child' => $subcategory->slug])}}" class="dropdown-item">{{$subcategory->name}}</a></li>
                                                @endif
                                            @endforeach
                                        </ul>
                                        @endif
                                    </li>
                                @endforeach --}}
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <form action="{{route('search-products',$params)}}" method="GET" class="frmMenuSearch">
                            <div class="input-group">
                                <input type="text" class="form-control search-pc" name="s" placeholder="Buscar" aria-label="Search" aria-describedby="menu-search" value="{{@$_GET['s']}}"  autocomplete="off">
                                <button type="submit">
                                    <span class="input-group-addon" id="menu-search">
                                        <img src="{{ asset('/website/img/ico-hec-04.png') }}" alt="Buscar">
                                    </span>
                                </button>
                            </div>
                            <?php unset($_GET['s']);//quito busqueda('s') por ser valor default en buscador ?>
                            @foreach($_GET as $name => $value)
                                <input name="{{$name}}" type="hidden" value="{{$value}}"><!-- filtros ocultos-->
                            @endforeach
                        </form>
                    </div>
                    <div class="col-lg-7">
                        <div class="row">
                            <div class="col-4">
                                <!--<a @if(Auth::guard('customer-web')->check()) href="{{route('my-account')}}#favoritos" @else href="#" data-title="Favoritos" data-msg="Accede a tu cuenta para ver tus favoritos" class="void modalLogin" @endif>
                                    <div class="item styled rounded" style="background-color:#91CD3E">
                                            <img src="{{ asset('/website/img/hec_fav.svg') }}" class="inline-svg svg-white" alt="Favoritos">
                                            span class="">(9)</span>
                                    </div>
                                </a>-->
                                
                                    <a href="{{route('settlement-products')}}" class="header-button rounded text-white" style="background-color:#E62020">
                                    <strong>Productos en Liquidación</strong>
                                    </a>
                                
                            </div>
                            <div class="col-2">
                                <div class="item styled rounded" style="background-color:#91CD3E">
                                    <a href="{{route('cart')}}">
                                        <img src="{{ asset('/website/img/hec_order.svg') }}" class="inline-svg svg-white" alt="Carrito">
                                        <span class="text-white">({{Cart::count()}})</span>
                                    </a>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="item styled rounded" style="background-color:#91CD3E"> 
                                    <a href="{{route('programming')}}">
                                        <img src="{{ asset('/website/img/hec_order_programming.svg') }}" class="inline-svg svg-white" alt="Carrito">
                                        <span class="text-white">({{ $orderProgrammerViews->count($customer_id) }})</span>
                                    </a>
                                </div>
                            </div>
                            <div class="col-4">
                                @if(!Auth::guard('customer-web')->check())
                                <a href="{{ route('website-login') }}" class="header-button rounded text-white" style="background-color:#91CD3E"><img src="{{ asset('/website/img/ico-hec-07.png') }}" alt="Mi cuenta"> Mi cuenta</a>
                                @else
                                <div class="dropdown item" style="background-color:#91CD3E">
                                    <button class="btn btn-secondary dropdown-toggle text-white" type="button" id="my_account" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="background-color:#91CD3E">
                                        <img src="{{ asset('/website/img/ico-hec-07.png') }}" alt="Mi cuenta">{{Auth::user()->name}}
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="my_account">
                                        <a class="dropdown-item" href="{{route('my-account')}}">Mi cuenta</a>
                                        <a class="dropdown-item" href="{{route('logout-customer')}}">Cerrar sesión</a>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row m-top-10">
                    <div class="col-lg-10" style="display:none">
                        <nav class="navbar navbar-expand-lg navbar-light bg-light">
                            <div class="collapse navbar-collapse" id="main_menu">
                                <ul class="navbar-nav mr-auto">
                                    @foreach($categories->take(5) as $category)
                                        <li class="nav-item"><!-- clase active disponible-->
                                            <a class="nav-link" href="{{route('category-products',['slug' => $category->slug])}}">
                                                <i class="{{$category->icon}}"></i>{{$category->name}}<span class="sr-only">(current)</span>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('layouts.includes.mobile.categories')

<!--<li class="nav-item d-flex align-items-cente">
        <a class="nav-link" href="">
            <span class="text-white">NOSOTROS</span>
        </a>
    </li>
                                    <li class="nav-item d-flex align-items-cente">
                                        <a class="nav-link" href="{{route('contact-us')}}">
                                            <span class="text-white">CONTACTO</span>
                                        </a>
                                    </li>
                                    <li class="nav-item d-flex align-items-center">
                                        <a class="nav-link text-white href="{{route('faq')}}">
                                                <i  style="color:#FFFFFF" class="fa fa-question-circle"></i><span>AYUDA</span>
                                        </a>
                                    </li>
                                    <li class="nav-item d-flex align-items-cente">
                                        <a class="nav-link">
                                            <span class="text-white">SÍGUENOS</span>
                                            @if($has_social_links == 1)
                                                @foreach($global_social_links as $key=>$link)
                                                    @if($link != '')
                                                        <a style="color:#5aa407" href="{{$link}}"><i class="la la-{{$key}}"></i></a>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </a>
                                    </li>-->