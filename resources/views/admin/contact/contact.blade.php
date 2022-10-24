@extends('layouts.admin')
<link rel="stylesheet" href="//cdn.jsdelivr.net/bootstrap.tagsinput/0.4.2/bootstrap-tagsinput.css" />

@section('css')
<style>
.m-card-user.m-card-user--sm .m-card-user__pic img{
    max-width: 80px!important;
}
.m-card-user .m-card-user__pic img{
    border-radius:0!important;
}
.m-card-user.m-card-user--sm .m-card-user__details .m-card-user__name {
    font-size: 1rem;
    line-height: 1.3;
}
.label-info{
    background-color: #5da814;
    padding:2px;
}


</style>
@stop

@section('content')
<div class="m-portlet m-portlet--mobile">
        @if(count($errors))
        <div class="alert alert-danger alert-dismissible" style="margin-bottom:10px">
            <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar"></button>
            <ul class="list-unstyled">
            @foreach($errors->all() as $error)
            <li><strong>{{$error}}</strong></li>@endforeach</ul></div>
            @endif
    <div class="m-portlet__head">
        <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title">
                <h3 class="m-portlet__head-text">
                    Contacto
                    <small>

                    </small>
            
                </h3>
            </div>
        </div>
        <div class="m-portlet__head-tools">
            <ul class="m-portlet__nav">
                <li class="m-portlet__nav-item">
                    <a id="btn-save-contact" class="m-btn m-btn--pill m-btn--hover-brand btn btn-sm btn-secondary">Guardar</a>	
                </li>
            </ul>
        </div>
    </div>
</div>
<form class="frmContact" action="{{ route("admin-contact-save") }}" method="POST" enctype="multipart/form-data">
    {{ csrf_field() }}
    <div class="m-portlet m-portlet--mobile">
        <div class="m-portlet__body">
            <!--begin: Search Form -->
            <div class="m-form m-form--label-align-right m--margin-top-20 m--margin-bottom-30">
                <div class="m-portlet__head" style="border:none!important">
                    <div class="m-portlet__head-caption" style="border-bottom: 1px solid #ebedf2;">
                        <div class="m-portlet__head-title">
                            <span class="m-portlet__head-icon m--hide">
                                <i class="la la-gear"></i>
                            </span>
                            <h3 class="m-portlet__head-text">
                                Nombre empresa
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__body">
                    <div class="form-group m-form__group">
                            <input type="text" name="corporation_name" id="corporation_name" class="form-control" style="width:100%"
                    value="{{ $corporation_name->value or old('corporation_name') }}" placeholder="Ingrese aquí" />
                    </div>
                </div>

                <div class="m-portlet__head" style="border:none!important">
                    <div class="m-portlet__head-caption" style="border-bottom: 1px solid #ebedf2;">
                        <div class="m-portlet__head-title">
                            <span class="m-portlet__head-icon m--hide">
                                <i class="la la-gear"></i>
                            </span>
                            <h3 class="m-portlet__head-text">
                                Razón social
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__body">
                    <div class="form-group m-form__group">
                            <input type="text" name="business_name" id="business_name" class="form-control" style="width:100%"
                    value="{{ $business_name->value or old('business_name')}}" placeholder="Ingrese aquí" />
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">

                            <div class="m-portlet__head" style="border:none!important">
                                <div class="m-portlet__head-caption" style="border-bottom: 1px solid #ebedf2;">
                                    <div class="m-portlet__head-title">
                                        <span class="m-portlet__head-icon m--hide">
                                            <i class="la la-gear"></i>
                                        </span>
                                        <h3 class="m-portlet__head-text">
                                            Teléfono
                                        </h3>
                                    </div>
                                </div>
                            </div>
                            <div class="m-portlet__body">
                                <div class="form-group m-form__group">
                                        <input type="tel" name="phone1" id="phone1" onkeypress='return isNumberKey(event);' class="form-control" style="width:100%"
                                        maxlength="10" value="{{ $phone1->value or old('phone1') }}" placeholder="Ingrese aquí"/>
                                </div>
                            </div>

                    </div>

                    <div class="col-md-6">

                            <div class="m-portlet__head" style="border:none!important">
                                <div class="m-portlet__head-caption" style="border-bottom: 1px solid #ebedf2;">
                                    <div class="m-portlet__head-title">
                                        <span class="m-portlet__head-icon m--hide">
                                            <i class="la la-gear"></i>
                                        </span>
                                        <h3 class="m-portlet__head-text">
                                            Teléfono 2
                                        </h3>
                                    </div>
                                </div>
                            </div>
                            <div class="m-portlet__body">
                                <div class="form-group m-form__group">
                                        <input type="tel" name="phone2" id="phone2" onkeypress='return isNumberKey(event);' class="form-control" style="width:100%"
                                           maxlength="10" value="{{ $phone2->value or old('phone2') }}" placeholder="Ingrese aquí"/>
                                </div>
                            </div>

                    </div>

                    <div class="col-md-4">
                        <div class="m-portlet__head" style="border:none!important">
                            <div class="m-portlet__head-caption" style="border-bottom: 1px solid #ebedf2;">
                                <div class="m-portlet__head-title">
                                    <span class="m-portlet__head-icon m--hide">
                                        <i class="la la-gear"></i>
                                    </span>
                                    <h3 class="m-portlet__head-text">
                                        Email pedidos
                                    </h3>
                                </div>
                            </div>
                        </div>
                        <div class="m-portlet__body">
                            <div class="form-group m-form__group">
                                    <input type="numbers" name="email_orders" id="email_orders" class="form-control" style="width:100%"
                                        value="{{ $email_orders->value or old('email_orders') }}" placeholder="Ingrese aquí"/>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">

                            <div class="m-portlet__head" style="border:none!important">
                                <div class="m-portlet__head-caption" style="border-bottom: 1px solid #ebedf2;">
                                    <div class="m-portlet__head-title">
                                        <span class="m-portlet__head-icon m--hide">
                                            <i class="la la-gear"></i>
                                        </span>
                                        <h3 class="m-portlet__head-text">
                                            Email contacto
                                        </h3>
                                    </div>
                                </div>
                            </div>
                            <div class="m-portlet__body">
                                <div class="form-group m-form__group">
                                        <input type="numbers" name="email_contact" id="email_contact" class="form-control" style="width:100%"
                                            value="{{ $email_contact->value or old('email_contact') }}" placeholder="Ingrese aquí"/>
                                </div>
                            </div>

                    </div>

                    <div class="col-md-4">

                            <div class="m-portlet__head" style="border:none!important">
                                <div class="m-portlet__head-caption" style="border-bottom: 1px solid #ebedf2;">
                                    <div class="m-portlet__head-title">
                                        <span class="m-portlet__head-icon m--hide">
                                            <i class="la la-gear"></i>
                                        </span>
                                        <h3 class="m-portlet__head-text">
                                            Email soporte
                                        </h3>
                                    </div>
                                </div>
                            </div>

                            <div class="m-portlet__body">
                                <div class="form-group m-form__group">
                                        <input type="numbers" name="email_support" id="email_support" class="form-control" style="width:100%"
                                            value="{{ $email_support->value or old('email_support') }}" placeholder="Ingrese aquí"/>
                                </div>
                            </div>

                    </div>
                
                </div>

                <div class="m-portlet__head" style="border:none!important">
                    <div class="m-portlet__head-caption" style="border-bottom: 1px solid #ebedf2;">
                        <div class="m-portlet__head-title">
                            <span class="m-portlet__head-icon m--hide">
                                <i class="la la-gear"></i>
                            </span>
                            <h3 class="m-portlet__head-text">
                                Dirección
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__body">
                    <div class="form-group m-form__group">
                            <input type="text" name="address" id="address" class="form-control" style="width:100%"
                    value="{{ $address->value or old('address') }}" placeholder="Calle número, Colonia, CP" />
                    </div>
                </div>

               


            </div>
            <!--end: Search Form -->
            <!--begin: Datatable -->
            <div class="m_datatable"></div>
            <!--end: Datatable -->
        </div>
    </div>
<form>
@stop
@section('js')
<script src="//cdn.jsdelivr.net/bootstrap.tagsinput/0.4.2/bootstrap-tagsinput.min.js"></script>
<script>
    var Config = [];
    $(function() {
        $("#btn-save-contact").on("click",function(){
            $(".frmContact").submit();
        });

        // Test regex
        var phoneRegExp = new RegExp(/^(?=.*[0-9])[+0-9]+$/);

    });

    function isNumberKey(evt) {
        var charCode = (evt.which) ? evt.which : event.keyCode;
        console.log(charCode);
        if (charCode != 43 &&  charCode > 31
            && (charCode < 48 || charCode > 57))
            return false;

        return true;
    }
    
</script>


@stop