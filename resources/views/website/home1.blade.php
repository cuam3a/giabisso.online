@extends('layouts.website')

@section('content')

    @if(count($sliders)>0)
    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
        <ol class="carousel-indicators">
            
            <!-- Carousel indicators -->
            {{-- @php ($first = true)
            @foreach (range(0, count($sliders)-1) as $index)
                @php ($first == true ? $class = 'active' : $class = '')
                    <li data-target="#carouselExampleIndicators" data-slide-to="{{$index}}" class="{{$class}}"></li>
                @php ($first = false)
            @endforeach --}}

        </ol>
        <div class="carousel-inner">
            
            <!-- Banner sliders -->
            @php ($first = true)
            @foreach ($sliders as $slider)
                @php ($first == true ? $class = 'active' : $class = '')
                    <div class="carousel-item {{$class}}">
                        @php ($first = false)
                    @if($slider->link != '')
                        <a target="_blank" href="{{$slider->link}}">
                            <img class="d-block w-100" src="{{ $slider->path }}" alt="{{$slider->description}}">
                        </a>
                    @else
                        <img class="d-block w-100" src="{{ $slider->path }}" alt="{{$slider->description}}">
                    @endif
                </div>
            @endforeach

        </div>
        <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>
    @endif

    <div class="features">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-4" >
                    <div class="item rounded" style="background-color:#0D0D32">
                        <img src="{{ asset('/website/img/ico-hec-01.png') }}" alt="Envío sin costo">
                        <div class="description text-white">
                            <h4 class="title text-white">Envío sin costo</h4>
                            <p>Directo a tu hogar</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="item rounded" style="background-color:#0D0D32">
                        <img src="{{ asset('/website/img/ico-hec-02.png') }}" alt="Seguridad de pago">
                        <div class="description text-white">
                            <h4 class="title text-white">Seguridad de pago</h4>
                            <p>En el método que elijas</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 ">
                    <div class="item rounded" style="background-color:#0D0D32">
                        <img src="{{ asset('/website/img/ico-hec-03.png') }}" alt="Las mejores marcas">
                        <div class="description text-white">
                            <h4 class="title text-white">Las mejores marcas</h4>
                            <p>Gran variedad a tu alcance</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="">
        <img src="/img/home.png" height="680" style="width:100%">
    </div>
<!--@foreach($carousels as $carousel)
    @if(count($carousel->products()) > 0)
    <div class="newest">
        <div class="container">
            <div class="products-grid">
                <h1 class="title">{{$carousel->name}}</h1>
                <div class="owl-carousel owl-theme">
                    @foreach($carousel->products() as $product)
                        @include('layouts.includes.product-item')
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif
@endforeach
@if(Auth::guard('customer-web')->check() && count(Auth::guard('customer-web')->user()->favoriteCategories()))
<div class="newest">
    <div class="container">
        <div class="products-grid">
            <h1 class="title">Recomendados para <span class="green-text">{{Auth::guard('customer-web')->user()->name}}</span></h1>
            <div class="owl-carousel owl-theme">
                @foreach(Auth::guard('customer-web')->user()->favoriteCategories() as $product)
                    @include(
                    'layouts.includes.product-item')
                @endforeach
            </div>
        </div>
    </div>
</div>
@endif

    @include('layouts.includes.suscribe')
    @include('layouts.includes.brands')-->
@stop

@section('js')
<script>
    $('.owl-carousel').owlCarousel({
        loop:true,
        margin:10,
        nav:true,
        autoplay: true,
        navText: ['<i class="fa fa-angle-left"></i>','<i class="fa fa-angle-right"></i>'],
        responsive:{
            0:{
                items:1
            },
            600:{
                items:2
            },
            992:{
                items:3
            },
            1200:{
                items:4
            }
        }
    });
    $("#frmSuscribe").submit(function( event ) {
        event.preventDefault();
        $.ajax({
            url: "{{route('website-suscribe-add')}}",
            type: 'POST',
            data: jQuery.param({ _token: "{{ csrf_token() }}", email : $("#frmSuscribe").find('input[name="email"]').val()}) ,
            success: function (data) {
               $("#frmSuscribe").find('input[name="email"]').val('');
               swal(
                data.flash_title,
                data.flash_message,
                data.flash_type,
                )
               if(data.flash_type != 'error'){ $('.suscribe').remove();}
            },
            error: function (data) {
                swal(
                'Error',
                'Intente más tarde',
                'error',
                )
            }
        });
    });
</script>
@stop