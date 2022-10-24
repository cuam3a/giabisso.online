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
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar"></button>
            <ul class="list-unstyled">
            @foreach($errors->all() as $error)
            <li><strong>{{$error}}</strong></li>@endforeach</ul></div>
            @endif
    <div class="m-portlet__head">
        <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title">
                <h3 class="m-portlet__head-text">
                    Devoluciones
                    <small>
                        
                    </small>
            
                </h3>
            </div>
        </div>
        <div class="m-portlet__head-tools">
            <ul class="m-portlet__nav">
                <li class="m-portlet__nav-item">
                    <a id="btn-save-refunds" class="m-btn m-btn--pill m-btn--hover-brand btn btn-sm btn-secondary">Guardar</a>	
                </li>
            </ul>
        </div>
    </div>
</div>
<form class="frmRefunds" action="{{ route("admin-config-refunds-save") }}" method="POST" enctype="multipart/form-data">
    {{ csrf_field() }}
    <div class="m-portlet m-portlet--mobile">
        <div class="m-portlet__body">
            <!--begin: Search Form -->
            <div class="m-form m-form--label-align-right m--margin-top-20 m--margin-bottom-30">

                <div class="m-portlet__head" style="border:none!important">
                    <div class="m-portlet__head-caption" style="border-bottom: 1px solid #ebedf2;">
                        <div class="m-portlet__head-title">
                            <span class="m-portlet__head-icon m--hide">
                                <i class="la la-gear"></i>
                            </span>
                            <h3 class="m-portlet__head-text">
                                Máximo de días para devolución
                            </h3>
                        </div>
                    </div>
                </div>

                <div class="m-portlet__body">
                    <div class="form-group m-form__group">
                            <input type="number" name="refunds_days" id="refunds_days" class="form-control" style="width:100%"
                            value="{{ $refunds_days->value or old('refunds_days') }}" placeholder="Ingrese aquí"/>
                    </div>
                </div>

                <div class="m-portlet__head" style="border:none!important">
                    <div class="m-portlet__head-caption" style="border-bottom: 1px solid #ebedf2;">
                        <div class="m-portlet__head-title">
                            <span class="m-portlet__head-icon m--hide">
                                <i class="la la-gear"></i>
                            </span>
                            <h3 class="m-portlet__head-text">
                                Instrucciones de devolución
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__body">
                    <div class="form-group m-form__group">
                            <textarea class="summernote-es" name="refunds_instructions" id="refunds_instructions">{{ $refunds_instructions->value or old('refunds_instructions') }}</textarea>
                    </div>
                </div>

            </div>
            <!--end: Search Form -->
            <!--begin: Datatable -->
            <div class="m_datatable"></div>
            <!--end: Datatable -->
        </div>
    </div>
<form>
@stop
@section('js')
<script src="/metronic_v5.0.5/theme/default/dist/default/assets/demo/default/custom/components/forms/widgets/summernote.js" type="text/javascript"></script>
<script type="text/javascript" src="{{ asset('/js/summernote-es-ES.js') }}"></script>
<script>
    var Config = [];
    $(function() {
        $("#btn-save-refunds").on("click",function(){
            $(".frmRefunds").submit();
        });

        $('.summernote-es').summernote({
            // height: 300,                 // set editor height
            minHeight: 200,             // set minimum height of editor
            maxHeight: null,             // set maximum height of editor
            focus: true,                  // set focus to editable area after initializing summernote
            lang:'es-ES',
            // airMode: true

        });
    });

</script>


@stop