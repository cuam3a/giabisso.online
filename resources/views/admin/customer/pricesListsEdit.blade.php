@extends('layouts.admin')
@section('css')
@stop
@section('content')
<div class="m-portlet m-portlet--mobile">
    <div class="m-portlet__head">
        <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title">
                <h3 class="m-portlet__head-text">
                    Productos
                    <small>
                        Editar Lista 
                        @if (count($List) > 0)
                            {{ $nameList }}
                        @endif  
                    </small>
                    
                </h3>
            </div>
        </div>
        <div class="m-portlet__head-tools">
            <ul class="m-portlet__nav">
                <li class="m-portlet__nav-item">
                    <a href="#" class="void m-btn m-btn--pill m-btn--hover-brand btn btn-sm btn-secondary guardarLista" >Guardar</a>	
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
                <div class="col-xl-6 order-2 order-xl-1">
                    <div class="form-group m-form__group row align-items-center">
                        <div class="col-md-12 offset-md-12">
                            <div class="m-input-icon m-input-icon--left">
                            <input type="text" class="form-control" id="idList" value="@if (count($List) > 0){{ $id }}@endif" hidden>
                                <input type="text" class="form-control" id="nameList" value="@if (count($List) > 0){{ $nameList }}@endif">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 order-2 order-xl-1">
                    <div class="form-group m-form__group row align-items-center">
                        <div class="col-md-12 offset-md-12">
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
@stop
@section('js')
<script>
    var Config = [];
    Config['id'] = "{{ $id }}";
    Config['datatable'] = "{{route('admin-prices-list-products-ajax',['id' => $id])}}";
    Config['edit'] = "{{route('admin-prices-list-products-edit-ajax',['id'=>'list_id','value'=>'newValue'])}}";
</script>
<script src="/js/datatables/priceListsProducts.js" type="text/javascript"></script>
<script>
    SuscribeActions = {
        focus: null,
        exportClient: function () {
            debugger
            let name = $('#nombreLista').val();
            if(name != ""){
                var url = '{{ route("admin-customer-add-prices-list", ":name") }}';
                url = url.replace(':name', name);
                var action = url;//"{{route('admin-customer-add-prices-list',[ 'name' => ''])}}";
                $("#modalRUsure form").attr('action', action);
                $("#modalRUsure").modal("hide");
            }
        },
    }

    $(document).ready(function() {
        $('body').on('click','.guardarLista', function(e){	
            var table = $(".m_datatable table tbody");//$('#m-datatable__table tbody');
            var nameList = $("#nameList").val();
            var idList = $("#idList").val();
            let trArray = []; 
            trArray = table.children();
            let arrayObj = []
            for(var i=0; i<trArray.length; i++){
                let obj = {};
                obj.id = (trArray[i].children[0].innerText).trimStart().trimEnd();
                obj.price = trArray[i].children[3].children[0].lastElementChild.value;
                arrayObj.push(obj);
            }
            $.get('http://justoatiempo.online/admin/clientes/ajax-priceListProductsEdit?idList='+idList+'&nameList='+nameList+'&data='+JSON.stringify(arrayObj)
            )
            .done(function(data) {
                window.location ='{{ route("admin-prices-list") }}';
            })
            //.fail(function() {
            //    alert( "error" );
            //});
            /*$.ajax({
				type: 'POST',
				url: '{{ route("admin-prices-list-products-edit-ajax") }}',
				data: "{ data:'" + JSON.stringify(arrayObj) + "'}",
				contentType: "application/json",
				dataType: 'json',
				async: true,
				success: function (data) {
                    consolo.log("OK");
				},
				error: function (error) {
					console.log(error);
				}
			});*/
            
            /*modal.launch({
                'title': "Agregar Lista de Precios",
                'body': "Nombre <b>" + "</b><br><input class='form-control form-control-sm' id='nombreLista'/>"
            }, SuscribeActions.exportClient);*/

        });
    });
</script>
@stop