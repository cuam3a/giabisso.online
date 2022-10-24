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

<div class="header__container" style="background-color:rgba(13, 13, 50, 0.85);; position:relative">
    <div class="header-wrapper">
		<div class="header-wrapper__ie-fix"></div>
            <div id="header" class="header">
		    	<div class="logo-wrapper">
                    <a href="{{ route('website-home-jat') }}" class="logo" alt="RH Logo" title="Inicio">
                    <img src="{{ asset('/website/img/Logo-JAT.png') }}" alt="..." class="mb-2"  width="90" height="50"/>
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
								<a href="{{route('concepto-JAT')}}">
                                    CONCEPTO
								</a>
                            </li>
                            <li id="uber-nav-rhbc-link" class="js-uber-nav__brand uber-nav__brand ">
								<a href="{{route('nosotros-JAT')}}">
                                    NOSOTROS
								</a>
                            </li>
                            <li id="uber-nav-rhtn-link" class="js-uber-nav__brand uber-nav__brand ">
								<a href="{{route('politica-precios-JAT')}}">
                                POLITICA DE PRECIOS
								</a>
							</li>
							<li id="uber-nav-design-link" class="js-uber-nav__brand uber-nav__brand">
                                <a href="{{route('ventajas-JAT')}}">
                                    VENTAJAS
                                </a>
                            </li>
                            <li id="uber-nav-design-link" class="js-uber-nav__brand uber-nav__brand">
                                <a href="{{route('beneficios-JAT')}}">
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
                        <li><a href="/rooms/?sale=false">PROGRAMACIÓN ({{ $orderProgrammerViews->count($customer_id) }})</a></li>
                        <li><a href="/rooms/?sale=false">PEDIDOS ({{Cart::count()}})</a></li>
                        @if(!Auth::guard('customer-web')->check())
                            <li><a href="{{ route('website-login-jat') }}">MI CUENTA</a></li>
                        @else
                            <li><a href="{{route('my-account-jat')}}">{{Auth::user()->name}}</a></li>
                        @endif
					</ul>
		            <div class="js-search-form search-form" hidden id="searchForm">
			            <form method="get" action="{{route('search-products-JAT',$params)}}" formid="header-searchform" name="header-searchform" class="hasrequired header-search">
				            <input title="search" placeholder="Buscar.." type="text" maxlength="255" name="s" autocomplete="off" class="search-input js-search-input required" value="{{@$_GET['s']}}">
				            <div class="search-input-container">
						        <button id="search-input-submit" class="search-input-submit" name="go" priority="-10" alt="Go">Go</button>
				            </div>
				            <div class="search-typeahead-dropped hidden">
					            <div class="x-close js-close-search" title="Close" role="button" aria-label="Close Search" tabindex="0">
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
                        <div class="bottomlvl-cat-header-wrapper child-li border border-secondary" style="visibility: hidden; height: 443px; z-index:3; position:absolute">
                            <ul style="height: 443px;">
                                @foreach($subcategories as $subcategory)
                                <li style="" class="bottomlvl-cat-header" id="cat160024-cat1481016" data-catid="cat1481016">
                                    <a class="text-capitalize" href="{{route('category-products-JAT',['slug' => 'cocina','child' => $subcategory->slug])}}">{{$subcategory->name}}</a>
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
                        <div class="bottomlvl-cat-header-wrapper child-li border border-secondary" style="visibility: hidden; height: 443px;">
                            <ul style="height: 443px;">
                                @foreach($subcategories as $subcategory)
                                <li style="" class="bottomlvl-cat-header default-li-selected" id="cat160024-cat1481016" data-catid="cat1481016">
                                    <a class="text-capitalize" href="{{route('category-products-JAT',['slug' => 'lavanderia','child' => $subcategory->slug])}}">{{$subcategory->name}}</a>
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
                        <div class="bottomlvl-cat-header-wrapper child-li border border-secondary" style="visibility: hidden; height: 443px;">
                            <ul style="height: 443px;">
                                @foreach($subcategories as $subcategory)
                                <li style="" class="bottomlvl-cat-header" id="cat160024-cat1481016" data-catid="cat1481016">
                                    <a class="text-capitalize" href="{{route('category-products-JAT',['slug' => 'closets','child' => $subcategory->slug])}}">{{$subcategory->name}}</a>
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
                        <div class="bottomlvl-cat-header-wrapper child-li border border-secondary" style="visibility: hidden; height: 800px;">
                            <ul style="height: 800px;">
                                @foreach($subcategories as $subcategory)
                                <li style="" class="bottomlvl-cat-header" id="cat160024-cat1481016" data-catid="cat1481016">
                                    <a class="text-capitalize" href="{{route('category-products-JAT',['slug' => 'herramienta','child' => $subcategory->slug])}}">{{$subcategory->name}}</a>
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
                        <div class="bottomlvl-cat-header-wrapper child-li border border-secondary" style="visibility: hidden; height: 550px;">
                            <ul style="height: 550px;">
                                @foreach($subcategories as $subcategory)
                                <li style="" class="bottomlvl-cat-header" id="cat160024-cat1481016" data-catid="cat1481016">
                                    <a class="text-capitalize" href="{{route('category-products-JAT',['slug' => 'automotriz','child' => $subcategory->slug])}}">{{$subcategory->name}}</a>
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
                        <div class="bottomlvl-cat-header-wrapper child-li border border-secondary" style="visibility: hidden; height: 800px;">
                            <ul style="height: 800px;">
                                @foreach($subcategories as $subcategory)
                                <li style="" class="bottomlvl-cat-header" id="cat160024-cat1481016" data-catid="cat1481016">
                                    <a class="text-capitalize" href="{{route('category-products-JAT',['slug' => 'ferreteria','child' => $subcategory->slug])}}">{{$subcategory->name}}</a>
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
                        <div class="bottomlvl-cat-header-wrapper child-li border border-secondary" style="visibility: hidden; height: 443px;">
                            <ul style="height: 443px;">
                                @foreach($subcategories as $subcategory)
                                <li style="" class="bottomlvl-cat-header" id="cat160024-cat1481016" data-catid="cat1481016">
                                    <a class="text-capitalize" href="{{route('category-products-JAT',['slug' => 'impulso','child' => $subcategory->slug])}}">{{$subcategory->name}}</a>
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
                        <div class="bottomlvl-cat-header-wrapper child-li border border-secondary" style="visibility: hidden; height: 650px;">
                            <ul style="height: 650px;">
                                @foreach($subcategories as $subcategory)
                                <li style="" class="bottomlvl-cat-header" id="cat160024-cat1481016" data-catid="cat1481016">
                                    <a class="text-capitalize" href="{{route('category-products-JAT',['slug' => 'electrico','child' => $subcategory->slug])}}">{{$subcategory->name}}</a>
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
                        <div class="bottomlvl-cat-header-wrapper child-li border border-secondary" style="visibility: hidden; height: 460px;">
                            <ul style="height: 460px;">
                                @foreach($subcategories as $subcategory)
                                <li style="" class="bottomlvl-cat-header" id="cat160024-cat1481016" data-catid="cat1481016">
                                    <a class="text-capitalize" href="{{route('category-products-JAT',['slug' => 'pintura','child' => $subcategory->slug])}}">{{$subcategory->name}}</a>
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
                        <div class="bottomlvl-cat-header-wrapper child-li border border-secondary" style="visibility: hidden; height: 443px;">
                            <ul style="height: 443px;">
                                @foreach($subcategories as $subcategory)
                                <li style="" class="bottomlvl-cat-header" id="cat160024-cat1481016" data-catid="cat1481016">
                                    <a class="text-capitalize" href="{{route('category-products-JAT',['slug' => 'jardin','child' => $subcategory->slug])}}">{{$subcategory->name}}</a>
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
                        <div class="bottomlvl-cat-header-wrapper child-li border border-secondary" style="visibility: hidden; height: 480px;">
                            <ul style="height: 480px;">
                                @foreach($subcategories as $subcategory)
                                <li style="" class="bottomlvl-cat-header" id="cat160024-cat1481016" data-catid="cat1481016">
                                    <a class="text-capitalize" href="{{route('category-products-JAT',['slug' => 'limpieza','child' => $subcategory->slug])}}">{{$subcategory->name}}</a>
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
                        <div class="bottomlvl-cat-header-wrapper child-li border border-secondary" style="visibility: hidden; height: 443px;">
                            <ul style="height: 443px;">
                                @foreach($subcategories as $subcategory)
                                <li style="" class="bottomlvl-cat-header" id="cat160024-cat1481016" data-catid="cat1481016">
                                    <a class="text-capitalize" href="{{route('category-products-JAT',['slug' => 'iluminacion','child' => $subcategory->slug])}}">{{$subcategory->name}}</a>
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
                        <div class="bottomlvl-cat-header-wrapper child-li border border-secondary" style="visibility: hidden; height: 500px;">
                            <ul style="height: 500px;">
                                @foreach($subcategories as $subcategory)
                                <li style="" class="bottomlvl-cat-header" id="cat160024-cat1481016" data-catid="cat1481016">
                                    <a class="text-capitalize" href="{{route('category-products-JAT',['slug' => 'plomeria','child' => $subcategory->slug])}}">{{$subcategory->name}}</a>
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
                        <div class="bottomlvl-cat-header-wrapper child-li border border-secondary" style="visibility: hidden; height: 443px;">
                            <ul style="height: 443px;">
                                @foreach($subcategories as $subcategory)
                                <li style="" class="bottomlvl-cat-header" id="cat160024-cat1481016" data-catid="cat1481016">
                                    <a class="text-capitalize" href="{{route('category-products-JAT',['slug' => 'ventilacion','child' => $subcategory->slug])}}">{{$subcategory->name}}</a>
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
                        <div class="bottomlvl-cat-header-wrapper child-li border border-secondary" style="visibility: hidden; height: 443px;">
                            <ul style="height: 443px;">
                                @foreach($subcategories as $subcategory)
                                <li style="" class="bottomlvl-cat-header" id="cat160024-cat1481016" data-catid="cat1481016">
                                    <a class="text-capitalize" href="{{route('category-products-JAT',['slug' => 'exterior','child' => $subcategory->slug])}}">{{$subcategory->name}}</a>
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
                        <div class="bottomlvl-cat-header-wrapper child-li border border-secondary" style="visibility: hidden; height: 443px;">
                            <ul style="height: 443px;">
                                @foreach($subcategories as $subcategory)
                                <li style="" class="bottomlvl-cat-header" id="cat160024-cat1481016" data-catid="cat1481016">
                                    <a class="text-capitalize" href="{{route('category-products-JAT',['slug' => 'mantenimiento','child' => $subcategory->slug])}}">{{$subcategory->name}}</a>
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
                        @php($subcategories = $categoriesList->subCategoriesWithProductsAndStock2("MATERIAL CONSTRUCCION"))
                        <div class="bottomlvl-cat-header-wrapper child-li border border-secondary" style="visibility: hidden; height: 443px;">
                            <ul style="height: 443px;">
                                @foreach($subcategories as $subcategory)
                                <li style="" class="bottomlvl-cat-header" id="cat160024-cat1481016" data-catid="cat1481016">
                                    <a class="text-capitalize" href="{{route('category-products-JAT',['slug' => 'material contruccion','child' => $subcategory->slug])}}">{{$subcategory->name}}</a>
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
                        <div class="bottomlvl-cat-header-wrapper child-li border border-secondary" style="visibility: hidden; height: 443px;">
                            <ul style="height: 443px;">
                                @foreach($subcategories as $subcategory)
                                <li style="" class="bottomlvl-cat-header" id="cat160024-cat1481016" data-catid="cat1481016">
                                    <a class="text-capitalize" href="{{route('category-products-JAT',['slug' => 'outdoor','child' => $subcategory->slug])}}">{{$subcategory->name}}</a>
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
                        <div class="bottomlvl-cat-header-wrapper child-li border border-secondary" style="visibility: hidden; height: 443px;">
                            <ul style="height: 443px;">
                                @foreach($subcategories as $subcategory)
                                <li style="" class="bottomlvl-cat-header" id="cat160024-cat1481016" data-catid="cat1481016">
                                    <a class="text-capitalize" href="{{route('category-products-JAT',['slug' => 'chapas','child' => $subcategory->slug])}}">{{$subcategory->name}}</a>
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
</div>

