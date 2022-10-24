@extends('layouts.admin')
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

.modal-img {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 1; /* Sit on top */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgb(0,0,0); /* Fallback color */
    background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

</style>
@stop
@section('content')
<div class="m-portlet m-portlet--mobile">
    <div class="m-portlet__head">
        <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title">
                <h3 class="m-portlet__head-text">
                    Slider
                    <small>
                        Listado de imágenes
                    </small>
            
                </h3>
            </div>
        </div>
        <div class="m-portlet__head-tools">
            <ul class="m-portlet__nav">
                <li class="m-portlet__nav-item">
                    <a href="{{ route('admin-add-slider-img') }}"class="m-btn m-btn--pill m-btn--hover-brand btn btn-sm btn-secondary">Agregar imagen</a>	
                </li>
            </ul>
        </div>
    </div>
</div>

<div class="m-portlet m-portlet--mobile">
    <div class="m-portlet__body">
        <!--begin: Search Form -->
        <div class="m-form m-form--label-align-right m--margin-top-20 m--margin-bottom-30">
            <div class="row align-items-center">
                <div class="col-xl-12 order-2 order-xl-1">
                    <div class="form-group m-form__group row align-items-center">
                        <div class="offset-md-4 col-md-4">
                            <div class="m-form__group m-form__group--inline">
                                <div class="m-form__label">
                                    <label>Estatus:</label>
                                </div>
                                <div class="m-form__control">
                                    <select class="form-control m-bootstrap-select" id="status">
                                        <option value="">Todos</option>
                                        @foreach($statuses as $id => $name)<option value="{{$id}}">{{$name}}</option>@endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="d-md-none m--margin-bottom-10"></div>
                        </div>
                        <div class="col-md-4">
                        </div>
                        <div class="col-md-4">
                            <div class="m-input-icon m-input-icon--left">
                                <input type="text" class="form-control m-input" placeholder="Buscar..." id="generalSearch">
                                <span class="m-input-icon__icon m-input-icon__icon--left">
                                    <span>
                                        <i class="la la-search"></i>
                                    </span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end: Search Form -->
        <!--begin: Datatable -->
        <div class="m_datatable"></div>
        <!--end: Datatable -->
    </div>
</div>


<div class="modal fade" id="basic" tabindex="-1" role="basic" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Slider</h4>
            </div>
            <div class="modal-body"> 
                <img style="width:100%" >
            </div>
            <div class="modal-footer">
                <button type="button" class="btn dark btn-outline" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

@stop
@section('js')
<script>
    var Config = [];
    Config['datatable'] = "{{route('admin-order-list-ajax')}}";
    Config['estatus'] = "{{route('admin-toggle-status-slider',['slider_img' => 'slider_image_id'])}}";
    Config.imgs = {!! $images !!};
    Config.change_order_slider = "{{ route('admin-change-order-slider') }}";
    Config.delete_slider = "{{ route('admin-delete-slider', ['slider' => 'slider_replace']) }}";
    Config.edit_slider = "{{ route('admin-edit-slider-img', ['slider' => 'slider_replace']) }}";
    Config.csrf_field = '{{ csrf_field() }}'
    var data_tmp = Config.imgs;
</script>
<script src="/js/datatables/slider.js" type="text/javascript"></script>
@stop