@extends('layouts.admin')
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
</style>
@stop
@section('content')
<div class="m-portlet m-portlet--mobile">
    <div class="m-portlet__head">
        <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title">
                <h3 class="m-portlet__head-text">
                    Interacciones por producto
                    <small>
                        Listado de usuarios
                    </small>
                </h3>
            </div>
        </div>
        <div class="m-portlet__head-tools">
            <ul class="m-portlet__nav">
                <li class="m-portlet__nav-item">
                <a href="#"  class="void m-btn m-btn--pill m-btn--hover-brand btn btn-sm btn-secondary exportUsersProd" >Exportar</a> {{-- <button type="submit" class="m-btn m-btn--pill m-btn--hover-brand btn btn-sm btn-secondary" id="descargarPedidos">Exportar pedidos</button>	 --}}
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
                        
                        <div class="col-md-4">
                            <div class="m-form__group m-form__group--inline" style="width:100%">
                                <div class="m-form__label" style="width:30%;">
                                        <label>Rango de fechas:</label>
                                </div>
                                <div class="m-form__control" style="width:70%;">
                                    <input type='text' class="form-control" id="daterange" readonly placeholder="Seleccionar rango de tiempo">
                                </div>
                            </div>
                            <div class="d-md-none m--margin-bottom-10"></div>
                        </div>  
                        <div class="col-md-4">
                            <div class="input-group form-control m-input pt-0 pb-0 pl-2 pr-0">
                                <span class="input-group-btn">
                                        <i class="la la-search"></i>
                                </span>
                                <input type="text" id="generalSearch" class="form-control m-input" placeholder="Búsqueda por SKU, nombre, descripción..." style="border:0px">
                                <span class="input-group-btn">
                                    <button class="btn btn-default" id="btn-toggle-product-search" data-type="false" type="button"># Producto</button>
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
    Config['datatable'] = "{{route('admin-product-customer-view-list-ajax')}}";
    Config['export'] = "{{route('admin-customer-export-interaction-products')}}";
    Config['edit'] = "{{route('admin-product-edit',['product' => 'id'])}}";//se pone 'id' por mientras, se sustituye luego por numero
    Config['statuses'] = {!! json_encode($status) !!};
    Config['imageType'] = {!! json_encode($imageType) !!};
    Config['imageTypeText'] = {!! json_encode($imageTypeText) !!};
</script>
<script>
$(document).ready(function() {
    $('#daterange').daterangepicker({
            buttonClasses: 'm-btn btn',
            applyClass: 'btn-primary',
            cancelClass: 'btn-secondary',
            locale: {
            format: 'DD/MM/YYYY',
            "applyLabel": "Aplicar",
                "cancelLabel": "Cancelar",
                "fromLabel": "Desde",
                "toLabel": "Hasta",
                "customRangeLabel": "Custom",
                "daysOfWeek": [
                    "Do",
                    "Lu",
                    "Ma",
                    "Mi",
                    "Ju",
                    "Vi",
                    "Sa"
                ],
                "monthNames": [
                    "Enero",
                    "Febrero",
                    "Marzo",
                    "Abril",
                    "Mayo",
                    "Junio",
                    "Julio",
                    "Agusto",
                    "Septiembre",
                    "Octubre",
                    "Noviembre",
                    "Diciembre"
                ],
        } 
    }, function(start, end, label) {
        $('#daterange_validate .form-control').val( start.format('DD-MM-YYYY') + ' / ' + end.format('DD-MM-YYYY'));
    });
    $('body').on('click','.exportUsersProd', function(e){	
        e.preventDefault();
        var ruta = Config['export']
                    +'?generalSearch=' + $('#generalSearch').val()
                    +'&searchByProductNumber='+ $('#btn-toggle-product-search').data("type")
                    +'&daterange='+ $('#daterange').val()
        window.open(ruta,"_blank");
    });
});
</script>
<script src="/js/datatables/product_customer_views.js" type="text/javascript"></script>
@stop