@extends('layouts.admin')
@section('css')
    <link rel="stylesheet" href="{{ asset('/website/css/messages.css') }}">
@stop
@section('content')

<div class="m-portlet m-portlet--mobile">
    <div class="m-portlet__head">
        <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title">
                <h3 class="m-portlet__head-text">
                    Mensajes del Pedido - {{$order->folio()}}
                </h3>
            </div>
        </div>
        <div class="m-portlet__head-tools">
            <ul class="m-portlet__nav">
                <li class="m-portlet__nav-item">
                    <h5>{{$order->created_at->format('d/M/Y h:i a')}}</h5>
                </li>
            </ul>
        </div>
    </div>
</div>

<div class="m-portlet m-portlet--mobile">
    <div class="m-portlet__body">
        <div class="row">
            <div class="col-md-8">
                <div class="box-messages">
                    {!! $order->present()->showMessages($from) !!}
                </div>
                <div class="control-messsages">
                    <form method="POST" action="{{route('admin-send-message', $order->id)}}" class="form-group form-md-line-input">
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
            <div class="col-md-4">
                <b>Cliente</b>
                <table class="table">
                    <tr>
                        <td>Nombre</td>
                        <td>{{$order->fullName()}}</td>
                    </tr>
                    <tr>
                        <td>Teléfono</td>
                        <td>{{$order->phone}}</td>
                    </tr>
                </table>
                <b>Detalles del pedido</b>
                <table class="table">
                    <tr>
                        <td>Total</td>
                        <td>{{$order->getTotalMoneyAttribute()}}</td>
                    </tr>
                    <tr>
                        <td>Pago</td>
                        <td>{!! $order->getPaymentBadgeAttribute() !!}</td>
                    </tr>
                    <tr>
                        <td>Envío</td>
                        <td>{!! $order->getStatusBadgeAttribute() !!}</td>
                    </tr>
                </table>
                <div class="text-center">
                    <a target="blank" href="{{route('admin-order-detail', $order)}}">Ver pedido</a>
                </div>
            </div>
        </div>
    </div>
</div>

@stop

@section('js')
<script>
    // Scroll to bottom of box messages
    $(".box-messages")[0].scrollTo(0,document.querySelector(".box-messages").scrollHeight)

{{-- Mostrar el mensaje de error --}}
@if($errors->any())
    toastr.options = {
    "closeButton": true,
    "debug": false,
    "positionClass": "toast-top-center",
    "onclick": null,
    "showDuration": "1000",
    "hideDuration": "1000",
    "timeOut": "5000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
    }
    toastr.error("{{$errors->first()}}");
@endif

</script>
@stop