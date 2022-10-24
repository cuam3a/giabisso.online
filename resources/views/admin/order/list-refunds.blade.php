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
                    Devoluciones
                    <small>
                        Listado de devoluciones
                    </small>
                </h3>
            </div>
        </div>
        <div class="m-portlet__head-tools">
            <ul class="m-portlet__nav">
                <li class="m-portlet__nav-item">
                    {{-- <button type="submit" class="m-btn m-btn--pill m-btn--hover-brand btn btn-sm btn-secondary" id="descargarProducto">Exportar pedidos</button>	 --}}
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
                        
                    </div>
                    <div class="form-group m-form__group row align-items-center">
                        <div class="col-md-3">
                            <div class="m-form__control">
                                <select class="form-control m-bootstrap-select" id="category">
                                    <option value="0">Todos</option>
                                    
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                        </div>
                        <div class="col-md-3">
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
        <div>
            <table class="table table-hover m_datatable">
                <thead>
                    <th>Folio</th>
                    <th>Fecha</th>
                    <th style="width: 300px !important">Producto</th>
                    <th>Motivo</th>
                    <th>Comentario</th>
                    <th>Respuesta</th>
                    <th>Estatus</th>
                    <th>Opciones</th>
                </tr>
                <tbody>
                    @foreach($refunds as $refund)
                        <tr>
                            <td>{{$refund->folio()}}</td>
                            <td>{{$refund->created_at}}</td>
                            <td style="width:300px">
                                <div class="m-card-user m-card-user--sm">
                                    {{-- <div class="m-card-user__pic">
                                        <a href="{{route('admin-product-edit',['product' => $refund->products->id])}}">
                                            <img src="{{$refund->products->image}}" class="m--img-rounded m--marginless" alt="photo">
                                        </a>
                                    </div> --}}
                                    <div class="m-card-user__details">
                                        {{$refund->products->product_number}}
                                        <a href="{{route('admin-product-edit',['product' => $refund->products->id])}}">
                                            <span class="m-card-user__name">
                                                @if($refund->status > 0)
                                                <b>{{$refund->quantity}} x </b>
                                                @endif
                                                {{$refund->products->name}}
                                            </span>
                                        </a>
                                        <div class="m-card-user__email m-link">{{$refund->products->sku}}</div>
                                    </div>
                                </div>
                            </td>
                            {{-- <td>{{$refund->products->name}}</td> --}}
                            <td>{{$refund->reasons->description}}</td>
                            <td>{{$refund->comment}}</td>
                            <td>{{$refund->comment_admin}}</td>
                            <td><span class="m-badge m-badge--{{$refund->getRefundStatus($refund->status)['class']}} ">{{$refund->getRefundStatus($refund->status)['text']}}</span></td>
                            <td>
                                @if($refund->status > 0)
                                    <a data-comment-admin="{{$refund->comment_admin}}" data-id="{{$refund->id}}" data-status="{{$refund->status}}" data-target="#modalEditRefund" data-toggle="modal" href="#" title="Editar devolución" class="btn-edit-refund m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"><i class="la la-edit"></i></a>
                                @endif
                                <a href="{{route('admin-order-detail', ['order' => $refund->orders->id])}}" title="Ver pedido" class=" m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"><i class="la la-search"></i></a>

                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!--end: Datatable -->
    </div>
</div>

<div class="modal fade" id="modalEditRefund" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <input type="hidden" name="delete_id" value="" \><!--Utilizado para eliminar categoria -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    Editar devolución
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">
                        &times;
                    </span>
                </button>
            </div>
            <form action="{{route('admin-save-refund')}}" method="post">    
                {{ csrf_field() }}
                <div class="modal-body">        
                    <div class="form-group col-md-12">
                        <label>Estatus:</label>
                        <input type="hidden" name="refund_id" id="refund_id">
                        <select class="form-control" id="slcStatus" name="status">
                            @foreach ($status as $val=>$text)
                                <option value="{{$val}}">{{$text}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-12">
                        <label>Observación:</label>
                        <textarea class="form-control" name="comment_admin" id="comment_admin"></textarea>
                    </div>     
                    
                    <div class="col-md-12 d-none" id="div-alert-cancel">
                        <div class="alert alert-warning">
                            <i class="fa fa-warning"></i> Al cancelar una devolución ya no se podrá volver a editar.
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary cancel btn-no" data-dismiss="modal">
                        Cerrar
                    </button>
                    <button type="submit" class="btn btn-primary btn-yes">
                        Aceptar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@stop
@section('js')
    <script>
        var datatable = $('.m_datatable').mDatatable({
            search: {
                input: $('#generalSearch'),
            },
            autoWidth:false
        })
        var Refunds = {
            handleEvents : function() {
                var _id;
                var _status;
                var _comment_admin;
                $(".btn-edit-refund").on("click",function(){
                    _id = $(this).data('id');
                    _status = $(this).data('status');
                    _comment_admin = $(this).data('comment-admin');

                    $("#slcStatus").val(_status);
                    $("#refund_id").val(_id);
                    $("#comment_admin").val(_comment_admin);
                })

                $("#slcStatus").on("change",function(){
                    if($(this).val() == 0){
                        $("#div-alert-cancel").removeClass('d-none');
                    }
                    else{
                        $("#div-alert-cancel").addClass('d-none');
                    }
                })
            }
        }

        Refunds.handleEvents();
    </script>
@stop