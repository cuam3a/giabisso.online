<?php
    $categories = App\Models\Category::where('parent_id',null)->orderBy('order','ASC')->get();
    $obj_cat = new App\Models\Category;
    $params = Route::current() ? array_key_exists('id',Route::current()->parameters()) ? [] : Route::current()->parameters() : [];
?>
<div class="footer-container">
    <div id="footer-menus">
        <div class="grid grid--wide">
            <div class="grid__item two-tenths  menu">           
            <h3>EMPRESA</h3>
            <ul>
                <li><a href="{{ route('website-home-jat') }}">Inicio</a></li>

                <li><a href="{{route('concepto-JAT')}}">Concepto</a></li>

                <li><a href="{{route('nosotros-JAT')}}">Nosotros</a></li>

                <li><a href="{{route('politica-precios-JAT')}}">Politica de Precios</a></li>

                <li><a href="{{route('ventajas-JAT')}}">Ventajas</a></li>

                <li><a href="{{route('beneficios-JAT')}}">Beneficios</a></li>

            </ul>
        </div>

        <div class="grid__item two-tenths  menu">
            <h3>CATEGORIAS</h3>
            <ul>
                <li><a href="{{route('category-products-JAT',['slug' => 'cocina'])}}">Cocina</a></li>
                <li><a href="{{route('category-products-JAT',['slug' => 'lavanderia'])}}">Lavanderia</a></li>
                <li><a href="{{route('category-products-JAT',['slug' => 'closets'])}}">Closets</a></li>
                <li><a href="{{route('category-products-JAT',['slug' => 'herramienta'])}}">Herramienta</a></li>
                <li><a href="{{route('category-products-JAT',['slug' => 'automotriz'])}}">Automotriz</a></li>
                <li><a href="{{route('category-products-JAT',['slug' => 'ferreteria'])}}">Ferreteria</a></li>
                <li><a href="{{route('category-products-JAT',['slug' => 'impulso'])}}">Impulso</a></li>
                <li><a href="{{route('category-products-JAT',['slug' => 'electrico'])}}">Electrico</a></li>
                <li><a href="{{route('category-products-JAT',['slug' => 'pintura'])}}">Pintura</a></li>
            </ul>
        </div>

        <div class="grid__item two-tenths  menu">
            <h3>CATEGORIAS</h3>
            <ul>
                <li><a href="{{route('category-products-JAT',['slug' => 'jardin'])}}">Jardin</a></li>
                <li><a href="{{route('category-products-JAT',['slug' => 'limpieza'])}}">Limpieza</a></li>
                <li><a href="{{route('category-products-JAT',['slug' => 'iluminacion'])}}">Iluminación</a></li>
                <li><a href="{{route('category-products-JAT',['slug' => 'plomeria'])}}">Plomeria</a></li>
                <li><a href="{{route('category-products-JAT',['slug' => 'ventilacion'])}}">Ventilación</a></li>
                <li><a href="{{route('category-products-JAT',['slug' => 'exterior'])}}">Exterior</a></li>
                <li><a href="{{route('category-products-JAT',['slug' => 'mantenimiento'])}}">Mantenimiento</a></li>
                <li><a href="{{route('category-products-JAT',['slug' => 'material_contruccion'])}}">Material Construcción</a></li>
                <li><a href="{{route('category-products-JAT',['slug' => 'outdoor'])}}">Outdoor</a></li>
                <li><a href="{{route('category-products-JAT',['slug' => 'chapas'])}}">Chapas</a></li>
            </ul>
        </div>

        <div class="grid__item four-tenths menu">
            <h3>CONTACTO</h3>
            <ul>
                <li>(662) 476-78-75</li>
                <li><a>pedidos@justoatiempo.online</a></li>
            </ul>
        </div>
    </div>
  </div>
</div>

<div id="footer-copyright">
                            <p>© 2019 JUSTO A TIEMPO</p>
                        </div>
                        <script>
    $("body").on("click", (e) => { 
        if(!$(e.target).hasClass( "parent-li" )){
            let submenu = $(".child-li");
            $.each(submenu, (index, element) => {
                $(element).css('visibility', 'hidden');
            })
        }
    })

    $('.parent-li').on("click", (e) => {
        let submenu = $(".child-li");
        $.each(submenu, (index, element) => {
            $(element).css('visibility', 'hidden');
        })
        let child = $(e.target).find(".child-li").css('visibility', 'visible');
    });

    $('.btnSearch').on("click", (e) => {
        $("#searchForm").prop("hidden",false);
    })

    $('.js-close-search').on("click", (e) => {
        $("#searchForm").prop("hidden",true);
    })
</script>