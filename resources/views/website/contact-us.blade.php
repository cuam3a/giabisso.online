@extends('layouts.website')
@section('extra-head')
    <link rel="stylesheet" href="{{ asset('/website/fonts/line-awesome/css/line-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/website/css/contact-us.css') }}">
@stop
@section('content')  
<section class="contact-us">
    <div class="container">
        <div class="row"><!-- Row contact content -->
            <div class="col-md-8 contact-col"><!-- Left panel row -->
                <form action="{{route('send-email-contact')}}" method="POST" id="frmContactEmail">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="row">

                        @if(session('flash_title') )
                            <div class="alert alert-{{Session::get('flash_type')}} alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button><strong>{{Session::get('flash_title')}}</strong> {{Session::get('flash_message')}}.</div>
                        @endif
                        @if(count($errors))
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar">
                            @foreach($errors->all() as $error)
                            <span aria-hidden="true">&times;</span></button><strong>{{$error}}</strong>@endforeach</div>
                        @endif
                        <div class="col-md-6"> 
                            <div class="form-group">
                                <label for="name" class="contatc-title" >Nombre(s) </label>
                                <input type="text" name="name" class="form-control m-input m-input--square" value="{{old('name')}}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="lastname" class="contatc-title">Apellidos </label>
                                <input type="text" name="lastname" class="form-control m-input m-input--square" value="{{old('lastname')}}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="phone" class="contatc-title" >Teléfono </label>
                                <input type="text" name="phone" class="form-control m-input m-input--square" value="{{old('phone')}}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email" class="contatc-title" >Correo </label>
                                <input type="text" name="email" class="form-control m-input m-input--square" value="{{old('email')}}">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group" class="contatc-title">
                                <label for="message">Mensaje </label>
                                <textarea name="message" class="form-control m-input m-input--square" rows="5">{{old('message')}}</textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-xs-12 mb10 mt10">
                                    <div style="width: 304px; margin: 0">
                                    {!! Recaptcha::render() !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 text-right">
                            <div class="form-group">
                                <button id="btnContactSend" class="btnContactSend btn btn-primary"> ENVIAR </button>
                            </div>
                        </div>

                   
                    </div>
                </form>
            </div><!-- Left panel row close -->





            <div class="col-md-4 info-col">
                <br>
                <div>
                    <h4 style="font-weight:600;">¿En qué podemos ayudarte? </h4>
                    <span>Puedes ponerte en contacto con nosotros.</span>
                </div>
                <div class="line-top-separator">
                    <p class="sub-title-gray">CONTÁCTANOS</p>
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
                            <a href="mailto:{{$global_contact_email}}">
                                <b>{{$global_contact_email}}</b>
                            </a>
                        </li>
                        @endif
                    </ul>
                </div>
                <div class="line-top-separator">
                    <ul class="list-unstyled">
                    @if(count($global_social_links)>0)
                        <p class="sub-title-gray"><b style="width:100%">SíGUENOS EN:</b></p>
                        @foreach($global_social_links as $key=>$link)
                            @if($link != '')
                                <a style="font-size:22px; color:#5aa407" href="{{$link}}"><i class="la la-{{$key}}"></i></a>
                            @endif
                        @endforeach

                    @endif
                    </ul>
                </div>

                    

            </div>
            

        </div><!-- End row contact content -->
    </div>
</section>



@stop

@section('js')
<script>
     $("#frmContactEmail").validate({
      rules: {
        name: {
          required: true,
          maxlength:50
        },
        lastname: {
          required: true,
          maxlength:50
        },
        email: {
          required: true,
          maxlength:50,
          email:true
        },
        phone: {
          required: true,
          maxlength:50
        }
        
      },
      messages:{ 
        name: {
          required: "Campo requerido",
          maxlength: "Este campo debe ser no mayor a 50 caracteres",
        },
        lastname: {
          required: "Campo requerido",
          maxlength: "Este campo debe ser no mayor a 50 caracteres",
        },
        email: {
          required: "Campo requerido",
          maxlength: "Este campo debe ser no mayor a 50 caracteres",
          email: "Correo electrónico inválido"
        },
        phone: {
          required: "Campo requerido",
          maxlength: "Este campo debe ser no mayor a 50 caracteres",
        }
        
      }
  });/* validate for submit*/
</script>
@stop