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
                    SEO
                    <small>

                    </small>
            
                </h3>
            </div>
        </div>
        <div class="m-portlet__head-tools">
            <ul class="m-portlet__nav">
                <li class="m-portlet__nav-item">
                    <a id="btn-save-keywords" class="m-btn m-btn--pill m-btn--hover-brand btn btn-sm btn-secondary">Guardar</a>	
                </li>
            </ul>
        </div>
    </div>
</div>
<form class="frmKeywords" action="{{ route("admin-seo-save") }}" method="POST" enctype="multipart/form-data">
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
                                Palabras clave
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__body">
                    <div class="form-group m-form__group">
                            <input type="text" name="keywords" id="keywords" class="form-control" style="width:100%"
                    data-role="tagsinput" value="{{ $seo_keywords->value or '' }}"placeholder="Escriba las palabras clave separadas por coma" />
                    </div>
                </div>

                <div class="m-portlet__head" style="border:none!important">
                    <div class="m-portlet__head-caption" style="border-bottom: 1px solid #ebedf2;">
                        <div class="m-portlet__head-title">
                            <span class="m-portlet__head-icon m--hide">
                                <i class="la la-gear"></i>
                            </span>
                            <h3 class="m-portlet__head-text">
                                Descripción del sitio
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__body">
                    <div class="form-group m-form__group">
                            <input type="text" name="description" id="description" class="form-control" style="width:100%"
                             value="{{ $seo_description->value or '' }}" placeholder="Ingrese aquí"/>
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
<script src="//cdn.jsdelivr.net/bootstrap.tagsinput/0.4.2/bootstrap-tagsinput.min.js"></script>
<script>
    var Config = [];
    $(function() {
        $("#btn-save-keywords").on("click",function(){
            $(".frmKeywords").submit();
        });

    });
</script>


@stop