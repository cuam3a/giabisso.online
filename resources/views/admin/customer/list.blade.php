@extends('layouts.admin')
@section('css')
@stop
@section('content')
<div class="m-portlet m-portlet--mobile">
    <div class="m-portlet__head">
        <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title">
                <h3 class="m-portlet__head-text">
                    Clientes
                    <small>
                        Listado de clientes
                    </small>
                </h3>
            </div>
        </div>
        <div class="m-portlet__head-tools">
            <ul class="m-portlet__nav">
                <li class="m-portlet__nav-item">
                    <a href="#" class="void m-btn m-btn--pill m-btn--hover-brand btn btn-sm btn-secondary exportarClientes" >Exportar</a>{{--<button class="m-btn m-btn--pill m-btn--hover-brand btn btn-sm btn-secondary" >Exportar clientes</button>--}}	
                </li>	
            </ul>
        </div>	 
    </div>
</div>
<div class="m-portlet m-portlet--mobile">
    <div class="m-portlet__body">
        <!--begin: Search Form -->
        <div class="m-form m-form--label-align-right m--margin-top-20 m--margin-bottom-30">
            <div class="row align-items-center">
                <div class="col-xl-12 order-2 order-xl-1">
                    <div class="form-group m-form__group row align-items-center">
                        <div class="col-md-4 offset-md-8">
                            <div class="m-input-icon m-input-icon--left">
                                <input type="text" class="form-control m-input" placeholder="Buscar..." id="generalSearch">
                                <span class="m-input-icon__icon m-input-icon__icon--left">
                                    <span>
                                        <i class="la la-search"></i>
                                    </span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end: Search Form -->
        <!--begin: Datatable -->
        <div class="m_datatable"></div>
        <!--end: Datatable -->
    </div>
</div>
@stop
@section('js')
<script>
    var Config = [];
    Config['datatable'] = "{{route('admin-customer-list-ajax')}}";
    Config['detail'] = "{{route('admin-customer-detail',['customer' => 'customer_id'])}}";
</script>
<script src="/js/datatables/customers.js" type="text/javascript"></script>
<script>
    SuscribeActions = {
    focus: null,
    exportClient: function () {
        var action = "{{route('admin-customer-exportClients')}}";
        $("#modalRUsure form").attr('action', action);
        $("#modalRUsure").modal("hide");
    },
}

$(document).ready(function() {
    $('body').on('click','.exportarClientes', function(e){	
        modal.launch({
            'title': "Exportar Clientes",
            'body': "Desea exportar clientes <b>" + "</b>.<br> ¿Desea continuar?"
        }, SuscribeActions.exportClient);
    });
});
</script>
@stop