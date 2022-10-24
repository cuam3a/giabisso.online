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
                        Listado de Precios
                    </small>
                </h3>
            </div>
        </div>
        <div class="m-portlet__head-tools">
            <ul class="m-portlet__nav">
                <li class="m-portlet__nav-item">
                    <a href="#" class="void m-btn m-btn--pill m-btn--hover-brand btn btn-sm btn-secondary agregarLista" >Agregar</a>{{--<button class="m-btn m-btn--pill m-btn--hover-brand btn btn-sm btn-secondary" >Exportar clientes</button>--}}	
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
                        <div class="col-md-4 offset-md-8">
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
    Config['datatable'] = "{{route('admin-prices-list-ajax')}}";
    Config['edit'] = "{{route('admin-prices-list-edit',['pricesList' => 'price_list_id'])}}";
    Config['delete'] = "{{route('admin-prices-list-delete',['pricesList' => 'id'])}}";
</script>
<script src="/js/datatables/priceLists.js" type="text/javascript"></script>
<script>
    SuscribeActions = {
        focus: null,
        addPriceList: function () {
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
        $('body').on('click','.agregarLista', function(e){	
            modal.launch({
                'title': "Agregar Lista de Precios",
                'body': "Nombre <b>" + "</b><br><input class='form-control form-control-sm' id='nombreLista'/>"
            }, SuscribeActions.addPriceList);
        });
    });

    ProductActions = {
        focus: null,
        deleteItem: function () {
            var _s = ProductActions;
            var action = Config["delete"].replace("id", _s.focus);
            $("#modalRUsure form").attr('action', action);
        },
        duplicateItem: function(){
            var _s = ProductActions;
            var action = Config["duplicate"].replace("id", _s.focus);
            $("#modalRUsure form").attr('action', action);
        }
    }

    $('body').on('click','.deletePriceList', function(e){	
        ProductActions.focus = $(this).data('id');
        modal.launch({
            'title': "Eliminar Lista de Precios",
            'body': "Se eliminará <b>" + $(this).data('name') + "</b>.<br> ¿Desea continuar?"
        }, ProductActions.deleteItem());
        $("#modalRUsure button[type='submit']").attr("data-act", "deletePriceList");
    });

</script>
@stop