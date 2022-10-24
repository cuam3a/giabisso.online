@extends('layouts.website')
@section('extra-head')
<link rel="stylesheet" href="{{ asset('/website/css/delivery-info.css') }}">
<link rel="stylesheet" href="{{ asset('/website/css/cart-sidebar.css') }}">
<link rel="stylesheet" href="/js/select2/css/select2.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@7.25.0/dist/sweetalert2.min.css">
<style>
#address-options .active {
    background: #5aa407 !important;
}

.hide {
    display: none;
}

.swal2-warning {
    zoom: 20% !important
}
</style>
@stop
<?php
    $customer = Auth::guard('customer-web')->user();
?>
@section('content')

<section class="delivery-info mt-5">
    <div class="container">
        @if(Session::has('flash_message'))
        <div class="alert alert-{{Session::get('flash_type')}} alert-dismissible"><button type="button" class="close"
                data-dismiss="alert" aria-label="Cerrar"><span
                    aria-hidden="true">&times;</span></button><strong>{{Session::get('flash_title')}}</strong>
            {{Session::get('flash_message')}}.
        </div>
        @endif
        <div class="row">
            @if($cartBasket->count()==0)
            <div
                class="col-12 col-sm-12 d-flex flex-row align-items-center justify-content-center border no-products p-5 mb-3 mt-4">
                <div class="p-3"><img src="/website/img/sin-prods_carrito.png"></div>
                <div class="d-flex flex-column">
                    <h4 class="font-weight-bold cart">Aun no tienes productos en tu carrito</h4>
                    <p>Lorem Ipsum es simplemente el texto de relleno de las imprentas y archivos de texto.</p>
                    <a href="{{route('category-products')}}">Ir a productos</a>
                </div>
            </div>
            @else
            <div class="col-md-12 form-inline">
                <div class="col-sm-12 col-md-8">
                    <form method="POST" action="{{route('create-order')}}" id="frmCreateOrder">
                        {{ csrf_field() }}
                        @if ($errors->any())
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        @endif


                        <ul class="nav nav-pills nav-fill {{!Auth::guard('customer-web')->check() ? 'hide' : ''}}"
                            role="tablist" id="address-options">
                            <li class="nav-item">
                                <a class="nav-link registered-address {{Auth::guard('customer-web')->check() && count($addresses) > 0 ? 'active' : ''}}"
                                    data-toggle="tab" href="#m_tabs_5_1">DIRECCIONES REGISTRADAS</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link new-address {{Auth::guard('customer-web')->check() && count($addresses) == 0 ? 'active' : ''}}"
                                    data-toggle="tab" href="#m_tabs_5_2">AGREGAR NUEVA DIRECCIÓN</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane {{Auth::guard('customer-web')->check() && count($addresses) > 0 ? 'active' : ''}}"
                                id="m_tabs_5_1" role="tabpanel">
                                @if(Auth::guard('customer-web')->check())
                                @if(count($addresses) == 0)
                                <div
                                    class="col-12 col-sm-12 d-flex flex-row align-items-center border no-products mt-4">
                                    <div class="p-3"><img src="/website/img/sin-prods_pedidos.png"></div>
                                    <div class="d-flex flex-column">
                                        <h4 class="font-weight-bold">No tienes direcciones</h4>
                                        <p>Consulta nuestros productos y promociones!</p>
                                    </div>
                                </div>
                                @endif
                                @foreach($addresses as $key => $address)
                                <div style="padding:10px; background:#fafafa; margin-top:10px;">
                                    <input type="radio" name="selected_address" id="selected_address"
                                        value="{{$address->id}}">
                                    <strong> {{$address->address_name}}</strong>
                                </div>
                                <div>
                                    <ul style="list-style-type: none;">
                                        <li>{{$address->name}} {{$address->lastname}}</li>
                                        <li>{{$address->address}} {{$address->street_number}} {{$address->suit_number}}
                                            {{$address->neighborhood}} {{$address->between_streets}}
                                            {{$address->zipcode}}
                                            {{$address->instructions_place}}</li>
                                        <li>{{$address->City['name']}}, {{$address->State['name']}}</li>
                                        <li>{{$address->phone}} {{$address->cell_phone}}</li>
                                    </ul>
                                </div>
                                @endforeach
                                @endif
                            </div>
                            <div class="tab-pane {{!Auth::guard('customer-web')->check() || count($addresses) == 0 ? 'active' : ''}}"
                                id="m_tabs_5_2" role="tabpanel">
                                <h4>TUS DATOS</h4>

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="name">Nombre(s)</label>
                                        <input type="text" name="name" class="form-control" value="{{@$customer->name}}"
                                            maxLength="150" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="lastname">Apellidos</label>
                                        <input type="text" name="lastname" class="form-control"
                                            value="{{@$customer->lastname}}" maxLength="120" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="email">Correo</label>
                                    <input type="text" class="form-control" name="email" value="{{@$customer->email}}"
                                        required>
                                </div>
                                @endif
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="phone">Teléfono</label>
                                        <input type="text" class="form-control" name="phone"
                                            value="{{@$customer->phone}}">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="cell_phone">Celular</label>
                                        <input type="text" class="form-control" name="cell_phone"
                                            value="{{@$customer->cell_phone}}" maxLength="30" required>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-8">
                                        <label for="address">Dirección</label>
                                        <input type="text" class="form-control" name="address" required maxLength="100"
                                            value="{{@$customer->f_address}}">
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label for="street_number">Número exterior</label>
                                        <input type="text" class="form-control" name="street_number" required
                                            maxLength="20">
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label for="suit_number">Número interior</label>
                                        <input type="text" class="form-control" name="suit_number" maxLength="20">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-8">
                                        <label for="between_streets">Entre calles</label>
                                        <input type="text" class="form-control" name="between_streets" required
                                            maxLength="150">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="neighborhood">Colonia</label>
                                        <input type="text" class="form-control" name="neighborhood" required
                                            maxLength="150">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-5">
                                        <label for="state">Estado</label>
                                        <select name="state" id="state" class="form-control select2"
                                            placeholder="Elige un estado" required>
                                            @foreach($states as $id => $name)
                                            <option value="{{$id}}"
                                                {{ ( $id == Auth::guard("customer-web")->user()->f_state) ? "selected" : "" }}>
                                                {{$name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-5">
                                        <label for="city">Ciudad</label>
                                        <select name="city" id="city" class="form-control select2"
                                            placeholder="Elige una ciudad" required>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label for="zipcode">Código postal</label>
                                        <input type="text" class="form-control" name="zipcode"
                                            value="{{@$customer->f_zipcode}}" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="instructions_place">Indicaciones</label>
                                    <textarea class="form-control" name="instructions_place" rows="3"
                                        placeholder="Ej.: Nos ubicamos frente a escuela"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="comments">Comentarios sobre pedido</label>
                                    <textarea class="form-control" name="comments" rows="3"></textarea>
                                </div>
                                <!--Datos de facturación
                                    <div class="form-group">
                                        <label class="check_container">¿Necesitas factura?
                                            <input type="checkbox" name="invoice_require" id="factura">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>-->
                                <div class="facturacion">
                                    <h4>DATOS DE FACTURACIÓN</h4>
                                    <div class="form-group">
                                        <label for="f_name">Razón social</label>
                                        <input type="text" class="form-control" name="f_name" required maxLength="250">
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="f_rfc">RFC</label>
                                            <input type="text" class="form-control" name="f_rfc" required>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="f_address">Dirección</label>
                                            <input type="text" class="form-control" name="f_address" required
                                                maxLength="250">
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="f_email">Correo electrónico</label>
                                            <input type="text" class="form-control" name="f_email" required>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="f_phone">Teléfono</label>
                                            <input type="text" class="form-control" name="f_phone" required>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="f_state">Estado</label>
                                            <select name="f_state" id="f_state" class="form-control select2" required>
                                                @foreach($states as $id => $name)
                                                <option value="{{$id}}">{{$name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-md-6" style="position:relative">
                                            <label for="f_city">Ciudad</label>
                                            <select name="f_city" id="f_city" class="form-control select2" required>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="is_registered_address" id="is_registered_address"
                                    value="{{Auth::guard('customer-web')->check() && count($addresses) > 0 ? 1 : 0}}">
                            </div>
                            <!-- /Tab-pane -->
                        </div>
                        <!--/Datos de facturación-->
                    </form>
                </div>
            
            <div class="col-sm-12 col-md-4">
                @include('layouts.includes.cart-sidebar')
            </div>
            </div>
        </div>
    </div>
</section>

@stop
@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@7.25.0/dist/sweetalert2.all.min.js"></script>
<script src="/js/select2/js/select2.min.js"></script>
<script src="/js/select2/js/i18n/es.js"></script>
<script>
var form_validator;
initValidator();

$("input:radio[name=selected_address]:first").attr('checked', true);

$(".new-address").on("click", function() {
    initValidator();
    $("#is_registered_address").val(0);
})

$(".registered-address").on("click", function() {
    $("#is_registered_address").val(1);
    form_validator.destroy();
});

$('.btnPay').click(function() {
    if (!$("#conditions").is(':checked')) {
        swal(
            'Aviso',
            'Debe aceptar los terminos y condiciones',
            'warning'
        );
        return;
    }

    _swalConfirm('Solicitar pedido', 'Al dar click en aceptar se generará tu pedido. ¿Deseas continuar?',
        function() {
            $('#frmCreateOrder').validate();
            $('#frmCreateOrder').submit();
        })

});

$('.btnPay-logged').click(function() {
    _swalConfirm('Solicitar pedido', 'Al dar click en aceptar se generará tu pedido. ¿Deseas continuar?',
        function() {
            $('#frmCreateOrderLogged').submit();
        })
});


$("#state,#f_state").select2({
    placeholder: 'Elige un estado',
    language: "es"
});

$("#city,#f_city").select2({
    placeholder: 'Elige una ciudad',
    language: "es"
});

$('.collapse').on('show.bs.collapse', function() {
    $(this).siblings('.card-header').addClass('active');
});

$('.collapse').on('hide.bs.collapse', function() {
    $(this).siblings('.card-header').removeClass('active');
});

$("#factura").change(function() {
    if (this.checked) {
        $('.facturacion').css('display', 'block');
        $('.facturacion input,.facturacion select').removeAttr("disabled");
    } else {
        $('.facturacion').css('display', 'none');
        $('.facturacion input,.facturacion select').attr('disabled', 'disabled');
    }
});

@if(isset($form['factura_requiere']) && $form['factura_requiere'] == "on")
$("#factura").prop('checked', true).trigger('change')
@endif


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
        $('#' + who + 'city').val("{{@$customer->f_city}}");
    });
});

/* Validación de boton */
var $inputs = $('#frmCreateOrder input');
$inputs.on("keyup", function() {
    valido = true;
    $inputs.each(function() {
        valido *= $(this).valid();
        return valido;
    });
    if (valido) {
        $("#frmCreateOrder").removeAttr("disabled");
    } else {
        $("#frmCreateOrder").attr('disabled', true);
    }
});
/* /Validación de boton */

function initValidator() {
    $.extend($.validator.messages, {
        maxLength: jQuery.validator.format("Este campo debe tener menos de {0} caracteres")
    });
    $.validator.messages.required = 'Campo requerido';
    form_validator = $("#frmCreateOrder").validate({
        rules: {
            email: {
                required: true,
                maxlength: 60,
                email: true
            },
            zipcode: {
                required: true,
                number: true,
                maxlength: 5,
                minlength: 5,
                digits: true
            },
            f_rfc: {
                required: true,
                maxlength: 13,
                minlength: 11
            },
            f_phone: {
                required: true,
                maxlength: 20,
            },
            f_email: {
                required: true,
                maxlength: 50,
                email: true
            }
        },
        messages: {
            email: {
                required: "Campo requerido",
                maxlength: "Este campo debe ser no mayor a 60 caracteres",
                email: "Correo electrónico inválido"
            },
            phone: {
                required: "Campo requerido",
                maxlength: "Este campo debe tener a 10 digitos",
                number: "Solo números",
                digits: "Solo números"
            },
            zipcode: {
                required: "Campo requerido",
                maxlength: "Este campo debe tener a 5 digitos",
                minlength: "Este campo debe tener a 5 digitos",
                number: "Solo números",
                number: "Solo números"
            },

            f_rfc: {
                required: "Campo requerido",
                maxlength: "Este campo debe tener máximo 13 digitos",
                minlength: "Este campo debe tener al menos 11 digitos"
            },
            f_email: {
                required: "Campo requerido",
                maxlength: "Este campo debe ser no mayor a 50 caracteres",
                email: "Correo electrónico inválido"
            },
            f_phone: {
                required: "Campo requerido",
                maxlength: "Este campo debe tener a 10 digitos",
                number: "Solo números",
                digits: "Solo números"
            }
        }
    }); /* validate for submit*/

}

function _swalConfirm(title, text, confirmFunction, type = 'warning') {
    swal({
        title: title,
        text: text,
        type: type,
        imageWidth: 100,
        imageHeight: 100,
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Aceptar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.value) {
            confirmFunction();
        }
    })
}

$('#state,#f_state').trigger('change');
</script>
@stop