@extends('layouts.website')
@section('extra-head')
    <link rel="stylesheet" href="{{ asset('/website/css/forgot-password.css') }}">
@stop

@section('content')
    <nav aria-label="breadcrumb" role="navigation">
        <div class="container">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                <li class="breadcrumb-item active" aria-current="page">Recuperar contraseña</li>
            </ol>
        </div>
    </nav>

    <div class="forgot-password">
        <div class="container">
            @if(Session::has('flash_message'))
                <div class="row">
                    <div class="col-md-6 offset-md-3">       
                        <div class="alert alert-{{Session::get('flash_type')}} alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button><strong>{{Session::get('flash_title')}}</strong> {!!Session::get('flash_message')!!}</div>
                    </div>
                </div>
            @endif
        
        @if($errors->any())
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif
        @if($valid)
            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <h1 class="title">Reestablecer contraseña</h1>
                    <form action="{{route('recover-password-customer',[$user->email,$user->recover_token])}}" method="POST" id="frmRecover">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="email">Contraseña<span class="required">*</span></label>
                            <input type="password" name="password" class="form-control" id="password">
                        </div>
                        <div class="form-group">
                            <label for="email">Repetir contraseña<span class="required">*</span></label>
                            <input type="password" name="repassword" class="form-control">
                        </div>
                        <div class="row m-top-40">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-lg green">Continuar</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @else
            <div class="alert alert-warning text-center mt-5" style="padding:50px 0">
                <span class="fa fa-warning mb-3" style="color: #262b36;font-size: 30px;"></span>
                <h3>Correo o Token inválido, revisa tu correo para poder recuperar tu contraseña</h3>      
            </div>
        @endif
        </div>
    </div>

@stop

@section('js')
<script>
  $('#frmRecover').validate({
    rules:{
      "password": {
            minlength: 4,
            required: true,
        },
      "repassword": {
            minlength: 4,
            required: true,
            equalTo: "#password"
        }
    },
     messages: {
        password:{
          required: "Una contraseña es requerida",
          minlength: "La contraseña debe contener al menos {0} caracteres"
        },
        repassword:{
          required: "Este campo es requerido",
          equalTo: "Las contraseñas no coinciden",
          minlength: "La contraseña debe contener al menos {0} caracteres"
        }
      },
  });
</script>
@stop