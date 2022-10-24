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
	<div class="">
        <img src="/img/home.png" height="800" style="width:100%">
    </div>
    
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