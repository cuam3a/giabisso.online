@extends('layouts.admin')
@section('css')
<style>
	.select2{
		max-width:100%!important;
	}
	.select2-result-repository{
		display: flex;
    	align-items: center;
	}
	.select2-result-repository__avatar {
		float: left;
		width: 60px;
		margin-right: 10px;
	}
	.select2-result-repository__meta {
		margin-left: 20px;
	}
	.select2-result-repository__title {
		color:#575962!important;
		font-weight: 700;
		word-wrap: break-word;
		line-height: 1.1;
		margin-bottom: 4px;
	}
	.select2-result-repository__description {
		font-size: 13px;
		color: #777;
		margin-top: 4px;
	}
</style>
@stop
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="m-portlet m-portlet--mobile">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">
                            {{$carousel->name}}
                        </h3>
                    </div>
                </div>
                <div class="m-portlet__head-tools">
                    <ul class="m-portlet__nav">
						<li class="m-portlet__nav-item">
							<button type="submit" class="m-btn m-btn--pill btn btn-sm btn-primary" id="guardarCarrusel">Guardar</button>	
						</li>
						<li class="m-portlet__nav-item">
							<a href="{{route('admin-carousels-list')}}" class="m-btn m-btn--pill btn btn-sm btn-default">Destacados</a>	
						</li>	
					</ul>
                </div>	
            </div>
        </div>
    </div>
</div>
<div class="row">
 <div class="col-xl-12">
	<div class="m-portlet m-portlet--full-height ">	
		<form class="m-form m-form--fit m-form--label-align-right">		
			<div class="m-portlet__body">
				<div class="form-group m-form__group">
					<h5 class="m-form__heading-title">Producto</h5>
					<select type="text" class="form-control m-input products" name="product"></select>
				</div>
			</div>
			<div class="m-form__seperator m-form__seperator--dashed"></div>
		</form>
		
		<div class="m-portlet__body">
			<div class="products-list m-widget4">	 
			</div>
		</div>
	</div>
  </div>
</div>
@stop
@section('js')
<script src="/js/select2/js/i18n/es.js" type="text/javascript"></script>
<script>
$( document ).ready(function() {
	$('#guardarCarrusel').click(function(){
		var url = "{{route('admin-carousel-save-products')}}";
		var items = JSON.stringify(Products.items);
		var form = $('<form>').attr('action', url).attr('method', 'post')
							.append($('<input>').attr('type', 'hidden').attr('name', '_token').attr('value','{{ csrf_token() }}'))
							.append($('<input>').attr('type', 'hidden').attr('name', 'carousel').attr('value','{{$carousel->id}}'))
							.append($('<input>').attr('type', 'hidden').attr('name', 'products').attr('value',items));
		$('body').append(form);
		$(form).submit(); 
	});
	var Products = {
		items: [],
		focus:null,
		htmlList: $('.products-list'),
		init: function(){
			var _s = this;
			var saved = {!! json_encode($carousel->products()) !!};
			$.each( saved, function( key, value ) {
				_s.addProduct(value);
			});
		},
		addProduct:function(product){ 
			var _s = this;
			_s.items.push(product.id);
			var element = '<div class="m-widget4__item" data-id="'+product.id+'" data-name="'+product.name+'">'+
								'<div class="m-widget4__img m-widget4__img--pic">'+						 
									'<img src="'+product.image+'" style="width: 4rem;border-radius: 50%;" onerror="imgError(this);">'+    
								'</div>'+
								'<div class="m-widget4__info">'+
									'<span class="m-widget4__title">'+product.name+'</span><br>'+
									'<span class="m-widget4__sub">'+product.sku+'</span>'+							 		 
								'</div>'+
								'<div class="m-widget4__ext">'+
									'<a href="#" data-portlet-tool="remove" class="m-portlet__nav-link btn m-btn m-btn--icon m-btn--icon-only m-btn--pill btn-danger void deleteProd" data-type="carousel-product" ><i class="la la-trash"></i></a>'+
								'</div>'+
							'</div>';	
			_s.htmlList.append(element);					
			_s.actionsEvents($(_s.htmlList).find('.m-widget4__item').last());//ultimo html agregado	
		},
		actionsEvents: function($element) {
			var _s = this;
			$element.unbind();
			$element.bind('click', function(e){	
				_s.focus = $element.data('id');
				modal.launch({'title': "Eliminar producto de carrusel",
							  'body':  "Se eliminará producto de carrusel <b>" + $(this).data('name') + "</b>.<br> ¿Desea continuar?"},_s.deleteProduct);
			});				
		},
		deleteProduct: function(){
			modal.kill();
			var _s = Products;//objeto local	
			_s.items.pop(_s.focus);
			$(_s.htmlList).find("[data-id="+_s.focus+"]").remove();//accion de borrar html de producto
			_s.focus = null;
		}		
	}//Products

	$(".products").select2({
		language: "es",
		placeholder: "Agrega un producto",
		ajax: {
			url: '{{route("admin-get-products-carousel")}}',
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

		templateResult: formatRepo,
		templateSelection: function(data){
			if(data.text!='') return data.text;
			return data.name;
		},
		escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
		minimumInputLength: 1,
		
	}).on('select2:select', function(e){
		var data = e.params.data;
		Products.addProduct(data);
		setTimeout(function () {
			toastr.success(
				"Se añadio <b>" +data.name +".</b>"
			);
		}, 100);
		$(this).val(null).trigger('change');
	});
	Products.init();
	
	function formatRepo (repo) {
		if (repo.loading) {
			return repo.text;
		}

		var markup = "<div class='select2-result-repository clearfix' data-info="+repo+">" +
			"<div class='select2-result-repository__avatar'><img src='" + repo.image+ "' style='width: 65px;border-radius: 50px;' onerror='imgError(this);'/></div>" +
			"<div class='select2-result-repository__meta'>" +
				"<div class='select2-result-repository__title'>" + repo.name + "</div>"+
				"<div class='select2-result-repository__description'>" + repo.sku + "</div></div>";
		return markup;
	}
});
</script>
@stop