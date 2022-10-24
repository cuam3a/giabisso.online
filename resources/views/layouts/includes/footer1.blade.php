<?php
    $categories = App\Models\Category::where('parent_id',null)->orderBy('order','ASC')->get();
    $obj_cat = new App\Models\Category;
    $params = Route::current() ? array_key_exists('id',Route::current()->parameters()) ? [] : Route::current()->parameters() : [];
?>
<div class="footer text-white" style="background-color:#0D0D32">
    <div class="container">
        <div class="row">
            {{-- <div class="col-md-6 col-lg-2">
                <h4 class="title text-white">Nosotros</h4>
                <p>Magna irure laborum adipisicing aliqua nisi magna voluptate magna aute sunt magna magna Lorem laboris.</p>
            </div> --}}
            <div class="col-md-6 col-lg-3">
                <h4 class="title text-white">Nosotros</h4>
                <p class="d-flex justify-content-end desktop">
                    Somos una tienda de Comercio Electrónico que ofrece en mayoreo y menudeo productos para todas las áreas del Hogar, mantenimiento y equipamiento de casas y edificios en mas de 20 categorías diferentes
                </p>
            </div>
            <div class="col-md-6 col-lg-2">

                <h4 class="title text-white">Contacto</h4>
                {{-- <p>¿Tienes dudas? Llámanos y te atenderemos</p> --}}

                <ul class="list-unstyled">
                    @if($global_contact_phone != '')
                    <li>
                        <i class="la la-phone-square"></i> 
                        <a href="tel:{{$global_contact_phone}}">
                                <b>{{"(".substr($global_contact_phone, 0, 3).") ".substr($global_contact_phone, 3, 3).".".substr($global_contact_phone,6,2).".".substr($global_contact_phone,8)}}</b>
                        </a>
                    </li>
                    @endif

                    @if($global_contact_phone_2 != '')
                    <li>
                        <i class="la la-phone-square"></i> 
                        <a href="tel:{{$global_contact_phone_2}}">
                            <b>{{"(".substr($global_contact_phone_2, 0, 3).") ".substr($global_contact_phone_2, 3, 3).".".substr($global_contact_phone_2,6,2).".".substr($global_contact_phone_2,8)}}</b>
                        </a>
                    </li>
                    @endif

                    @if($global_contact_email != '')
                    <li>
                        <i class="la la-envelope"></i> 
                        <a href="mailto:{{$global_contact_email}}" title="{{$global_contact_email}}">
                            <b>ESCRÍBENOS </b>
                        </a>
                    </li>
                    @endif

                    <li>
                    <!-- Social links -->
                    @if(count($global_social_links)>0)
                        <p style="margin-top:5px;margin-bottom:0px;"><b style="width:100%">SíGUENOS EN:</b></p>
                        @foreach($global_social_links as $key=>$link)
                            @if($link != '')
                                <a style="font-size:26px;color:#5aa407;margin-top:0px;" href="{{$link}}"><i class="la la-{{$key}}"></i></a>
                            @endif
                        @endforeach

                    @endif
                    </li>
                </ul>
            </div>
            {{-- <div class="col-md-6 col-lg-2">
                <h4 class="title text-white">La tienda</h4>
                <ul class="list-unstyled">
                @php($var_categories = $obj_cat->categoriesWithProductsAndStock('', 6))
                @foreach($var_categories as $category)
                    <li>
                        <a href="{{route('category-products',['slug' => $category->slug])}}">{{$category->name}}</a>
                    </li>
                @endforeach
                </ul>
            </div> --}}
            <div class="col-md-6 col-lg-2">
                <h4 class="title text-white">Mi cuenta</h4>
                <ul class="list-unstyled">
                    <li>
                        <a href="{{route('my-account')}}">Mi cuenta</a>
                    </li>
                    <li>
                        <a href="{{route('about-us')}}">Nosotros</a>
                    </li>
                    <li>
                        <a href="{{route('contact-us')}}">Contacto</a>
                    </li>
                    <li>
                        <a href="{{route('my-account')}}#mis-pedidos" class="account_tab">Pedidos</a>
                    </li>
                    <li>
                        <a @if(Auth::guard('customer-web')->check()) href="{{route('my-account')}}#favoritos"  class="account_tab" @else href="#" class="void modalLogin account_tab" @endif>Favoritos</a>
                    </li>
                    <li>
                        <a href="{{route('faq')}}">Preguntas frecuentes</a>
                    </li>
                </ul>
            </div>
            <div class="col-md-6 col-lg-2">
                <h4 class="title text-white">LEGAL</h4>
                <ul class="list-unstyled">
                    <li>
                        <a class="account_tab" href="{{route('privacy')}}">Aviso de privacidad</a>
                    </li>
                    <li>
                        <a href="{{route('policy')}}">Políticas</a>
                    </li>
                </ul>
            </div>
            <div class="col-md-6 col-lg-3">
                <!--<h4 class="title">Métodos de pago</h4>
                <ul class="list-inline">
                    <li class="list-inline-item">
                        <img src="{{ asset('/website/img/ico-mercado-pago.png') }}" alt="Mercado Pago" />
                    </li>
                    <li class="list-inline-item">
                        <img src="{{ asset('/website/img/icon-visa.png') }}" alt="Visa" />
                    </li>
                    <li class="list-inline-item">
                        <img src="{{ asset('/website/img/ico-master.png') }}" alt="Mastercard" />
                    </li>
                    <li class="list-inline-item">
                        <img src="{{ asset('/website/img/ico-spei.png') }}" alt="SPEI" />
                    </li>
                    <li class="list-inline-item">
                        <img src="{{ asset('/website/img/ico-oxxo.png') }}" alt="OXXO" />
                    </li>
                </ul>-->

            </div>
            {{-- <div class="d-none col-md-6 d-md-block d-lg-none">
                <h4 class="title">Seguridad</h4>
                <ul class="list-inline">
                    <li class="list-inline-item">
                        <img src="{{ asset('/website/img/ico-hec-18.png') }}" alt="COMODO" />
                    </li>
                </ul>
            </div> --}}
        </div>
    </div>
</div>

<div class="rights" style="background-color:#91CD3E">
    <div class="container">
        <div class="row">
            <div class="col-6 text-left"><a class="text-white" href="{{route('website-home')}}">Justo A Tiempo 2019</a></div>
            <div class="col-6 text-right text-white">Todos los Derechos Reservados</div>
        </div>
    </div>
</div>