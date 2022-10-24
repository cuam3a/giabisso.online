<?php
    $categories = App\Models\Category::where('parent_id',null)->orderBy('order','ASC')->get();
    $obj_cat = new App\Models\Category;
    $params = Route::current() ? array_key_exists('id',Route::current()->parameters()) ? [] : Route::current()->parameters() : [];
?>
<div class="container-fluid">
    <div id="row">
        <div class="col-md-12" style="display: flex; flex-flow: row wrap;">
            <div class="col-md-3   menu">           
            <h2 class="mb-2 font-weight-bold">EMPRESA</h2>
            <ul>
                <li><a href="{{ route('website-home') }}" style="text-decoration:none">Inicio</a></li>

                <li><a href="{{route('concepto')}}" style="text-decoration:none">Concepto</a></li>

                <li><a href="{{route('nosotros')}}" style="text-decoration:none">Nosotros</a></li>

                <li><a href="{{route('politica-precios')}}" style="text-decoration:none">Politica de Precios</a></li>

                <li><a href="{{route('ventajas')}}" style="text-decoration:none">Ventajas</a></li>

                <li><a href="{{route('beneficios')}}" style="text-decoration:none">Beneficios</a></li>

            </ul>
        </div>

        <div class="col-md-3  menu">
            <h2 class="mb-2 font-weight-bold">CATEGORIAS</h2>
            <ul>
                <li><a href="{{route('category-products',['slug' => 'cocina'])}}" style="text-decoration:none">Cocina</a></li>
                <li><a href="{{route('category-products',['slug' => 'lavanderia'])}}" style="text-decoration:none">Lavanderia</a></li>
                <li><a href="{{route('category-products',['slug' => 'closets'])}}" style="text-decoration:none">Closets</a></li>
                <li><a href="{{route('category-products',['slug' => 'herramienta'])}}" style="text-decoration:none">Herramienta</a></li>
                <li><a href="{{route('category-products',['slug' => 'automotriz'])}}" style="text-decoration:none">Automotriz</a></li>
                <li><a href="{{route('category-products',['slug' => 'ferreteria'])}}" style="text-decoration:none">Ferreteria</a></li>
                <li><a href="{{route('category-products',['slug' => 'impulso'])}}" style="text-decoration:none">Impulso</a></li>
                <li><a href="{{route('category-products',['slug' => 'electrico'])}}" style="text-decoration:none">Electrico</a></li>
                <li><a href="{{route('category-products',['slug' => 'pintura'])}}" style="text-decoration:none">Pintura</a></li>
            </ul>
        </div>

        <div class="col-md-3  menu">
            <h2 class="mb-2 font-weight-bold">CATEGORIAS</h2>
            <ul>
                <li><a href="{{route('category-products',['slug' => 'jardin'])}}" style="text-decoration:none">Jardin</a></li>
                <li><a href="{{route('category-products',['slug' => 'limpieza'])}}" style="text-decoration:none">Limpieza</a></li>
                <li><a href="{{route('category-products',['slug' => 'iluminacion'])}}" style="text-decoration:none">Iluminación</a></li>
                <li><a href="{{route('category-products',['slug' => 'plomeria'])}}" style="text-decoration:none">Plomeria</a></li>
                <li><a href="{{route('category-products',['slug' => 'ventilacion'])}}" style="text-decoration:none">Ventilación</a></li>
                <li><a href="{{route('category-products',['slug' => 'exterior'])}}" style="text-decoration:none">Exterior</a></li>
                <li><a href="{{route('category-products',['slug' => 'mantenimiento'])}}" style="text-decoration:none">Mantenimiento</a></li>
                <li><a href="{{route('category-products',['slug' => 'material_contruccion'])}}" style="text-decoration:none">Material Construcción</a></li>
                <li><a href="{{route('category-products',['slug' => 'outdoor'])}}" style="text-decoration:none">Outdoor</a></li>
                <li><a href="{{route('category-products',['slug' => 'chapas'])}}" style="text-decoration:none">Chapas</a></li>
            </ul>
        </div>

        <div class="col-md-3 menu">
            <h2 class="mb-2 font-weight-bold">CONTACTO</h2>
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