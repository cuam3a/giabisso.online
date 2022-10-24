@extends('layouts.admin')
@section('css')
<link href="/bower_components/bootstrap-fileinput/css/fileinput.min.css" rel="stylesheet" type="text/css" />
<style>
/*
https://bootsnipp.com/snippets/eNbOa
*/
.btn-file {
    position: relative;
    overflow: hidden;
}
.btn-file input[type=file] {
    position: absolute;
    top: 0;
    right: 0;
    min-width: 100%;
    min-height: 100%;
    font-size: 100px;
    text-align: right;
    filter: alpha(opacity=0);
    opacity: 0;
    outline: none;
    background: white;
    cursor: inherit;
    display: block;
}
.label.filename{
	word-break: break-word;
}   
.showImage{
	background-color:#efefef;
	height: auto;
    width: 100%;
}
.showImage img{
	max-height:100%;
	max-width:100%;
}

#shipment .error:not(input){
	top:40px;
	position: absolute;
}

.select2-container--default .select2-selection--single .select2-selection__rendered {
    color: #575962!important;
    height: calc(2.55rem + 2px);
    padding: 0.7rem 1.25rem;
}
</style>
@stop
@section('content')
@if(count($errors))
	<div class="alert alert-danger alert-dismissible">
		<button type="button" class="close" data-dismiss="alert" aria-label="Cerrar"></button>
		<ul class="list-unstyled">
		@foreach($errors->all() as $error)
		<li><strong>{{$error}}</strong></li>@endforeach</ul></div>
		@endif
<form class="frmProducto" action="{{ route('admin-product-save',['product' => $product->id]) }}" method="POST" enctype="multipart/form-data">
	{{ csrf_field() }}
	<div class="row">
		<div class="col-md-12">
			<div class="m-portlet m-portlet--tab">
				<div class="m-portlet__head">
					<div class="m-portlet__head-caption">
						<div class="m-portlet__head-title">
							<span class="m-portlet__head-icon m--hide">
								<i class="la la-gear"></i>
							</span>
							<h3 class="m-portlet__head-text">
								@if(!isset($product->id)) Crear producto @else {{$product->sku}} {{$product->name}}<small>Editar producto</small>@endif
							</h3>
						</div>
					</div>
					<div class="m-portlet__head-tools">
						<input type="hidden" id="option" name="option">
						<ul class="m-portlet__nav">

							<li class="m-portlet__nav-item">
								<a href="#" data-type="product" data-action="Duplicará" class="m-btn m-btn--pill m-btn--hover-brand btn btn-sm btn-secondary void duplicate-item" data-name="{{$product->sku.' '.$product->name}}" data-url="{{route('admin-product-duplicate',['product' => $product->id])}}" data-target="#modalRUsure" data-toggle="modal" >Duplicar</a>	
							</li>

							<li class="m-portlet__nav-item">
								<a href="#" data-type="product" data-action="{{$product->getStatusMsgAttribute($product->status)}}" class="m-btn m-btn--pill m-btn--hover-brand btn btn-sm btn-secondary void change-status" data-name="{{$product->sku.' '.$product->name}}" data-url="{{route('admin-product-change-estatus',['product' => $product->id])}}" data-target="#modalRUsure" data-toggle="modal" >{{ $product->getStatusMsgAttribute($product->status)}}</a>	
							</li>
							<li class="m-portlet__nav-item">
								<button type="submit" class="m-btn m-btn--pill m-btn--hover-brand btn btn-sm btn-secondary" id="update-product">Actualizar</button>	
							</li>
							<li class="m-portlet__nav-item">
								<button type="submit" class="m-btn m-btn--pill m-btn--hover-brand btn btn-sm btn-secondary" id="update-exit">Guardar y salir</button>	
							</li>	
						</ul>
					</div>	
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<!--begin::Portlet-->
			<div class="m-portlet m-portlet--tab">
				<div class="m-portlet__head">
					<div class="m-portlet__head-caption">
						<div class="m-portlet__head-title">
							<span class="m-portlet__head-icon m--hide">
								<i class="la la-gear"></i>
							</span>
							<h3 class="m-portlet__head-text">
								Datos Generales
							</h3>
						</div>
					</div>
				</div>
				<!--begin::Form-->
				<div class="m-form m-form--fit m-form--label-align-right">
						<div class="m-portlet__body">
							<div class="form-group m-form__group row">

								<div class="col-lg-4 m-form__group-sub">
									<label for="product_number">Número de artículo</label>
									<input type="text" class="form-control m-input" name="product_number" placeholder="Ingresa aquí"  value="{{$product->product_number}}" >
								</div>

								<div class="col-lg-4 m-form__group-sub">
									<label for="sku">SKU</label>
									<input type="text" class="form-control m-input" name="sku" placeholder="Ingresa aquí"  value="{{$product->sku}}" required>
								</div>

								<div class="col-lg-4 m-form__group-sub">
									<label for="brand">Marca</label>
									<select type="text" class="form-control m-input brands" name="brand" >
										@if($product->brand)
											<option value="{{$product->brand->id}}" selected="selected">{{$product->brand->name}}</option>
										@endif
									</select>
								</div>								
							</div>
							
							<div class="form-group m-form__group">
								<label for="name">Nombre</label>
								<input type="text" class="form-control m-input" name="name" placeholder="Ingresa aquí"  value="{{$product->name}}" required>
							</div>
							
							<div class="form-group m-form__group row">
								<?php
									$categoria = $product->category_id;
									$subcategoria = "";

									if(isset($product->category->parent_category)){
										$categoria = $product->category->parent_category->id;
										$subcategoria = $product->category_id;
									}
								?>
								<div class="col-lg-6 m-form__group-sub">
									<label for="category">Categoría</label>
									<select class="form-control m-input select2" id="category" name="category_id" required>
										@foreach($categories as $id => $name)<option value="{{$id}}">{{$name}}</option>@endforeach
									</select>
								</div>
								<div class="col-lg-6 m-form__group-sub">
									<label for="subcategory">Subcategoría</label>
									<select class="form-control m-input select2" id="subcategory" name="subcategory"></select>
								</div>
							</div>
							<div class="form-group m-form__group row">
								<div class="col-lg-4 m-form__group-sub">
									<label for="name">Cantidad en inventario</label>
									<input type="text" class="form-control m-input" name="stock" placeholder="Ingresa aquí"  value="{{$product->stock}}" required>
								</div>
								<div class="col-lg-4 m-form__group-sub">
									<label for="regular_price">Precio regular</label>
									<input type="text" class="form-control m-input" name="regular_price" placeholder="Ingresa aquí" value="{{$product->regular_price}}" required>
								</div>
								<div class="col-lg-4 m-form__group-sub">
									<label for="offer_price">Precio promoción</label>
									<input type="text" class="form-control m-input" name="offer_price" placeholder="Ingresa aquí" value="{{ $product->offer_price ? $product->offer_price : ''}}">
								</div>
							</div>
							<div class="form-group m-form__group row ">
								<div class="col-lg-6 m-form__group-sub dates" @if($product->offer_price)style="display:none"@endif>
									<label for="offer_date_start">Fecha inicio de promoción</label>
									<div class='input-group date' id='offer_date_start'>
										<input type='text' class="form-control m-input" name="offer_date_start" placeholder="Seleccione la fecha y hora" value="{{$product->offer_startDate()}}" required/>
										<span class="input-group-addon">
											<i class="la la-calendar glyphicon-th"></i>
										</span>
									</div>
								</div>
								<div class="col-lg-6 m-form__group-sub dates" @if($product->offer_price)style="display:none"@endif>
									<label for="offer_date_end">Fecha que finaliza promoción</label>
									<div class='input-group date' id='offer_date_end'>
										<input type='text' class="form-control m-input" name="offer_date_end" placeholder="Seleccione la fecha y hora" value="{{$product->offer_endDate()}}" required/>
										<span class="input-group-addon">
											<i class="la la-calendar glyphicon-th"></i>
										</span>
									</div>	
								</div>
							</div>
							<hr>
							<div class="form-group m-form__group row">
								<div class="col-lg-4 m-form__group-sub form-inline mt-2">
									<label for="name">Liquidacion</label>
									<input type="checkbox" class="form-control m-input ml-3" name="liquidado" @if($product->liquidado == 1)checked="checked"@endif>
								</div>
								<div class="col-lg-4 m-form__group-sub">
									<label for="regular_price">Precio Liquidacion</label>
									<input type="text" class="form-control m-input" name="liquidado_price" placeholder="Ingresa aquí" value="{{$product->liquidado_price}}" >
								</div>
							</div>
						</div>
				</div>
				<!--end::Form-->
			</div>
			<!--end::Portlet-->
		</div>
	</div>
	
	<!-- Lista de Precios -->
	<div class="row" id="shipment">
		<div class="col-md-12">
			<!--begin::Portlet-->
			<div class="m-portlet m-portlet--tab">
				<div class="m-portlet__head">
					<div class="m-portlet__head-caption">
						<div class="m-portlet__head-title">
							<span class="m-portlet__head-icon m--hide">
								<i class="la la-gear"></i>
							</span>
							<h3 class="m-portlet__head-text">
								Lista de Precios
							</h3>
						</div>
					</div>
				</div>
				<!--begin::Form-->
				<div class="m-form m-form--fit m-form--label-align-center">
					<div class="m-portlet__body">
						<div class="form-group m-form__group row">
							<div class="col-lg-12" id="alertPriceList"></div>
							<div class="col-lg-4 m-form__group-sub">
								<label for="category">Lista de Precio</label>
								<select class="form-control form-control-sm m-input" id="priceList" >
									@foreach($pricesLists as $id => $name)<option value="{{$id}}">{{$name}}</option>@endforeach
								</select>
							</div>
							<div class="col-lg-2 m-form__group-sub">
								<label for="regular_price">Precio</label>
								<input type="text" class="form-control form-control-sm m-input text-right" id="price_ListPrice" placeholder="Ingresa aquí" >
							</div>
							<div class="col-lg-4 m-form__group-sub mt-2">
								<a href="#" class="mt-4 m-btn m-btn--pill m-btn--hover-brand btn btn-sm btn-secondary void" id="btnSavePriceList">Guardar Precio</a>	
							</div>
						</div>
					</div>
				</div>
				<!--end::Form-->
			</div>
			<!--end::Portlet-->
		</div><!--div class="col-md-12"-->
	</div>	
	<!--end::Lista de Precios -->

	<!-- Envío -->
	<div class="row" id="shipment">
		<div class="col-md-12">
			<!--begin::Portlet-->
			<div class="m-portlet m-portlet--tab">
				<div class="m-portlet__head">
					<div class="m-portlet__head-caption">
						<div class="m-portlet__head-title">
							<span class="m-portlet__head-icon m--hide">
								<i class="la la-gear"></i>
							</span>
							<h3 class="m-portlet__head-text">
								Envío
							</h3>
						</div>
					</div>
				</div>
				<!--begin::Form-->
				<div class="m-form m-form--fit m-form--label-align-center">
					<div class="m-portlet__body">
						<div class="form-group m-form__group row">
							<div class="col-lg-4 m-form__group-sub">
									<label for="length">Ancho</label>
									<div class="input-group">
										<input type="text" class="form-control m-input shipment-field" name="width" placeholder="Ingresa aquí" value="{{$product->width or ''}}">
										<input type="hidden" class="unit dimensions" name="length_unit" value="{{$product->dimension_unit or 'cm'}}">
										<div class="input-group-btn">
											<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="selected-item dimensions-text">{{$product->length_unit or 'cm'}}</span><span class="caret"></span></button>
											<ul class="dropdown-menu">
												<li><a class="item-dropdown">cm</a></li>
												<li><a class="item-dropdown">pulg</a></li>
											</ul>
										</div><!-- /btn-group -->
									</div><!-- /input-group -->
							</div>
							<div class="col-lg-4 m-form__group-sub">
								<label for="length">Alto</label>
								<div class="input-group">
									<input type="text" class="form-control m-input shipment-field" name="height" placeholder="Ingresa aquí" value="{{$product->height or ''}}">
									<input type="hidden" class="unit dimensions" name="length_unit" value="{{$product->dimension_unit or 'cm'}}">
									<div class="input-group-btn">
									<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="selected-item dimensions-text">{{$product->length_unit or 'cm'}}</span><span class="caret"></span></button>
									<ul class="dropdown-menu">
										<li><a class="item-dropdown">cm</a></li>
										<li><a class="item-dropdown">pulg</a></li>
									</ul>
									</div><!-- /btn-group -->
								</div><!-- /input-group -->
							</div>
							<div class="col-lg-4 m-form__group-sub">
								<label for="length">Largo</label>
								<div class="input-group">
									<input type="text" class="form-control m-input shipment-field" name="length" placeholder="Ingresa aquí" value="{{$product->length or ''}}">
									<input type="hidden" class="unit dimensions" name="length_unit" value="{{$product->dimension_unit or 'cm'}}">
									<div class="input-group-btn">
									<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="selected-item dimensions-text">{{$product->length_unit or 'cm'}}</span><span class="caret"></span></button>
									<ul class="dropdown-menu">
										<li><a class="item-dropdown">cm</a></li>
										<li><a class="item-dropdown">pulg</a></li>
									</ul>
									</div><!-- /btn-group -->
								</div><!-- /input-group -->
							</div>
						</div>

						<div class="form-group m-form__group row">
							<div class="col-lg-4 m-form__group-sub">
								<label for="name">Peso</label>
								<div class="input-group">
									<input type="text" class="form-control m-input shipment-field" name="weight" placeholder="Ingresa aquí" value="{{$product->weight or ''}}">
									<input type="hidden" class="unit" name="weight_unit" value="{{$product->weight_unit or 'kg'}}">
									<div class="input-group-btn">
										<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="selected-item">{{$product->weight_unit or 'kg'}}</span><span class="caret"></span></button>
										<ul class="dropdown-menu">
											<li><a class="item-dropdown type-weight">kg</a></li>
											<li><a class="item-dropdown type-weight">lb</a></li>
										</ul>
									</div><!-- /btn-group -->
									
									

								</div><!-- /input-group -->
							</div><!-- /.col-lg-6 -->
						</div>

						<div class="form-group m-form__group row">
							<div id="container"></div>
						</div>

					</div>
				</div>
				<!--end::Form-->
			</div>
			<!--end::Portlet-->
		</div><!--div class="col-md-12"-->
	</div>	
	<!--end::Envío -->

	<!-- Tipo envío -->
	<div class="row" id="shipment_type">
			<div class="col-md-12">
				<!--begin::Portlet-->
				<div class="m-portlet m-portlet--tab">
					<div class="m-portlet__head">
						<div class="m-portlet__head-caption">
							<div class="m-portlet__head-title">
								<span class="m-portlet__head-icon m--hide">
									<i class="la la-gear"></i>
								</span>
								<h3 class="m-portlet__head-text">
									Tipo de envío
								</h3>
							</div>
						</div>
					</div>
					<!--begin::Form-->
					<div class="m-form m-form--fit m-form--label-align-center">
						<div class="m-portlet__body">
							<div class="form-group m-form__group row">
								<div class="col-lg-6 col-md-6 m-form__group-sub">
										<label for="shipment_id">Tipo</label>
										<div class="input-group">
											<select class="form-control m-input" name="shipment_id" id="shipment_id" placeholder="Selecciona un tipo">
												<option value="">Seleccione una opción</option>
												@foreach($shipment_types as $shipment)
												<option {{$shipment->id == $product->shipment_id ? "selected": "" }} value="{{$shipment->id}}">{{$shipment->text}}</option>
												@endforeach
											</select>
										</div><!-- /input-group -->
								</div>
								<div class="col-lg-6 col-md-6 m-form__group-sub">
									<label for="shipment_cost">Costo</label>
									<div class="input-group">
										<div class="input-group-btn">
											<input type="text" class="form-control m-input" name="shipment_cost" id="shipment_cost" placeholder="Ingresa aquí" value="{{$product->shipment_cost or ''}}">
										</div><!-- /btn-group -->
									</div><!-- /input-group -->
								</div>
							</div>
						</div>
					</div>
					<!--end::Form-->
				</div>
				<!--end::Portlet-->
			</div><!--div class="col-md-12"-->
		</div>	
		<!--end::Tipo envío -->

	<input type="hidden" name="saved_images" value="{{$saved_images}}">
	<div class="row">
		<div class="col-md-12">
			<!--begin::Portlet-->
			<div class="m-portlet m-portlet--tab">
				<div class="m-portlet__head">
					<div class="m-portlet__head-caption">
						<div class="m-portlet__head-title">
							<span class="m-portlet__head-icon m--hide">
								<i class="la la-gear"></i>
							</span>
							<h3 class="m-portlet__head-text">
								Imágenes
								{{-- <small>Máximo: 2mb por imagen</small> --}}
							</h3>
						</div>
					</div>
				</div>
				<!--begin::Form-->
				<div class="m-form m-form--fit m-form--label-align-center">
					<div class="m-portlet__body">
						<div class="form-group m-form__group row">
							<div class="col-lg-5 col-md-5 col-sm-12">
								<div class="showImage">
									<img src="" />
								</div>
								<label class="filename">{{$product->image_name}}</label>
								<span class="btn btn-default btn-file pull-right">
									<div class="btnName"></div>
									<input type="file" name="image" accept="image/*" >
								</span>
							</div>
							<div class="col-lg-7 col-md-7 col-sm-12">
								<input id="images" type="file" name="images[]" multiple accept="image/*" data-preview-file-type="text">
							</div>
							<div id="kartik-file-errors"></div>
						</div>
					</div>
				</div>
				<!--end::Form-->
			</div>
			<!--end::Portlet-->
		</div><!--div class="col-md-12"-->
	</div>	
	<div class="row">
		<div class="col-md-12">
			<!--begin::Portlet-->
			<div class="m-portlet m-portlet--tab">
				<!--begin::Form-->
				<div class="m-form m-form--fit m-form--label-align-right">
					<div class="m-portlet__head" style="border:none!important">
						<div class="m-portlet__head-caption" style="border-bottom: 1px solid #ebedf2;">
							<div class="m-portlet__head-title">
								<span class="m-portlet__head-icon m--hide">
									<i class="la la-gear"></i>
								</span>
								<h3 class="m-portlet__head-text">
									Descripción
								</h3>
							</div>
						</div>
					</div>
					<div class="m-portlet__body">
						<div class="form-group m-form__group">
							<textarea class="summernote" name="description">{{ $product->description }}</textarea>
						</div>
					</div>
					<div class="m-portlet__head" style="border:none!important">
						<div class="m-portlet__head-caption" style="border-bottom: 1px solid #ebedf2;">
							<div class="m-portlet__head-title">
								<span class="m-portlet__head-icon m--hide">
									<i class="la la-gear"></i>
								</span>
								<h3 class="m-portlet__head-text">
									Especificaciones
								</h3>
							</div>
						</div>
					</div>
					<div class="m-portlet__body">
						<div class="form-group m-form__group">
							<textarea class="summernote" name="specifications">{{ $product->specifications }}</textarea>
						</div>
					</div>
				</div>
				<!--end::Form-->
			</div>
			<!--end::Portlet-->
		</div>
	</div>
</form>
@stop
@section('js')
<script src="/metronic_v5.0.5/theme/default/dist/default/assets/demo/default/custom/components/forms/widgets/summernote.js" type="text/javascript"></script>
<script src="/metronic_v5.0.5/theme/default/dist/default/assets/vendors/custom/jquery-ui/jquery-ui.bundle.js" type="text/javascript"></script>
<script src="/metronic_v5.0.5/theme/default/dist/default/assets/demo/default/custom/components/forms/widgets/bootstrap-datetimepicker.js" type="text/javascript"></script>
<script src="/js/bootstrap-datetimepicker.es.js" type="text/javascript"></script>
<script src="/bower_components/bootstrap-fileinput/js/fileinput.min.js"></script>
<script src="/bower_components/bootstrap-fileinput/js/locales/es.js"></script>
<script src="/bower_components/bootstrap-fileinput/themes/fa/theme.js"></script>
<script src="/bower_components/bootstrap-fileinput/js/plugins/purify.min.js"></script>
<script src="/bower_components/bootstrap-fileinput/js/plugins/sortable.js"></script>
<script src="/js/maskmoney/src/jquery.maskMoney.js" type="text/javascript"></script>
<script src="/js/summernote/summernote-es-ES.js" type="text/javascript"></script>
<script src="/js/select2/js/i18n/es.js" type="text/javascript"></script>
<script type="text/javascript">
  $('.summernote').summernote({
    lang: 'es-ES' // default: 'en-US'
  });
	$('.select2').select2({language: "es"});
	$(".brands").select2({
		language: "es",
		tags: true,
		placeholder: "Elige marca",
		ajax: {
			url: '{{route("admin-brand-list-ajax")}}',
			delay: 250,
			dataType: 'json',
			data: function (params) {
				return {
					search: params.term,
					page: params.page || 1
				}
			},
			processResults: function(data,params) {
				// parse the results into the format expected by Select2
		      	// since we are using custom formatting functions we do not need to
		      	// alter the remote JSON data, except to indicate that infinite
		      	// scrolling can be used
		      	params.page = params.page || 1;
		      	return {
			        results: data.results,
			        pagination: {
			          	more: (params.page * 5) < data.total_count
			        }
		      	};
		    },
		    cache: true
		},
		createTag: function (params) {
			return {
			id: params.term,
			text: params.term,
			newOption: true
			}
		},
		templateResult: function (data) {
			var $result = $("<span></span>");
			if (data.newOption) {
				$result.text(data.text);
				$result.append(" <em>(nuevo)</em>");
			}else{
				if(data.text!='') $result.text(data.text);
				$result.text(data.name);
			}
			return $result;
		},
		templateSelection: function(data){
			if(data.text!='') return data.text;
			return data.name;
		},
		escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
		minimumInputLength: 1
	});

	var MainImage = {
		inputFile: $('input[name="image"]'),
		lblFilename: $('label.filename'),
		imgSrc: "{{$product->image != '' ? $product->image : '/uploads/products/product-placeholder.jpg'}}",
		imgType: "{{$product->image_type}}",//por mientras
		imageDisplay: $('div.showImage img'),
		types: {!! json_encode($imageType) !!},//por mientras
		btnLabel: $('.btnName'),
		init: function() {
			var _s = this;
			_s.setBtnLabel();
			if(_s.types[_s.imgType] == 'local') _s.imgSrc = _s.imgSrc;
			_s.imageDisplay.attr('src', _s.imgSrc);
			_s.inputFile.unbind('change');
			_s.inputFile.bind('change',function(event){
				var reader = new FileReader();	
				if(this.files[0].size > 2 * 1024 * 1024){
					toastr.warning("El tamaño de la imagen es mayor a 2mb, intenta con otra.", "Error") 
					return;
				}
				var file = $(this).prop('files')[0];				
				reader.onload = function (e) {
					_s.imageDisplay.attr('src',e.target.result);
					_s.lblFilename.html(file.name);
				}			
				reader.readAsDataURL(file);
			}); 
		},
		setBtnLabel: function(){
			var _s = this;
			var lbl = "Adjuntar";
			if(_s.imgType != "") lbl = "Cambiar";
			_s.btnLabel.html(lbl);
		}
	};
	MainImage.init();

	$("#images").fileinput({	
		autoReplace:false,
		initialPreviewAsData: true, // identify if you are sending preview data only and not the raw markup
		initialPreview: {!! json_encode($product->images()->orderBy('order')->pluck('path')) !!},
		initialPreviewConfig: {!! json_encode($product->images_preview()) !!},
		language: 'es',
		overwriteInitial: false,
		theme:'fa',
		showUploadedThumbs:true,
		maxFileSize: 2 * 1024,
		uploadUrl: "{!! route('admin-product-add-image') !!}",
		deleteExtraData:{
			_token: "{{ csrf_token() }}",
			id: "{{$product->id or ''}}",
    	},
		uploadExtraData:function(previewId, index) {
			var data = {
				_token: "{{ csrf_token() }}",
				id: "{{$product->id or ''}}",
				saved_images: $('input[name="saved_images"]').val(),
				maxFileCount: 1,
				showUpload: true,
				dropZoneEnabled: false
			};
			return data;
		},
		}).on('fileuploaderror',function(){
			toastr.warning("El tamaño de la imagen es mayor a 2mb, intenta con otra.", "Error");
		}).on('filesorted', function(e, params) {
			$.ajax({
				data: { _token: "{{ csrf_token() }}", sortItems: params.stack },
				method: "POST",
				url: "{!! route('admin-product-sort-images') !!}",
			});
		}).on('fileuploaded', function(event, data, previewId, index) {
			$('input[name="saved_images"]').val(JSON.stringify(data.response));
		}).on('filedeleted', function(event, key, jqXHR, data) {
			$('input[name="saved_images"]').val(jqXHR.responseText);
		}).on('filesuccessremove', function(event, id) {
			$.ajax({
				data: { _token: "{{ csrf_token() }}", key: id },
				method: "POST",
				url: "{!! route('admin-product-remove-image') !!}",
			});
		});
		shipment_error = true;

$(document).ready(function(){
	
	// $('input[name="regular_price"],input[name="offer_price"]').maskMoney();
	// $('input[name="width"], input[name="height"], input[name="length"], input[name="weight"]').maskMoney();

	$('input[name="regular_price"],input[name="offer_price"]').on("change",function(){
		if( isNaN(parseFloat($(this).val())) ){
			$(this).val(parseFloat(0).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'))
		}
		else{
			$(this).val(parseFloat($(this).val()).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'))
		}
	})

	$(".shipment-field").on("change",function(){
		var c = 0;
		$(".shipment-field").each(function(index,el){
			$(el).val() != '' ? c++ : c;

			if(c == 0 || c == 3){
				shipment_error = false;
				$(".shipment-field").attr('required', false);
			}
			else{
				$(".shipment-field").attr('required', true);
			}
		});
	})

	$(".frmProducto").validate();

	$.validator.messages.required = 'Campo requerido';
	$('#offer_date_start').datetimepicker({
		autoclose: true,
		language: 'es',
		format: 'dd/mm/yyyy hh:ii',
		defaultDate:  new Date("{!!$product->offer_startDate()!!}"),
		pickerPosition: 'bottom-left'
	});
	$('#offer_date_end').datetimepicker({
		autoclose: true,
		language: 'es',
		format: 'dd/mm/yyyy hh:ii',
		defaultDate:  new Date("{!!$product->offer_endDate()!!}"),
		pickerPosition: 'bottom-left',
	});

	$("#enable-product, #update-product, #update-exit").on("click",function(event){
		event.preventDefault();
		if($(this).attr('id') == 'update-product'){
			$("#option").val('update');
		}
		else if($(this).attr('id') == 'update-exit'){
			$("#option").val('update_exit');
		}

		$(".frmProducto").submit();
	})

	$("#frmProduct").submit(function() {

		$(this).attr('disabled','disabled');
		var wyswygD = $("<input>")
				.attr("type", "hidden")
				.attr("name", "description").val($('textarea[name="description"]').val());
		$(this).append($(wyswygD));
		var wyswygS = $("<input>")
				.attr("type", "hidden")
				.attr("name", "specifications").val($('textarea[name="specifications"]').val());
		$(this).append($(wyswygS));
	});

	$( "input[name='offer_price']").keyup(function() {
		if($(this).val() == '' || $(this).val() == 0){
			$(this).removeAttr('required');
			$('.dates').css('display','none');
			$('input[name="offer_date_start"],input[name="offer_date_end"]').attr('disabled','disabled');
		}else{
			$(this).attr('required',true);
			$('.dates').css('display','block');
			$('input[name="offer_date_start"],input[name="offer_date_end"]').removeAttr("disabled");
		}
	});

	$(".item-dropdown").on("click",function(){

		var el = $(this).text();
		var input = $(this).parent().parent().parent().siblings(".unit");
		var drop = $(this).parent().parent().siblings('button').children(".selected-item");
		
		/* Current input and drop */
		input.val(el);
		drop.text(el);	
		
		if(!$(this).hasClass('type-weight')){
			/* All dimensions inputs and drop */
			$(".dimensions").val(el);
			$(".dimensions-text").text(el);
		}

	});

	var Categories = {
		offerPrice: $("input[name='offer_price']"),
		sltCategory: $('#category'),
		sltSubcategory: $('#subcategory'),
		catVal: "{{$categoria or ''}}",
		subcatVal: "{{$subcategoria or false}}",
		init: function() {
			var _s = this;
				// _s.sltCategory.unbind('change');
				_s.sltCategory.bind('change', function(event) {
					$.ajax({
						method: "POST",
						url: "{{route('admin-category-get-subcategories')}}",
						data: {
						'_token': '{!! csrf_token() !!}',
						'category': $(this).val()
						}
					}).done(function(subcategories) {
						_s.sltSubcategory.find('option').remove();
						if(subcategories.length == 0){
							_s.sltSubcategory.attr('disabled','disabled');
							return false;
						}
						_s.sltSubcategory.removeAttr('disabled');
						_s.sltSubcategory.append(new Option('Elegir opción','', false, false));
						$.each(subcategories, function(key,text) {
							newOption = new Option(text,key, false, false);
							_s.sltSubcategory.append(newOption);
						});
						if(_s.subcatVal){
							_s.sltSubcategory.val(_s.subcatVal);//asignandole valor
							_s.subcatVal = false;
						}
					});
				});
			
			//Inicializando
			if(_s.catVal == ''){
				_s.sltCategory.find('option:first-child').trigger('change');
			}else{
				_s.sltCategory.val(_s.catVal).trigger('change');
			}			
			_s.offerPrice.trigger('keyup');
		}
	}

	$("#shipment_id").on("change",function(){
		var selected_id = $(this).val()
		if(!selected_id){
			$("#shipment_cost").val(0).attr('readonly', true).parent().parent().parent().hide();
		}
		else{
			$("#shipment_cost").attr('readonly', false).parent().parent().parent().show();
		}
		var shipment_types = JSON.parse('{!!$shipment_types_json!!}');
		for(var i = 0; i<shipment_types.length; i++){
			if(selected_id == shipment_types[i].id){
				$("#shipment_cost").val(shipment_types[i].cost);
				break;
			}
		}
	});

	
	Categories.init();

	//Script for 3D Box 

	$(".change-status").on("click", function(){
		var name = $(this).data('name');
		var action = $(this).data('action');
		var url = $(this).data('url');

		$("#modalRUsure form").attr('action', url);
		$("#modalRUsure .modal-title").html(action + ' producto');
		$("#modalRUsure .modal-body").html('Se '+ action + 'á <b>' + name + '</b><br> ¿Desea continuar?'  );
	});
	$(".duplicate-item").on("click", function(){
		var name = $(this).data('name');
		var action = $(this).data('action');
		var url = $(this).data('url');

		$("#modalRUsure form").attr('action', url);
		$("#modalRUsure .modal-title").html(action + ' producto');
		$("#modalRUsure .modal-body").html('Se '+ action + ' <b>' + name + '</b><br> ¿Desea continuar?'  );
	});

	//GCCUAMEA
	
	$("#priceList").on("change", function(){
		$.ajax({
			method: "POST",
			url: "{{route('admin-get-price-ajax')}}",
			data: {
				'_token': '{!! csrf_token() !!}',
				'price_list_id': $(this).val(),
				'product_id': "{{ $product->id }}"
			}
		}).done(function(data) {
			if(data != null){
				$("#price_ListPrice").val(parseFloat(data).toFixed(2));
			}	
		});
	});
	$("#priceList").trigger("change");

	$("#priceList").on("change", function(){
		$.ajax({
			method: "POST",
			url: "{{route('admin-get-price-ajax')}}",
			data: {
				'_token': '{!! csrf_token() !!}',
				'price_list_id': $(this).val(),
				'product_id': "{{ $product->id }}"
			}
		}).done(function(data) {
			if(data != null){
				$("#price_ListPrice").val(parseFloat(data).toFixed(2));
			}	
		});
	});

	$("#btnSavePriceList").on("click", function(){
		if($("#priceList").val() != "" && parseFloat($("#price_ListPrice").val()) > 0){
			$.ajax({
			method: "POST",
			url: "{{route('admin-update-product-price-list-ajax')}}",
			data: {
				'_token': '{!! csrf_token() !!}',
				'price_list_id': $("#priceList").val(),
				'product_id': "{{ $product->id }}",
				'price': $("#price_ListPrice").val()
			}
			}).done(function() {
				$("#alertPriceList").empty();
				$("#alertPriceList").append('<div class="alert alert-success alert-dismissible fade show" role="alert">Precio Guardado Correctamente.<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
				setTimeout(function(){ $("#alertPriceList").empty(); }, 3000);
				window.history.back();
			}).error(function() {
				$("#alertPriceList").empty();
				$("#alertPriceList").append('<div class="alert alert-danger alert-dismissible fade show" role="alert">Ocurrio un error al guardar Precio.<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
				setTimeout(function(){ $("#alertPriceList").empty(); }, 3000);
			});
		}
	})
});

    
</script>
@stop