@extends('layouts.website')
@section('extra-head')
    <link rel="stylesheet" href="{{ asset('/website/fonts/line-awesome/css/line-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/website/css/about-us.css') }}">
@stop
@section('content')
<section class="about-us">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div>
                    <img src="/img/nos-2.png" class="img-fluid">
                </div>
            </div>
            <div class="col-md-6">
                <h2>Quiénes somos</h2>
                <p style="text-align:justify">Somos una tienda de comercio electrónico que ofrece en mayoreo y menudeo productos para todas las áreas del hogar, en más de 20 categorías diferentes: automotriz, baños, chapas, closets, cocinas, decoración, eléctrico, exterior, ferretería, herramientas, iluminación, impulso, jardín, lavandería, limpieza, mantenimiento, materiales de construcción, outdoor, pintura, plomería, recámaras, sala y ventilación.</p>
                <p>Nuestra detallada selección de proveedores y colaboradores son parte fundamental para brindar la amplia selección de productos que nos caracteriza.</p>
            </div>
        </div>
    </div>
</section>
<section id="mision">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h2>Misión</h2>
                <p>Ofrecer una amplia selección de productos enfocados a la construcción, mantenimiento y mejoras del hogar, bajo un concepto innovador enfocado en brindar la mejor experiencia de compra en nuestra pagina web.</p>
            </div>

            <div class="col-md-6">
                    <h2>Visión</h2>
                    <p>Ser la página web líder en el giro de construcción, decoración y mantenimiento del hogar, tanto en mayoreo como en menudeo. Posicionarnos en el mercado internacional como un modelo innovador que provee una agradable experiencia de compra.</p>
            </div>

        </div>
    </div>
</section>
<section class="about-banner">
    <div class="container">
        <div class="banner align-items-stretch justify-content-between">
            <div class="item">
                <div><img class="img-fluid" src="/img/ico-01.png" alt="Card image cap"></div>                
                <div class="item-body">
                    <h4>ENVÍO SIN COSTO</h4>
                    <h5 class="item-subtitle mb-2 ">Directo a tu hogar</h5>
                </div>
            </div>
            <div class="item">
                <div><img class="img-fluid" src="/img/ico-02.png" alt="Card image cap"></div>  
                <div class="item-body">
                    <h4>SEGURIDAD DE PAGO</h4>
                    <h5 class="item-subtitle mb-2 ">En el método que elijas</h5>
                </div>
            </div>
            <div class="item">
                <div><img class="img-fluid" src="/img/ico-03.png" alt="Card image cap"></div>  
                <div class="item-body">
                    <h4>LAS MEJORES MARCAS</h4>
                    <h5 class="item-subtitle mb-2 ">Gran variedad a tu alcance</h5>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="about-collapse">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 offset-md-3 col-md-6 text-center">                
                <h1 class="title">Somos la mejor opción</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div id="accordion" role="tablist">
                    <div class="card">
                        <div class="card-header active" role="tab" id="headingOne">                            
                            <h5 class="mb-0">
                                <a data-toggle="collapse" href="#collapseOne" role="button" aria-expanded="true" aria-controls="collapseOne">                                    
                                    <div class="arrow"></div>
                                    Surtido adecuado
                                </a>
                            </h5>
                        </div>

                        <div id="collapseOne" class="collapse show" role="tabpanel" aria-labelledby="headingOne" data-parent="#accordion">
                        <div class="card-body">
                                <p>Amplia selección de productos de distintas marcas, adecuado a los requerimientos de la población.</p>
                        </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header" role="tab" id="headingTwo">                            
                            <h5 class="mb-0">
                                <a class="collapsed" data-toggle="collapse" href="#collapseTwo" role="button" aria-expanded="false" aria-controls="collapseTwo">
                                    <div class="arrow"></div>    
                                    Precios competitivos
                                </a>
                            </h5>
                        </div>
                        <div id="collapseTwo" class="collapse" role="tabpanel" aria-labelledby="headingTwo" data-parent="#accordion">
                        <div class="card-body">
                            <p>Precios estandarizados en sus distintas unidades de negocio. Basados en nuestra alianza con proveedores y procesos eficientes de administración de inventarios, así como un programa de artículos en promoción.</p>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header" role="tab" id="headingThree">
                            <h5 class="mb-0">
                                <a class="collapsed" data-toggle="collapse" href="#collapseThree" role="button" aria-expanded="false" aria-controls="collapseThree">
                                    <div class="arrow"></div>    
                                    Conveniencia

                                </a>
                            </h5>
                        </div>
                        <div id="collapseThree" class="collapse" role="tabpanel" aria-labelledby="headingThree" data-parent="#accordion">
                        <div class="card-body">
                            <p>Página web enfocada a ofrecer productos para todas las áreas del hogar, desde el inicio de la construcción hasta la decoración y mantenimiento del mismo.</p>
                        </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header" role="tab" id="headingFour">
                            <h5 class="mb-0">
                                <a class="collapsed" data-toggle="collapse" href="#collapseFour" role="button" aria-expanded="false" aria-controls="collapseFour">
                                    <div class="arrow"></div>    
                                    Horarios
                                </a>
                            </h5>
                        </div>
                        <div id="collapseFour" class="collapse" role="tabpanel" aria-labelledby="headingFour" data-parent="#accordion">
                        <div class="card-body">
                            <p>24 horas los 365 días del año.</p>
                        </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header" role="tab" id="headingFive">
                            <h5 class="mb-0">
                                <a class="collapsed" data-toggle="collapse" href="#collapseFive" role="button" aria-expanded="false" aria-controls="collapseFive">
                                    <div class="arrow"></div>    
                                    Servicio de calidad
                                </a>
                            </h5>
                        </div>
                        <div id="collapseFive" class="collapse" role="tabpanel" aria-labelledby="headingFive" data-parent="#accordion">
                        <div class="card-body">
                            <p>Asesoría telefónica y virtual, con especialistas capacitados para brindarte una atención de calidad.</p>
                        </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="col-md-6">
            <img src="/img/nos-3.jpg" class="img-fluid">
        </div>
    </div>
</section>
@stop

@section('js')
<script>
 $('.collapse').on('show.bs.collapse', function () {
    $(this).siblings('.card-header').addClass('active');
  });

  $('.collapse').on('hide.bs.collapse', function () {
    $(this).siblings('.card-header').removeClass('active');
  });
</script>
@stop