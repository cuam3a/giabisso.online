@extends('layouts.website')
@section('extra-head')
    <link rel="stylesheet" href="{{ asset('/website/fonts/line-awesome/css/line-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/website/css/faq.css') }}">
@stop

@section('content')
    <nav aria-label="breadcrumb" role="navigation">
        <div class="container">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                <li class="breadcrumb-item active" aria-current="page">Preguntas frecuentes</li>
            </ol>
        </div>
    </nav>
    <section class="faq">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-md-8">
                    @if(count($faq)>0)
                    <div id="accordion" role="tablist">
                        @foreach($faq as $f)
                        <div class="card">
                            <div class="card-header" arole="tab" id="heading{{$f->id}}">                            
                                <h5 class="mb-0">
                                    <a data-toggle="collapse" href="#collapse{{$f->id}}" role="button" aria-expanded="true" aria-controls="collapse{{$f->id}}">                                    
                                        <div class="arrow"></div>
                                        {{$f->title}}
                                    </a>
                                </h5>
                            </div>

                            <div id="collapse{{$f->id}}" class="collapse" role="tabpanel" aria-labelledby="heading{{$f->id}}" data-parent="#accordion">
                                <div class="card-body">
                                {!!$f->content!!}
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
                <div class="col-xs-12 col-md-4">
                    <h5>¿En qué podemos ayudarte?</h5>
                    <p class="contact_text">Aquí encontrarás respuesta a las preguntas más comunes que nuestros clientes se hacen antes de realizar su compra. Si necesitas atención más personalizada no dudes en marcarnos o escribirnos a nuestro correo.</p>
                    <ul class="list-unstyled">
                        <hr>
                        <p><b style="width:100%">CONTÁCTANOS:</b></p>
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
    
                        @if($email_support != '')
                        <li>
                            <i class="la la-envelope"></i> 
                            <a href="mailto:{{$email_support->value}}">
                                <b>{{$email_support->value}}</b>
                            </a>
                        </li>
                        @endif
                        <hr>
                        <li>
                        <!-- Social links -->
                        @if(count($global_social_links)>0)
                            <p><b style="width:100%">SíGUENOS EN:</b></p>
                            @foreach($global_social_links as $key=>$link)
                                @if($link != '')
                                    <a style="font-size:26px; color:#5aa407" href="{{$link}}"><i class="la la-{{$key}}"></i></a>
                                @endif
                            @endforeach
    
                        @endif
                        
                        </li>
                    </ul>             
                </div>
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