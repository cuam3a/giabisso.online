@extends('layouts.admin')
@section('css')
@stop
@section('content')
<div class="m-portlet m-portlet--mobile">
    <div class="m-portlet__head">
        <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title">
                <h3 class="m-portlet__head-text">
                    Suscripciones
                    <small>
                        Listado de Suscriptores
                    </small>
                </h3>
            </div>
        </div>
        <div class="m-portlet__head-tools">
            <ul class="m-portlet__nav">
                <li class="m-portlet__nav-item">
                <a href="{{route('admin-export-suscribers')}}" class="m-btn m-btn--pill m-btn--hover-brand btn btn-sm btn-secondary" target="_blank">Exportar suscriptores</a>	
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
    Config['datatable'] = "{{route('admin-suscribe-list-ajax')}}";
    Config['delete'] = "{{route('admin-suscribe-delete','id')}}";
</script>
<script src="/js/datatables/suscribe.js" type="text/javascript"></script>
<script>
SuscribeActions = {
    focus: null,
    deleteItem: function () {
        var _s = SuscribeActions;
        var action = Config["delete"].replace("id", _s.focus);
        var newForm = jQuery('<form>', {
            'action': action,
            'method': 'GET'
        });
        newForm.appendTo('body').submit();
    },
}

$(document).ready(function() {
    $('body').on('click','.deleteSuscribe', function(e){	
        SuscribeActions.focus = $(this).data('id');
        modal.launch({
            'title': "Eliminar suscriptor",
            'body': "Se eliminará suscriptor <b>" + $(this).data('email') + "</b>.<br> ¿Desea continuar?"
        }, SuscribeActions.deleteItem);
    });
});
</script>
@stop