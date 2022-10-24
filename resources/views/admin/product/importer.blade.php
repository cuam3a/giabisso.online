@extends('layouts.admin')
@section('css')
<style>
label.error{
    display:block!important;
}
</style>
@stop
@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="m-portlet">
			<div class="m-portlet__head">
				<div class="m-portlet__head-caption">
					<div class="m-portlet__head-title">
						<h3 class="m-portlet__head-text">
							Importador de productos
						</h3>
					</div>
				</div>
			</div>
			<div class="m-portlet__body">
				<!--begin::Section-->
				<div class="m-section__content">
                    <form  id="frmImport" action="{{route('admin-product-import-products')}}" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <input type="file" name="excelFile" required style="width:100%">
                    </form>
					 @if(Session::has('counter_message'))
                        <div class="alert alert-{{Session::get('flash_type')}} alert-dismissible mt-3">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                            {!!Session::get('counter_message')!!}
                        </div>
                    @endif
				</div>
				<!--end::Section-->
            </div>
            <div class="m-portlet__foot">
                <div class="row align-items-center">
					<div class="col-lg-12 m--align-right">		
                        <a href="{{route('admin-product-list')}}" class="btn m-btn--hover-brand btn-secondary" style="margin-right:15px;">Volver</a>			
                        <button id="submitImport" type="reset" class="btn btn-primary">Subir</button>
					</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="m-portlet">
			<div class="m-portlet__head">
				<div class="m-portlet__head-caption">
					<div class="m-portlet__head-title">
						<h3 class="m-portlet__head-text">
							Archivo de ejemplo
						</h3>
					</div>
				</div>
			</div>
			<div class="m-portlet__body">
				<!--begin::Section-->
				<div class="m-section__content">
                    Este archivo muestra cuales son las columnas y el formato que deben tener los datos para que la importación de los productos sea correcta.
				</div>
				<!--end::Section-->
            </div>
            <div class="m-portlet__foot">
                <div class="row align-items-center">
					<div class="col-lg-12 m--align-right">		
                        <a href="{{ '/files/'.'ejemplo.xls'}}" class="btn m-btn--hover-brand btn-secondary" style="margin-right:15px;">Descargar</a>			
					</div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
@section('js')
<script type="text/javascript">
$(document).ready(function(){
    $("#submitImport").click(function() {
        if($("#frmImport").valid()){
            $(this).prop("disabled",true);
            $(this).html('Subiendo');            
            $(this).addClass('m-loader m-loader--light m-loader--right')
            $('#frmImport').submit();
        }
    });
    	$.validator.messages.required = 'Campo requerido';
});
</script>
@stop