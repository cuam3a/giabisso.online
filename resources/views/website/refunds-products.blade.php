@extends('layouts.website')
@section('extra-head')
    <link rel="stylesheet" href="{{ asset('/website/css/refunds.css') }}">
@stop
<style>
    .folio-devolucion{
        background: #5aa407;
        padding: 10px;
        border-radius:5px;
        color:white;
        width:100%;
        font-size:16px;
        margin-bottom:10px;
    }
    .dias-devolucion{
        display:block;
        margin-top:10px;
    }
    .dias-devolucion b {
        font-size:16px;
    }
</style>

@section('content')
    <nav aria-label="breadcrumb" role="navigation">
        <div class="container">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                <li class="breadcrumb-item"><a href="{{route('my-account')}}">Mis pedidos
                </a></li>
            </ol>
        </div>
    </nav>
    <section class="payment-detail">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-info text-center">
                        <p>Sólo se pueden solicitar devoluciones de aquellos pedidos que hayan sido entregados @if($days > 0) y la fecha de entrega esté dentro de un plazo de <b>{{$days}}</b> días @endif</p>
                        <small class="text-right"><a href="{{route('policy')}}">Ver políticas de cancelación y envío</a></small>
                    </div>
                </div>
            </div>

            <form id="frmRefunds" action="{{route('refunds-customer')}}" method="post">
            {{ csrf_field()}}
             <div class="row">
                <div class="col-md-12">
                    <button type="submit" style="margin-bottom:10px" class="pull-right btn btn-common" id="store" aria-haspopup="true" aria-expanded="false">
                        <span>Solicitar devolución</span>
                    </button>
                </div>
            </div>
            @foreach($orders as $order)
                
                @if($order->getAvailableProductsForRefund() == 0)
                @php continue; @endphp 
                @endif

                @php $encripted_order_id =  Crypt::encrypt($order->id); @endphp     

            <div class="row">
                <div class="col-md-12 mb-3">
                    <span class="folio-devolucion">Pedido {{$order->folio()}}</span>
                </div>
                @if($order->getRefundsDaysRemains() != null)
                <div class="col-md-12">
                    <small class="dias-devolucion">
                        Dispones de <b>{{$order->getRefundsDaysRemains()}} día(s)</b> para solicitar la devolución de artículos en este pedido
                    </small>
                </div>
                @endif
                    
                <div class="col-12">
                    {{ Form::hidden('order_id[]', $encripted_order_id) }}
                    <div class="table-responsive m--margin-top-20">
                        <table class="table">
                        <thead>
                            <tr class="heading">
                                <td>Seleccione</td>
                                <td>Solicitar</td>
                                <td>Cantidad disponible</td>
                                <td>Producto</td>
                                <td>Motivo</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->order_details as $index => $detail)                      
                                @if($detail->getAvailableProductsForRefund() > 0)
                                <tr>
                                    <td class=""><input type="checkbox" class="c-product input-lg" name="product_id[{{ $encripted_order_id }}][{{$index}}]" value="{{$detail->product->id}}"></td>
                                    <td class=""><input type="number" class="form-control c-quantity input-sm" style="width:auto !important;" name="quantity[{{ $encripted_order_id }}][{{$index}}]" value="0" min="1" max="{{$detail->getAvailableProductsForRefund()}}" readonly="readonly"></td>
                                    <td class="available">{{$detail->getAvailableProductsForRefund()}}</td>
                                    <td class="d-flex flex-row ">
                                        <a href="{{route('product-detail',[$detail->product->id,$detail->product->slug])}}" target="_blank">
                                            <img src="{{ $detail->product->image }}" alt="{{$detail->product->name}}" class="rounded float-left img-fluid">
                                            <div class="d-flex flex-column align-self-stretch">
                                                <small>{{$detail->sku}}</small>
                                                <div>{{$detail->name}}</div>
                                            </div>
                                        </a>
                                    </td>
                                    {{-- <td>
                                        <a href="{{route('product-detail',[$detail->product->id,$detail->product->slug])}}" target="_blank">
                                            <img src="{{ $detail->product->image }}" alt="{{$detail->product->name}}" style="width:100px" class="rounded float-left img-fluid">
                                            <small>{{$detail->sku}}</small>
                                            <div>{{$detail->name}}</div>
                                            
                                        </a>
                                    </td> --}}
                                    <td>
                                        <select name="reason_id[{{ $encripted_order_id }}][{{$index}}]" class="form-control c-motivo" readonly="readonly">
                                        @foreach($reasons as $reason)
                                            <option value="{{$reason->id}}">{{$reason->description}}</option>
                                        @endforeach
                                        </select>
                                        <textarea name="another[{{ $encripted_order_id }}][{{$index}}]" class="c-another form-control d-none m-top-10" readonly="readonly" placeholder="Ingrese su motivo"></textarea>
                                    </td>
                                </tr>
                                @endif
                            @endforeach
                        </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endforeach
            </form>
        </div>
</section>
@stop

@section('js')
<script>

    $(".c-product").on("change",function(){
        if($(this).is(':checked')){
            $(this).addClass('selected');
            // $(this).parent().parent().find('.c-quantity').attr('readonly',false).val(0);
            $(this).parent().parent().find('.c-quantity').attr('readonly',false).val($(this).parent().parent().find('.available').text());
            $(this).parent().parent().find('.c-another').attr('readonly',false).val('');
            $(this).parent().parent().find('.c-motivo').attr('readonly',false).val(1).trigger('change');
        }
        else{
            $(this).parent().parent().find('.c-quantity').attr('readonly',true).val(0);
            $(this).parent().parent().find('.c-another').attr('readonly',true).val('');
            $(this).parent().parent().find('.c-motivo').attr('readonly',true).val(1).trigger('change');
            $(this).removeClass('selected');
        }
    })

    $(".c-motivo").on("change",function(){
        // 7 = Otro motivo...
        if($(this).val()==7){
            $(this).parent().parent().find('.c-another').removeClass('d-none').val('');
        }
        else{
            $(this).parent().parent().find('.c-another').addClass('d-none').val('');
        }
    }).mousedown (function (e) {
        if(!$(this).parent().parent().find('.c-product').is(':checked')){
            e.preventDefault();
            this.blur();
            window.focus();
        }
    });
    $(".btnCreateRefund").on("click",function(){
        $("#frmRefunds").submit();
    })
</script>
@stop