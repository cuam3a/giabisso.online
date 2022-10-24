@extends('layouts.admin')
@section('content')

<div class="m-portlet m-portlet--mobile">
    <div class="m-portlet__head">
        <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title">
                <h3 class="m-portlet__head-text">
                    Mensajes
                    <small>
                        Listado de mensajes dentro de pedidos
                    </small>
                </h3>
            </div>
        </div>
    </div>
</div>

<div class="m-portlet m-portlet--mobile">
    <div class="m-portlet__body">
        <table class="table">
            <thead>
                <tr>
                    <th>Pedido</th>
                    <th>Cliente</th>
                    <th class="text-center">Mensajes</th>
                    <th class="text-center">Status</th>
                    <th>Ver</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($messages as $message)
                <tr>
                <td>{{$message->order->folio()}} - {{$message->order->getTotalMoneyAttribute()}}</td>
                    <td>{{$message->order->present()->getCustomerName()}}</td>
                    <td class="text-center">{{$message->counter}}</td>
                    <td class="text-center">{{$message->order->present()->newMessage()}}</td>
                    <td>
                    <a href="{{route('admin-messages-order',$message->order_id)}}" class="m-portlet__nav-link btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill">
                            <i class="la la-search"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>  
</div>

@stop