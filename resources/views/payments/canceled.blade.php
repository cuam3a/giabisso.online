
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
                <h3><i class="fa fa-times"></i> <span class="badge badge-warning">Pago cancelado</span></h3>
                <hr>
                <h5>Has cancelado el pago</h5>
                <a class="btn btn-success" href="{!!$link!!}">Intenta nuevamente</a>
            </div>
        </div>
    </div>
</section>
@stop


