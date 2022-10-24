@extends('layouts.jat.website')


@section('content')


<div id="wrap" class="mt-5">
    @if(Session::has('flash_message_login'))
    <div class="alert alert-{{Session::get('flash_type')}} alert-dismissible"><button type="button" class="close"
            data-dismiss="alert" aria-label="Cerrar"><span
                aria-hidden="true">&times;</span></button><strong>{{Session::get('flash_title')}}</strong>
        {{Session::get('flash_message_login')}}.</div>
    @endif
    <div id="content" class="grid">
        <div class="grid__item one-half">
            <div class="grid__item one-whole">
                <h1 class="title brand">
                    Iniciar Sesión
                </h1>
            </div>
            <div class="signin--container form-elements--no-label grid">
                <div class="js-eq-height" style="">
                    <div class="grid__item one-whole">
                    </div>
                    <form action="{{route('signin-customer')}}" method="GET">
                        <input type="hidden" name="originurl" value="@if(isset($page_origin)){{$page_origin}}@endif">
                        <fieldset class="grid__item one-whole">
                            <input size="35" autocomplete="off" maxlength="80" name="email"
                                placeholder="Direccion de Correo" type="email" value="" aria-label="Email Address"
                                class="">
                        </fieldset>
                        <fieldset class="grid__item one-whole">
                            <input size="35" autocomplete="off" maxlength="80" name="password" placeholder="Contraseña"
                                type="password" value="" aria-label="Contraseña" class="">
                        </fieldset>
                        <fieldset class="grid__item one-whole">
                            <span style="position:relative; left:125px; bottom: -1.5em;"><a
                                    href="{{route('forgot-password')}}">Olvidé mi contraseña?</a></span>
                        </fieldset>
                        <fieldset class="grid__item one-whole form-element--submit">
                            <input type="submit" value="Iniciar Sesión" class="button--primary">
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
        <div class="grid__item one-half">
            <div class="grid__item one-whole">
                <h1 class="title brand">
                    Registrarse
                </h1>
            </div>
            <div class="signin--container form-elements--no-label">
                <div class="js-eq-height" style="">
                    <div id="registerForm" class="hidden">
                        @if(count($errors))
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar">
                                @foreach($errors->all() as $error)
                                <span aria-hidden="true">&times;</span></button><strong>{{$error}}</strong>
                            @endforeach
                        </div>
                        @endif
                        <form method="post" name="my-account-register" action="{{route('new-customer')}}"
                            id="frmNewCustomer" class="grid">
                            <input type="hidden" name="originurl"
                                value="@if(isset($page_origin)){{$page_origin}}@endif">
                            {{ csrf_field() }}
                            <fieldset class="grid__item one-half">
                                <label for="firstname">Nombre</label>
                                <input size="25" name="name" id="name" type="text" value="{{old('name')}}"></fieldset>
                            <fieldset class="grid__item one-half">
                                <label for="lastname">Apellidos</label>
                                <input size="25" name="lastname" id="lastname" type="text" value="{{old('lastname')}}">
                            </fieldset>
                            <fieldset class="grid__item one-half">
                                <label for="firstname">Telefono</label>
                                <input size="25" name="phone" id="phone" type="phone" value="{{old('phone')}}">
                            </fieldset>
                            <fieldset class="grid__item one-half">
                                <label for="lastname">Celular</label>
                                <input size="25" name="cell_phone" id="cell_phone" type="cell_phone"
                                    value="{{old('cell_phone')}}">
                            </fieldset>
                            <fieldset class="grid__item one-whole">
                                <label for="email">Correo Electrónico</label>
                                <input size="35" autocomplete="off" maxlength="80" name="email" id="email" type="email"
                                    value="{{old('email')}}">
                            </fieldset>
                            <fieldset class="grid__item one-half">
                                <label for="password">Contraseña (8 to 20 characters)</label>
                                <input size="25" autocomplete="off" maxlength="20" name="password" id="password"
                                    type="password" value="">
                            </fieldset>
                            <fieldset class="grid__item one-half">
                                <label for="confirmpassword">Confirmar Contraseña</label>
                                <input size="25" autocomplete="off" maxlength="20" name="password_confirm"
                                    id="password_confirm" type="password" value="">
                            </fieldset>
                            <hr />
                            <h4 class="title brand">Datos de Facturación</h4>
                            <fieldset class="grid__item one-whole">
                                <label for="f_name">Razon Social</label>
                                <input size="57" name="f_name" id="f_name" type="text" value="{{old('f_name')}}">
                            </fieldset>
                            <fieldset class="grid__item one-whole">
                                <label for="f_rfc">RFC</label>
                                <input size="57" name="f_rfc" id="f_rfc" type="text" value="{{old('f_rfc')}}">
                            </fieldset>
                            <fieldset class="grid__item four-fifths">
                                <label for="f_address">Dirección</label>
                                <input size="40" name="f_address" id="f_address" type="text"
                                    value="{{old('f_address')}}"></fieldset>
                            <fieldset class="grid__item one-fifth">
                                <label for="f_zipcode">C.P.</label>
                                <input size="6" name="f_zipcode" id="f_zipcode" type="text"
                                    value="{{old('f_zipcode')}}">
                            </fieldset>
                            <fieldset class="grid__item one-half">
                                <label for="firstname">Estado</label>
                                <select name="f_state" id="f_state" class="select2" style="width:100%">
                                    <option value="">Elegir Estado</option>
                                    @foreach($states as $id => $name)
                                    <option value="{{$id}}">{{$name}}</option>
                                    @endforeach
                                </select>
                            </fieldset>
                            <fieldset class="grid__item one-half">
                                <label for="lastname">Ciudad</label>
                                <select name="f_city" id="f_city" class="select2" style="width:100%">
                                </select>
                            </fieldset>
                            <fieldset class="grid__item one-whole">
                                <div class="checkbox opt-in-checkbox--email">
                                    <input name="conditions" id="conditions" type="checkbox" value="conditions"
                                        class="fieldHighlight"><label for="conditions">Acepto los Terminos y
                                        Condiciones</label>
                                </div>
                            </fieldset>
                            <fieldset class="grid__item one-whole form-element--submit">
                                <input type="submit" value="Registro" class="button--primary">
                            </fieldset>
                        </form>
                    </div>
                </div>
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
            maxlength: 50
        },
        lastname: {
            required: true,
            maxlength: 50
        },
        email: {
            required: true,
            maxlength: 50,
            email: true
        },
        password: {
            required: true,
            maxlength: 50
        },
        password_confirm: {
            required: true,
            equalTo: "#password",
            maxlength: 50
        },
        conditions: {
            required: true
        },
        f_name: {
            required: true,
            maxlength: 50
        },
        f_rfc: {
            required: true,
            maxlength: 50
        },
        f_address: {
            required: true,
            maxlength: 50
        },
        f_zipcode: {
            required: true,
            maxlength: 50
        },
        f_state: {
            required: true,
            maxlength: 50
        },
        f_city: {
            required: true,
            maxlength: 50
        }
    },
    messages: {
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
}); /* validate for submit*/



$(document).on('change', '#state,#f_state', function(e) {
    var who = '';

    if ($(this).attr('id') == 'f_state') { //esto es para reutilizar funcion
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
        $('#' + who + 'city option').remove();
        $.each(ciudades, function(key, text) {
            var newOption = new Option(text, key, false, false);
            $('#' + who + 'city').append(newOption);
        });
    });
});
</script>
@stop