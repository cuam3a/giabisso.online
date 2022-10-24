@extends('layouts.website')
@section('extra-head')
<link rel="stylesheet" href="{{ asset('/website/css/payment-detail.css') }}">
<link rel="stylesheet" href="{{ asset('/website/css/messages.css') }}">
<style>
.box-messages {
    margin-top: 16px;
    font-size: 90%;
    padding: 16px 10px;
    max-height: 240px;
}
</style>
@stop

@section('content')

<section class="payment-detail">
    <div class="container">
        <div class="row">


            <div class="col-md-12">
                @if(Session::has('flash_message'))
                <div class="alert alert-success alert-dismissible ">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close"></a>
                    {!! Session::get('flash_message') !!}
                </div>
                @endif
            </div>



            <div class="col-10">
                <div class="col-12 text-right flex-column flex-nowrap">
                    <h6>Folio: {{$order->id}}</h6>
                    <h4>Creado {{$order->date_created()}}</h4>
                    <span class="mr-3">Estatus: {!!$order->status_badge!!}</span>
                </div>
            </div>
            <div class="col-4">
            </div>

        </div>
        {{-- Products Section --}}
        <div class="products-messages-section">
            <div class="row">
                <div class="col-md-12 form-inline">
                    <div class="col-md-12" id="products-section">
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table table-products">
                                        <thead>
                                            <tr>
                                                <th scope="col" colspan="4"></th>
                                            </tr>
                                        </thead>
                                        <tr class="heading">
                                            <td>Cantidad</td>
                                            <td>Producto</td>
                                            <td>Precio unitario</td>
                                            <td>Importe</td>
                                        </tr>
                                        <tbody>
                                            @foreach($orderDet as $detail)
                                            <tr>
                                                <td>{{$detail->quantity}}</td>
                                                <td class="d-flex flex-row ">
                                                    
                                                        <div class="d-flex flex-column align-self-stretch">
                                                            <small>{{$detail->sku}}</small>
                                                            <div>{{$detail->name}}</div>
                                                        </div>
                                                    

                                                </td>
                                                <td>${{$detail->unit_price}} MXN</td>
                                                <td>${{($detail->unit_price * $detail->quantity)}} MXN</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                           
                        </div>
                        
                    </div>
                   
                   
                </div>
            </div>
        </div>
    </div>
</section>
@stop

@section('js')
<script>
// Show / Hide sections
$("#showMessages").click(function() {
    $("#products-section").removeClass("col-md-12").addClass("col-md-8");
    $("#messages-section").removeClass("d-none");

    // Scroll to bottom of box messages
    $(".box-messages")[0].scrollTo(0, document.querySelector(".box-messages").scrollHeight)

    return false;
})
</script>
@stop