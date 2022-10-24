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
                    Envíos
                    <small>

                    </small>
            
                </h3>
            </div>
        </div>
        <div class="m-portlet__head-tools">
            <ul class="m-portlet__nav">
                <li class="m-portlet__nav-item">
                    <a id="btn-save-free-shipment" class="m-btn m-btn--pill m-btn--hover-brand btn btn-sm btn-secondary">Guardar</a>	
                </li>
            </ul>
        </div>
    </div>
</div>
<form class="frmFreeShipment" action="{{ route("admin-shipment-free") }}" method="POST" enctype="multipart/form-data">
    {{ csrf_field() }}
    <div class="m-portlet m-portlet--mobile">
        <div class="m-portlet__body">
            <!--begin: Search Form -->
            <div class="m-form m-form--label-align-right m--margin-top-20 m--margin-bottom-30">
                <div class="m-portlet__head" style="border:none!important">
                    <div class="m-portlet__head-caption" style="border-bottom: 1px solid #ebedf2;">
                        <div class="">
                            <span class="m-portlet__head-icon m--hide">
                                <i class="la la-gear"></i>
                            </span>
                            <div class="col-md-12">
                                <h3 class="m-portlet__head-text">
                                    Envío gratuito
                                </h3>
                            </div>

                            <div class="col-md-3">
                                <span class="m-switch m-switch--outline m-switch--success">
                                    <label>
                                    <input type="checkbox"  name="freeShipment" id="freeShipment">
                                    <span></span>
                                    </label>
                                </span>
                            </div>

                            <div class="row m--margin-bottom-10">
                                {{-- Minimum for free shipping --}}
                                <div class="col-md-12 minimum" style="display:none">
                                    <div class="col-md-3">
                                        <label><b>Monto mínimo</b></label>
                                    </div>
                                    
                                    <div id="minimum" class="col-md-12">
                                        <input type="text" class="form-control m-input" id="amount" value="{{$amount->value or ''}}" name="amount" placeholder="Ingrese aquí">
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    
                </div>

                <div class="m-portlet__body">
                        @foreach($shipment_types as $shipment)
                        <div class="row">
                            <div class="col-md-12" style="margin-top:10px">
                                <h3><label>{{$shipment->text}}</label></h3>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Costo:</label>
                                        <input type="text" class="form-control m-input cost" name="{{$shipment->name}}" value="{{$shipment->cost}}">
                                    </div>    
                                    <div class="col-md-6">
                                        <label>Tiempo de entrega:</label>
                                        <input type="text" class="form-control m-input" name="{{$shipment->name}}_description" placeholder="Tiempo entrega" value="{{$shipment->description}}">
                                    </div>    
                                </div>
                            </div>
                        </div>
                        @endforeach
                </div>
                <hr>
                {{-- Flat rate input --}}
                <div class="m-portlet__body">
                    <div class="row">
                        <div class="col-md-6">
                                <h3>
                                    <label>Tarifa predeterminada</label>
                                </h3>

                                <select class="form-control" name="flat_rate">
                                    @foreach($shipment_types as $shipment)
                                    <option {{$shipment->default == 1 ? "selected": "" }} value="{{$shipment->id}}">{{$shipment->text}}</option>
                                    @endforeach
                                </select>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
<form>
@stop
@section('js')
<script src="/js/maskmoney/src/jquery.maskMoney.js" type="text/javascript"></script>
<script>
    var Config = [];
    var free_shipment = "{{ $free_shipment->value or ''}}";

    $(function() {
        $("#btn-save-free-shipment").on("click",function(){
            $(".frmFreeShipment").submit();
        });

        //$("#amount, #flat_rate").maskMoney();
        $("#flat_rate").maskMoney();

        $("#freeShipment").on("change",function(){
            if($(this).is(':checked')){
                $(".minimum").show();
                $("#amount").prop('required',true)
            }
            else{
                $(".minimum").hide();
                $("#amount").prop('required',false)
            }
        })

        if(free_shipment  == 'ON'){
            $("#freeShipment").attr('checked', true).trigger('change');
        }

        $(".cost, #amount").on("change",function(){
            var val = parseFloat($(this).val()).toFixed(2);
            if(isNaN(val)){
                $(this).val(0);
            }
            else{
                $(this).val(val);
            }
        })


    });
</script>
@stop