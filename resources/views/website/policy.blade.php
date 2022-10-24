@extends('layouts.website')
@section('extra-head')
    <link rel="stylesheet" href="{{ asset('/website/fonts/line-awesome/css/line-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/website/css/about-us.css') }}">
@stop
@section('content')

<section class="delivery-policy">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-md-12">                
                <h1 class="">Políticas de envío y cancelación</h1>
            </div>
        </div>
        <div class="col-md-12">
            <hr>
        </div>
        <div class="row">
             <div class="col-xs-12 col-md-12">
                {!! $policy->value or '' !!}
            </div>
        </div>
    </div>
</section>
@stop
