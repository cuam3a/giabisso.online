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
</style>
@stop
@section('content')
<div class="m-portlet m-portlet--mobile">
    <div class="m-portlet__head">
        <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title">
                <h3 class="m-portlet__head-text">
                    Productos
                    <small>
                        Listado de productos
                    </small>
                </h3>
            </div>
        </div>
        <div class="m-portlet__head-tools">
            <ul class="m-portlet__nav">
                <li class="m-portlet__nav-item">
                    <a href="{{route('admin-product-importer-view')}}" class="m-btn m-btn--pill m-btn--hover-brand btn btn-sm btn-secondary">Importar productos</a>
                </li>		
               <li class="m-portlet__nav-item">
                    <a href="#" class="void m-btn m-btn--pill m-btn--hover-brand btn btn-sm btn-secondary exportProductos">Exportar productos</a>
                </li>						
                <li class="m-portlet__nav-item">
                    <a href="{{ route('admin-product-create') }}"class="m-btn m-btn--pill m-btn--hover-brand btn btn-sm btn-secondary">Crear Producto</a>	
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
                        <div class="col-md-2">
                            <div class="m-form__group m-form__group--inline">
                                <div class="m-form__label">
                                    <label>Categoría:</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="m-form__group m-form__group--inline">
                                <div class="m-form__label">
                                    <label>Subcategoría:</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="m-form__group m-form__group--inline">
                                <div class="m-form__label">
                                    <label>Estatus:</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="m-form__group m-form__group--inline">
                                <div class="m-form__label">
                                    <label>Marca:</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="m-form__group m-form__group--inline">
                                <div class="m-form__label">
                                    <label style="font-weight:700">Activos: <span id="actives" class="m-badge m-badge--success m-badge--wide"></span></label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="m-form__group m-form__group--inline">
                                <div class="m-form__label">
                                    <label style="font-weight:700">Inactivos: <span id="inactives" class="m-badge m-badge--danger m-badge--wide"></span></label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group m-form__group row align-items-center">
                        <div class="col-md-2">
                            <div class="m-form__control">
                                <select class="form-control m-bootstrap-select" id="category">
                                    <option value="0">Todos</option>
                                    @foreach($categories as $id => $name)<option value="{{$id}}">{{$name}}</option>@endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="m-form__control">
                                <select class="form-control m-bootstrap-select" id="subcategory" placeholder="Elige una opción" disabled="disabled">
                                    <option value="0">Todos</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="m-form__control">
                                <select class="form-control m-bootstrap-select" id="status">
                                    <option value="">Todos</option>
                                    @foreach($status as $id => $name)<option value="{{$id}}">{{$name}}</option>@endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="m-form__control">
                                <select class="form-control m-bootstrap-select" id="brand">
                                    <option value="0">Todos</option>
                                    @foreach($brands as $id => $val)<option value="{{$val->id}}">{{$val->text}}</option>@endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group form-control m-input pt-0 pb-0 pl-2 pr-0">
                                <span class="input-group-btn">
                                        <i class="la la-search"></i>
                                </span>
                                <input type="text" id="generalSearch" class="form-control m-input" placeholder="Búsqueda por SKU, nombre, descripción..." style="border:0px">
                                <span class="input-group-btn">
                                    <button class="btn btn-default" id="btn-toggle-product-search" type="button"># Producto</button>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end: Search Form -->
        <!--begin: Datatable -->
        <div class="m_datatable" id="ajax_data">
        </div>
        <!--end: Datatable -->
    </div>
</div>
@stop
@section('js')
<script>
    var Config = [];
    Config['statuses'] = {!! json_encode($status) !!};
    Config['imageType'] = {!! json_encode($imageType) !!};
    Config['imageTypeText'] = {!! json_encode($imageTypeText) !!};
    Config['datatable'] = "{{route('admin-product-list-ajax')}}";
    Config['placeholder'] = '/uploads/products/product-placeholder.jpg';
    Config['edit'] = "{{route('admin-product-edit',['product' => 'id'])}}";//se pone 'id' por mientras, se sustituye luego por numero
    Config['duplicate'] = "{{route('admin-product-duplicate',['product' => 'id'])}}";
    Config['delete'] = "{{route('admin-product-delete',['product' => 'id'])}}";
    Config['status'] = "{{route('admin-product-change-estatus',['product' => 'id'])}}";
    Config['subcategory'] = "{{route('admin-category-get-subcategories')}}";
    Config['brands'] = "{{route('admin-brands-get')}}";
    Config['_token'] = '{!! csrf_token() !!}';
    Config['searchByProductNumber'] = false;
    Config['priceList'] = "{{route('admin-product-price-list-ajax',['id' => 'product_id'])}}";//GCUAMEA
</script>
<script src="/js/datatables/products.js" type="text/javascript"></script>
<script>
ProductActions = {
    focus: null,
    deleteItem: function () {
        var _s = ProductActions;
        var action = Config["delete"].replace("id", _s.focus);
        $("#modalRUsure form").attr('action', action);
    },
    changeStatus: function(){
        var _s = ProductActions;
        var action = Config["status"].replace("id", _s.focus);
        $("#modalRUsure form").attr('action', action);
    },
    duplicateItem: function(){
        var _s = ProductActions;
        var action = Config["duplicate"].replace("id", _s.focus);
        $("#modalRUsure form").attr('action', action);
    }
}

$(document).ready(function() {
    Products.init();
    $('body').on('click','.deleteProduct', function(e){	
        ProductActions.focus = $(this).data('id');
        modal.launch({
            'title': "Eliminar producto",
            'body': "Se eliminará <b>" + $(this).data('name') + "</b>.<br> ¿Desea continuar?"
        }, ProductActions.deleteItem());
        $("#modalRUsure button[type='submit']").attr("data-act", "deleteProduct");
    });

    $('body').on('click','.changeStatus', function(e){
        ProductActions.focus = $(this).data('id');
        var action = $(this).data('action');	
        var title = $(this).attr('title');	
        var name = $(this).data('name');	

        modal.launch({
            'title': ` ${title}`,
            'body': `Se ${action} <b> ${name} </b>.<br> ¿Desea continuar?`
        },ProductActions.changeStatus());
        $("#modalRUsure button[type='submit']").attr("data-act", "changeStatus");

    });
    $('body').on('click','.duplicateItem', function(e){
        ProductActions.focus = $(this).data('id');
        var action = $(this).data('action');	
        var title = $(this).attr('title');	
        var name = $(this).data('name');	
        
        modal.launch({
            'title': ` ${title}`,
            'body': `Se ${action} <b> ${name} </b>.<br> ¿Desea continuar?`
        },ProductActions.duplicateItem());
        $("#modalRUsure button[type='submit']").attr("data-act", "duplicateItem");

    });



});

$(document).ready(function() {
    $('body').on('click','.exportProductos', function(e){	
        modal.launch({
            'title': "Exportar Productos",
            'body': "Desea exportar productos<b>" + "</b>.<br> ¿Desea continuar?",
        });
        $("#modalRUsure button[type='submit']").attr("data-act","exportProductos");
    });
    
    //$("#modalRUsure button[type='submit']").on("click",function(e){
    $("#modalRUsure").on("click", "button[data-act=exportProductos]", function(e){
        e.preventDefault();
        var ruta = "{{route('admin-product-export')}}"
                    +'?estatus=' + $('#status').val()
                    +'&category='+ $('#category').val()
                    +'&subcategory='+ $('#subcategory').val();
        window.open(ruta,"_blank");
        $("#modalRUsure").modal("hide");

    });

    $("body").on("focusout",".inputListPrice",function(){
        debugger
        let id = $(this).data("id");
        let price = $(this).val();
        if(id != null && id != ""){
			$.ajax({
			method: "POST",
			url: "{{route('admin-update-price-product-price-list-ajax')}}",
			data: {
				'_token': '{!! csrf_token() !!}',
				'id': id,
				'price': price
			}
			});
		}   
    })

    $("body").on("click",".checkLiquidado",function(){
        debugger
        let id = $(this).data("id");
        let liquidado = $(this).is(':checked');
        if(id != null && id != ""){
			$.ajax({
			method: "POST",
			url: "{{route('admin-update-liquidacion-status-ajax')}}",
			data: {
				'_token': '{!! csrf_token() !!}',
				'id': id,
				'liquidado': liquidado
			}
			});
		}   
    })

    $("body").on("focusout",".inputLiquidado",function(){
        debugger
        let id = $(this).data("id");
        let liquidado_price = $(this).val();
        if(id != null && id != ""){
			$.ajax({
			method: "POST",
			url: "{{route('admin-update-liquidacion-price-ajax')}}",
			data: {
				'_token': '{!! csrf_token() !!}',
				'id': id,
				'liquidado_price': liquidado_price
			}
			});
		}   
    })
});
</script>
@stop