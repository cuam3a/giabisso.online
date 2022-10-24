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
                    Redes sociales
                    <small>

                    </small>
            
                </h3>
            </div>
        </div>
        <div class="m-portlet__head-tools">
            <ul class="m-portlet__nav">
                <li class="m-portlet__nav-item">
                    <a id="btn-save-social" class="m-btn m-btn--pill m-btn--hover-brand btn btn-sm btn-secondary">Guardar</a>	
                </li>
            </ul>
        </div>
    </div>
</div>
<form class="frmSocial" action="{{ route("admin-social-networks-save") }}" method="POST" enctype="multipart/form-data">
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
                                <i class="la la-facebook"></i> Facebook
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__body">
                    <div class="form-group m-form__group">
                            <input type="text" name="facebook" id="facebook" class="form-control" style="display:inline-block"
                    value="{{ $facebook->value or '' }}" placeholder="Enlace Facebook" />
                    </div>
                </div>

                <div class="m-portlet__head" style="border:none!important">
                    <div class="m-portlet__head-caption" style="border-bottom: 1px solid #ebedf2;">
                        <div class="m-portlet__head-title">
                            <span class="m-portlet__head-icon m--hide">
                                <i class="la la-gear"></i>
                            </span>
                            <h3 class="m-portlet__head-text">
                                <i class="la la-instagram"></i> Instragram
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__body">
                    <div class="form-group m-form__group">
                            <input type="text" name="instagram" id="instagram" class="form-control" style="display:inline-block"
                             value="{{ $instagram->value or '' }}" placeholder="Enlace Instagram"/>
                    </div>
                </div>

                <div class="m-portlet__head" style="border:none!important">
                    <div class="m-portlet__head-caption" style="border-bottom: 1px solid #ebedf2;">
                        <div class="m-portlet__head-title">
                            <span class="m-portlet__head-icon m--hide">
                                <i class="la la-gear"></i>
                            </span>
                            <h3 class="m-portlet__head-text">
                                <i class="la la-youtube"></i>  Youtube
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__body">
                    <div class="form-group m-form__group">
                            <input type="text" name="youtube" id="youtube" class="form-control" style="display:inline-block"
                             value="{{ $youtube->value or '' }}" placeholder="Enlace Youtube"/>
                    </div>
                </div>

                <div class="m-portlet__head" style="border:none!important">
                    <div class="m-portlet__head-caption" style="border-bottom: 1px solid #ebedf2;">
                        <div class="m-portlet__head-title">
                            <span class="m-portlet__head-icon m--hide">
                                <i class="la la-gear"></i>
                            </span>
                            <h3 class="m-portlet__head-text">
                                <i class="la la-twitter"></i>  Twitter
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__body">
                    <div class="form-group m-form__group">
                            <input type="text" name="twitter" id="twitter" class="form-control" style="display:inline-block"
                             value="{{ $twitter->value or '' }}" placeholder="Enlace Twitter"/>
                    </div>
                </div>

                <div class="m-portlet__head" style="border:none!important">
                    <div class="m-portlet__head-caption" style="border-bottom: 1px solid #ebedf2;">
                        <div class="m-portlet__head-title">
                            <span class="m-portlet__head-icon m--hide">
                                <i class="la la-gear"></i>
                            </span>
                            <h3 class="m-portlet__head-text">
                                <i class="la la-google-plus"></i>  Google+
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__body">
                    <div class="form-group m-form__group">
                            <input type="text" name="googleplus" id="googleplus" class="form-control" style="display:inline-block"
                             value="{{ $googleplus->value or '' }}" placeholder="Enlace Google+"/>
                    </div>
                </div>

                <div class="m-portlet__head" style="border:none!important">
                    <div class="m-portlet__head-caption" style="border-bottom: 1px solid #ebedf2;">
                        <div class="m-portlet__head-title">
                            <span class="m-portlet__head-icon m--hide">
                                <i class="la la-gear"></i>
                            </span>
                            <h3 class="m-portlet__head-text">
                                <i class="la la-pinterest"></i> Pinterest
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__body">
                    <div class="form-group m-form__group">
                            <input type="text" name="pinterest" id="pinterest" class="form-control" style="display:inline-block"
                             value="{{ $pinterest->value or '' }}" placeholder="Enlace Pinterest"/>
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
        $("#btn-save-social").on("click",function(){
            $(".frmSocial").submit();
        });
    
        $('input').on('change', function(){
            setGoButton();
        })

        $('input').trigger('change');
        
        function setGoButton(){
            $("input").each(function(index,el){

                if($(el).val() != ''){
                    $(el).css({'width':'95%'});
                    $(el).siblings('.go-link').remove();
                    $(el).parent().append('<a target="_blank" href="'+$(el).val()+'" class="go-link pull-right m-btn m-btn--pill m-btn--hover-brand btn btn-sm btn-secondary">Ir</a>')
                    
                }
                else{
                    $(el).siblings('.go-link').remove();
                    $(el).css('width','100%');
                }
                
            })
        }
    });
</script>


@stop