@extends('layouts.website')
@section('extra-head')
    <link rel="stylesheet" href="{{ asset('/website/css/forgot-password.css') }}">
@stop

@section('content')
    <div class="forgot-password">
        <div class="container">
            @if(Session::has('flash_message'))
                <div class="row">
                    <div class="col-md-6 offset-md-3">       
                        <div class="alert alert-{{Session::get('flash_type')}} alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button><strong>{{Session::get('flash_title')}}</strong> {!!Session::get('flash_message')!!}</div>
                    </div>
                </div>
            @endif
            <div class="row">
                <div class="col-md-6 offset-md-3">
                    @if(count($errors))
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar">
                            @foreach($errors->all() as $error)
                            <span aria-hidden="true">&times;</span></button><strong>{{$error}}</strong>@endforeach</div>
                    @endif
                    <h1 class="title">Olvidé contraseña</h1>
                    <form action="{{route('send-reset-link-email')}}" method="POST">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="email">Correo electrónico <span class="required">*</span></label>
                            <input type="email" name="email" class="form-control form-control-sm">
                        </div>
                        
                        <div class="row m-top-40 ">
                            <div class="col-md-12 form-inline">
                                <div class="col-md-8"></div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <button type="submit" class="button--primary">Continuar</button>
                                    </div>
                                </div>
                            </div>  
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@stop

@section('js')
@stop