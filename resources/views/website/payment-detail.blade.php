@extends('layouts.website')
@section('extra-head')
    <link rel="stylesheet" href="{{ asset('/website/css/payment-detail.css') }}">
    <link rel="stylesheet" href="{{ asset('/website/css/messages.css') }}">
    <style>
        .box-messages{
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
                @if($order->payment_status == 1)
                <div class="row">
                    {!! $order->getPaymentStatusHtml() !!}
                    <div class="col-md-12 text-center mb-3">
                            <span>Estamos procesando tu pedido. Recibirás un correo de seguimiento cuando tu pedido esté listo</span>
                    </div>
                </div>                    
                @endif
             <div class="row">

                
                <div class="col-md-12">
                    @if(Session::has('flash_message'))
                     <div class="alert alert-success alert-dismissible ">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close"></a>
                         {!! Session::get('flash_message') !!} 
                      </div>
                    @endif
                </div>
                


                 <div class="col-8">
                    <div class="col-12 text-right flex-column flex-nowrap">
                        <h2>Folio: {{$order->folio()}}</h2>
                        <h6>Creado {{$order->date_created()}}</h6>
                        <span class="mr-3">Estatus: {!!$order->status_badge!!}</span>
                        <span>Estatus pago: {!! $order->payment_badge!!}</span>
                    </div>
                 </div>
                 <div class="col-4">
                     <a href="#" id="showMessages">
                     <div>
                         <span style="font-size: 50px;" class="fa fa-comments-o"></span>
                         <span class="badge badge-danger"></span>
                     </div>
                     <div>Ver mensajes @if ($order->new_message_customer == 1) <span class="badge badge-danger"> * </span>  @endif </div>
                     </a>
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
                                        <tr><th scope="col" colspan="4"></th></tr>
                                    </thead>
                                    <tr class="heading">
                                        <td>Cantidad</td>
                                        <td>Producto</td>
                                        <td>Precio unitario</td>
                                        <td>Importe</td>
                                    </tr>
                                    <tbody>
                                        @foreach($order->order_details as $detail)                            
                                        <tr>
                                            <td>{{$detail->quantity}}</td>
                                            <td class="d-flex flex-row ">
                                                <a href="{{route('product-detail',[$detail->product->id,$detail->product->slug])}}" target="_blank">
                                                    <img src="{{ $detail->product->image }}" alt="{{$detail->product->name}}" class="rounded float-left img-fluid">
                                                    <div class="d-flex flex-column align-self-stretch">
                                                        <small>{{$detail->sku}}</small>
                                                        <div>{{$detail->name}}</div>
                                                    </div>
                                                </a>
                                                <!--<div style="padding: 15px;">
                                                    <form method="POST" action="{{route('add-product-to-cart',['product' => $detail->product->id,'ban' => 1])}}">
                                                        {!! Form::token() !!}
                                                        <input type="hidden" name="quantity" value="1">
                                                        <button class="btn btn-success">
                                                            <i class="fa fa-dollar"></i>  Volver a comprar 
                                                        </button>
                                                    </form>
                                                </div>-->
                                            </td>
                                            <td>${{$detail->unit_price}} MXN</td>
                                            <td>${{$detail->amount}} MXN</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="total d-flex flex-row flex-wrap">
                                    <div class="col-9"><label>Subtotal</label></div><div class="col-3"><span>{{dinero($order->subtotal) }}</span></div>
                                    <div class="col-9"><label>Envio</label></div><div class="col-3"><span>{{dinero($order->shipping) }}</span></div>
                                    <div class="col-9"><label class="totalLbl">Total</label></div><div class="col-3"><span class="totalLbl">{{dinero($order->total) }}</span></div>
                                </div>
                            </div>
                        </div>
                        <!--<div class="row">
                            <div class="col-md-12">
                                <div class="pull-right">{!!$link!!}</div>
                            </div>
                        </div>-->
                </div>
                {{-- Mensajes --}}
                <div class="col-md-4 d-none" id="messages-section">
                    <div class="box-messages">
                        {!! $order->present()->showMessages($from) !!}
                    </div>
                    <div class="control-messsages">
                        <form method="POST" action="{{route('send-message', [$order->id, $order->email])}}" class="form-group form-md-line-input">
                            <div class="input-group-control">
                                <textarea name="new_message" class="form-control" placeholder="Enviar mensaje..." required></textarea>
                            </div>
                            <span class="pt-3 float-right">
                                <button class="btn btn-success" type="submit">Enviar</button>
                            </span>
                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                        </form>
                    </div>
                </div>
                </div>
            </div>
            
        </div>

            <div class="row">
                <div class="col-6 col-sm-6 col-md-6">
                    <h6>Envío</h6>
                    <div class="delivery">
                        <p class="address">{!!$order->fullAddress()!!}</p>
                        <div>Celular: {{$order->cell_phone}}</div>
                        <div>Telefóno: {{$order->phone}}</div>
                        <div>Email: {{$order->email}}</div>
                    </div>
                </div>
                <!--@if($order->payment_status == App\Models\Order::$payment_text['pagado'])
                <div class="col-6 col-sm-6 col-md-6">                     
                    <h6>Pago</h6>
                    @if($payment)
                        <div class="pay-detail">
                            <table class="table table-striped">
                                <tbody>
                                    <tr>
                                        <th scope="row">Nombre:</th>
                                        <td>{{$payment->card_holder}}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Tipo de pago:</th>
                                        <td>{{$payment_type_text}}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Número de tarjeta:</th>
                                        <td>•••• •••• •••• {{$payment->last_four_digits}}</td>
                                    </tr>
                                    {{-- <tr>
                                        <th scope="row">Expiración</th>
                                        <td>04/2020</td>
                                    </tr> --}}
                                    <tr>
                                        <th scope="row">Fecha de pago</th>
                                        <td>{{$payment->created_at->format('d/m/Y')}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="pay-detail">
                            <table class="table table-striped">
                                <tbody>
                                    <tr>
                                        <th scope="row" colspan="2">Datos no disponibles</th>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
                @endif-->
                <div class="col-6 col-sm-6 col-md-6">
                    <h6>Facturación</h6>
                    <div class="delivery">
                        <div>{{$customer->f_rfc}}</div>
                        <div>{{$customer->f_name}}</div>
                        <p class="address">{{$customer->f_address}}</p>
                        <div>Telefóno: {{$customer->f_phone}}</div>
                        <div>Email: {{$customer->email}}</div>
                    </div>
                </div>
            </div>
        </div>
</section>
@stop

@section('js')
<script>




    

    // Show / Hide sections
    $("#showMessages").click(function(){
        $("#products-section").removeClass("col-md-12").addClass("col-md-8");
        $("#messages-section").removeClass("d-none");

        // Scroll to bottom of box messages
        $(".box-messages")[0].scrollTo(0,document.querySelector(".box-messages").scrollHeight)

        return false;
    })
</script>
@stop