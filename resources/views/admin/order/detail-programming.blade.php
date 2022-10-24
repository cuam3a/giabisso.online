@extends('layouts.admin')
@section('style')
<style>
	td.m-widget11__app{
		width:8%!important;
	}
	.m-widget11__total{
		width:3%!important;
	}
	td.m-widget11__price{
		width:5%!important;
	}
</style>
@stop

@section('content')
<!--Begin::Main Portlet-->
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
							Pedido {{$order->folio()}}
						</h3>
					</div>
				</div>
				<div class="m-portlet__head-tools">
					<ul class="m-portlet__nav">
						<li class="m-portlet__nav-item">
							<h5>{{ $order->date_created()}}</h5>
						</li>
						<li class="m-portlet__nav-item">
							{{-- <button type="submit" class="m-btn m-btn--pill m-btn--hover-brand btn btn-sm btn-secondary" id="descargarProducto">Exportar</button>	 --}}
						</li>	
					</ul>
				</div>	
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="m-portlet m-portlet--tab">
			<div class="m-portlet__head">
			<div class="m-portlet__head-caption">
					<div class="m-portlet__head-title">
						<h6 class="m-portlet__head-text" style="width:40%;line-height:1">
							Estatus: 
						</h6>
						<div class="mt-4 ml-3">{!! $order->getStatusBadgeAttribute() !!}</div>
					</div>
				</div>
				<div class="m-portlet__head-tools">
					<ul class="m-portlet__nav">
						<li class="m-portlet__nav-item">
							<!--<div class="row">
								<div class="col-12">
									<a href="{{route('admin-order-export-pdf',["id"=> $order->id])}}" target="_blank" class="m-btn m-btn--pill m-btn--hover-brand btn btn-sm btn-secondary exportarClientes">Exportar</a>
								</div>
							</div>-->
						</li>
						<li class="m-portlet__nav-item">						
						<div class="row">
								<div class="col-12">
									<button class="m-btn m-btn--pill m-btn--hover-brand btn btn-sm btn-secondary btnActualizar">Actualizar</button>
								</div>
							</div>
						</li>
						<li class="m-portlet__nav-item">
							<div class="row">
								<div class="col-12">
									<button class="m-btn m-btn--pill m-btn--hover-brand btn btn-sm btn-secondary btnCancelar" data-type="cancel">Cancelar</button>
								</div>
							</div>
						</li>	
					</ul>
				</div>	
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="m-portlet m-portlet--tab">
			<div class="m-portlet__body ">
				<div class="row">
				<div class="col-12 col-sm-12 col-md-12"> 
                    <table class="table table-responsive col-md-12">
                        <tr class="text-center">
                            <th class="text-center">PRODUCTOS</th>
                            <!--<th>PRECIO</th>-->
                            <th class="text-center">CANTIDAD</th>
                            <th class="text-center">TOTAL</th>
                            <th class="text-center">FECHA ENTREGA</th>
							<th class="text-center">ESTADO</th>
                        </tr>
                        <form action="{{route('update-product-programming-admin')}}" method="POST" class="frmCart">
                            @foreach($order_items as $orderItem)
                                {{ csrf_field() }}
                                <tr>
                                    <td>{{ $orderItem->name }}</td>
                                    <!--<td class="text-right">{{ dinero($orderItem->unit_price) }}</td>-->
                                    <td class="text-center">{{$orderItem->quantity}}</td>
                                    <td class="text-right">{{dinero($orderItem->unit_price*$orderItem->quantity)}}</td>
                                    <td class="text-center">{{$orderItem->date_send}}</td>
									<td>
										<select class="form-control m-input m-input--square" id="sltStatus" data-type="estatus" data-statusnum="{{$order->status}}" name="status-{{$orderItem->id}}">
											@foreach($statuses as $id => $text)
												<option value="{{$id}}" @if($orderItem->status == $id) selected @endif>{{$text}}</option>
											@endforeach
										</select>
									</td>
                                </tr>
                            @endforeach
                        <!--<button type="submit" class="mt-4 btn btn-primary float-right">Actualizar pedido</button>-->
                        </form>
                    </table>
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
	var Config = [];
	Config['pago'] = "{{route('admin-order-change-payment-status-order',['order' => $order->id])}}";
	Config['cancel'] = "{{route('admin-order-cancel-order',['order' => $order->id])}}";
	$.validator.messages.required = 'Campo requerido';
	$("#frmTracking").validate();
	
	$('.btnCancelar').on("click",function () {
		if($(this).data('statusnum') == $(this).val()) return false;
		$('#modalRUsure .modal-title').html('Cancelar Pedido Programado');
		$('#modalRUsure .modal-body').html(`Se cambiará estatus a Cancelado </b>.<br> Se notificará  por correo electrónico a cliente.<br>
											¿Desea continuar?`);
		$('#modalRUsure form').data('type', $(this).data('type'));//si se cancela, se pone status guardado
		$('#modalRUsure form').data('cancel', $(this).data('statusnum'));//si se cancela, se pone status guardado
		$('#modalRUsure form').attr('action', Config[$(this).data('type')]);//si da aceptar, se hace submit con val elegido
		$('#modalRUsure').modal();
	});

	$(".btnActualizar").on("click", () => {
        $(".frmCart").submit();
    })
});//document ready
</script>
@stop	