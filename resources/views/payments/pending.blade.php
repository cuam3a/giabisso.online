
@extends('layouts.website')
@section('extra-head')
    <link rel="stylesheet" href="{{ asset('/website/fonts/line-awesome/css/line-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/website/css/about-us.css') }}">
@stop
@section('content')
<section class="canceled">
    <div class="container">
        <div class="row" style="margin:20px 0px;">
            <div class="col-xs-12 col-md-12 text-center">        
                <h3><i class="fa fa-send"></i> <span class="badge badge-success">Pago pendiente</span></h3>
                <hr>
                <h5>Tu pago está pendiente, enviamos las instrucciones de pago a tu correo. Gracias.</h5>
            </div>
        </div>
    </div>
</section>
@stop


