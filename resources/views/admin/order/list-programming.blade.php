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
                    Pedidos Programados
                    <small>
                        Listado de pedidos
                    </small>
                </h3>
            </div>
        </div>
        <div class="m-portlet__head-tools">
            <ul class="m-portlet__nav">
                <li class="m-portlet__nav-item">
                <a href="#"  class="void m-btn m-btn--pill m-btn--hover-brand btn btn-sm btn-secondary exportPedido" >Exportar</a> {{-- <button type="submit" class="m-btn m-btn--pill m-btn--hover-brand btn btn-sm btn-secondary" id="descargarPedidos">Exportar pedidos</button>	 --}}
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
                            <div class="m-form__group m-form__group--inline">
                                <div class="m-form__label">
                                    <label>Estatus:</label>
                                </div>
                                <div class="m-form__control">
                                    <select class="form-control m-bootstrap-select" id="status" name="estatus">
                                        <option value="">Todos</option>
                                        @foreach($statuses as $id => $name)<option value="{{$id}}">{{$name}}</option>@endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="d-md-none m--margin-bottom-10"></div>
                        </div>
                        <div class="col-md-4">
                            <!--<div class="m-form__group m-form__group--inline">
                                <div class="m-form__label">
                                    <label class="m-label m-label--single">Pago:</label>
                                </div>
                                <div class="m-form__control">
                                    <select class="form-control m-bootstrap-select" id="payment" name="pago">
                                        <option value="">Todos</option>
                                        @foreach($payment as $id => $name)<option value="{{$id}}">{{$name}}</option>@endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="d-md-none m--margin-bottom-10"></div>-->
                        </div>
                        <div class="col-md-4">
                            <div class="m-input-icon m-input-icon--left">
                                <input type="text" class="form-control m-input" placeholder="Buscar..." id="generalSearch" >
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
    Config['datatable'] = "{{route('admin-order-list-programming-ajax')}}";
    Config['detail'] = "{{route('admin-order-programming-detail',['product' => 'product_id'])}}";
    Config['export'] = "{{route('admin-order-export')}}";
</script>
<script>
$(document).ready(function() {
    $('body').on('click','.exportPedido', function(e){	
        modal.launch({
            'title': "Exportar pedidos",
            'body': "Desea exportar pedidos<b>" + "</b>.<br> ¿Desea continuar?",
        });
    });
    
    $("#modalRUsure button[type='submit']").on("click",function(e){
        e.preventDefault();
        var ruta = "{{route('admin-order-export')}}"
                    +'?estatus=' + $('#status').val()
                    +'&pago='+ $('#payment').val();
        window.open(ruta,"_blank");
        $("#modalRUsure").modal("hide");
    });
});
</script>
<script src="/js/datatables/ordersProgramming.js" type="text/javascript"></script>
@stop