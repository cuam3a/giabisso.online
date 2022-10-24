@extends('layouts.website')
@section('extra-head')
    <link rel="stylesheet" href="{{ asset('/website/css/login.css') }}">
@stop

@section('content')
    <nav aria-label="breadcrumb" role="navigation">
        <div class="container">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                <li class="breadcrumb-item active" aria-current="page">Iniciar sesión</li>
            </ol>
        </div>
    </nav>
    <div class="login">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    @if(Session::has('flash_message_login'))
                        <div class="alert alert-{{Session::get('flash_type')}} alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button><strong>{{Session::get('flash_title')}}</strong> {{Session::get('flash_message_login')}}.</div>
                    @endif
                    <h1 class="title">Iniciar sesión</h1>
                    <form action="{{route('signin-customer')}}" method="GET">
                        <input type="hidden" name="originurl" value="@if(isset($page_origin)){{$page_origin}}@endif">
                        <div class="form-group">
                            <label for="email">Correo electrónico <span class="required">*</span></label>
                            <input type="email" name="email" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="password">Contraseña <span class="required">*</span></label>
                            <input type="password" name="password" class="form-control">
                        </div>

                        <div class="row m-top-40">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-lg">Iniciar Sesión</button>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check m-top-10">
                                    <label class="form-check-label">
                                        <input class="form-check-input" type="checkbox" value="">
                                        Recordarme
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <a href="{{route('forgot-password')}}">Olvidé mi contraseña</a>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-md-6">
                    @if(Session::has('flash_message_new_customer'))
                        <div class="alert alert-{{Session::get('flash_type')}} alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button><strong>{{Session::get('flash_title')}}</strong> {{Session::get('flash_message_new_customer')}}.</div>
                    @endif
                    
                    @if(count($errors))
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar">
                            @foreach($errors->all() as $error)
                            <span aria-hidden="true">&times;</span></button><strong>{{$error}}</strong>@endforeach</div>
                    @endif
                    <h1 class="title">Registrarse</h1>
                    <form action="{{route('new-customer')}}" method="POST" id="frmNewCustomer">
                        <input type="hidden" name="originurl" value="@if(isset($page_origin)){{$page_origin}}@endif">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="name">Nombre <span class="required">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{old('name')}}">
                        </div>
                        <div class="form-group">
                            <label for="lastname">Apellidos <span class="required">*</span></label>
                            <input type="text" name="lastname" class="form-control" value="{{old('lastname')}}">
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="phone">Telefono </label>
                                <input type="phone" name="phone" class="form-control" value="{{old('phone')}}">
                            </div>
                            <div class="form-group col-md-6" style="position:relative">
                                <label for="cell_phone">Telefono celular</label>
                                <input type="cell_phone" name="cell_phone" class="form-control" value="{{old('cell_phone')}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="email">Correo electrónico <span class="required">*</span></label>
                            <input type="email" name="email" class="form-control" value="{{old('email')}}">
                        </div>
                        <div class="form-group">
                            <label for="password">Contraseña <span class="required">*</span></label>
                            <input type="password" name="password" id="password" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="password_confirm">Confirmar contraseña <span class="required">*</span></label>
                            <input type="password" name="password_confirm" class="form-control">
                        </div>
                        <hr/>
                        <h4 class="">Datos de Facturación</h4>
                        <div class="form-group">
                            <label for="f_name">Razon Social <span class="required">*</span></label>
                            <input type="f_name" name="f_name" class="form-control" value="{{old('f_name')}}">
                        </div>
                        <div class="form-group">
                            <label for="f_rfc">RFC <span class="required">*</span></label>
                            <input type="f_rfc" name="f_rfc" class="form-control" value="{{old('f_rfc')}}">
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-8">
                                <label for="f_address">Dirección <span class="required" >*</span></label>
                                <input type="f_address" name="f_address" class="form-control" value="{{old('f_address')}}">
                            </div>
                            <div class="form-group col-md-4" style="position:relative">
                                <label for="f_zipcode">C.P. <span class="required">*</span></label>
                                <input type="f_zipcode" name="f_zipcode" class="form-control" value="{{old('f_zipcode')}}">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="f_state">Estado</label>
                                <select name="f_state" id="f_state" class="form-control select2" >
                                <option value="">Elegir Estado</option>
                                @foreach($states as $id => $name)
                                    <option value="{{$id}}">{{$name}}</option>
                                @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-6" style="position:relative">
                                <label for="f_city">Ciudad</label>
                                <select name="f_city" id="f_city" class="form-control select2">
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12 mb10 mt10">
                                <div style="width: 304px; margin: 0">
                                {!! Recaptcha::render() !!}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-check">
                                    <label class="form-check-label subscribe">
                                        <input class="form-check-input" type="checkbox" checked name="subscribe" value="">
                                        <span class="">Suscribirme al boletín</a></span>
                                    </label>
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-check">
                                    <label class="form-check-label terminos">
                                        <input class="form-check-input" type="checkbox" name="conditions" value="" required>
                                        <span class="">Acepto los &nbsp;<a href="#">terminos y condiciones</a></span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-lg green m-top-40">Registrarse</button>
                                </div>
                            </div>
                            <div class="col-md-6">
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

@stop

@section('js')
<script>
   $("#frmNewCustomer").validate({
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
        password: {
          required: true,
          maxlength:50
        },
        password_confirm: {
          required: true,
          equalTo: "#password",
          maxlength:50
        },
        conditions:{
            required:true
        },
        f_name: {
          required: true,
          maxlength:50
        },
        f_rfc: {
          required: true,
          maxlength:50
        },
        f_address: {
          required: true,
          maxlength:50
        },
        f_zipcode: {
          required: true,
          maxlength:50
        },
        f_state: {
          required: true,
          maxlength:50
        },
        f_city: {
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
          maxlength: "Este campo debe ser no mayor a 50 caracteres"
        },
        email: {
          required: "Campo requerido",
          maxlength: "Este campo debe ser no mayor a 50 caracteres",
          email: "Correo electrónico inválido"
        },
        password: {
          required: "Campo requerido",
          maxlength: "Este campo debe tener a 20 digitos"
        },
        password_confirm: {
          required: "Campo requerido",
          equalTo: "Contraseñas no coinciden",
          maxlength: "Este campo debe tener a 20 digitos"
        },
        conditions: {
            required: "Es necesario aceptar terminos y condiciones"
        },
        f_name: {
          required: "Campo requerido"
        },
        f_rfc: {
          required: "Campo requerido"
        },
        f_address: {
          required: "Campo requerido"
        },
        f_zipcode: {
          required: "Campo requerido"
        },
        f_state: {
          required: "Campo requerido"
        },
        f_city: {
          required: "Campo requerido"
        }
      }
    });/* validate for submit*/

    

  $(document).on('change','#state,#f_state', function (e) {
        var who = '';

        if($(this).attr('id') == 'f_state'){//esto es para reutilizar funcion
            var who = 'f_';
        }

        $.ajax({
            method: "POST",
            url: "{{route('get-cities')}}",
            data: {
            '_token': '{!! csrf_token() !!}',
            'state': $(this).val()
            }
        }).done(function(ciudades) {
            $('#'+who+'city option').remove();
            $.each(ciudades, function(key,text) {
                var newOption = new Option(text,key, false, false);
                $('#'+who+'city').append(newOption);
            });
        });
    });
</script>
@stop