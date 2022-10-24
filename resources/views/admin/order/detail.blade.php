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
						<h3 class="m-portlet__head-text" style="width:40%;line-height:1">
							Número de guía
						</h3>
						<div class="m-portlet__head-text  m--align-right" style="vertical-align:bottom;padding-bottom:10px;line-height: 1.0!important;">
							@if($order->delivery_service != 0)
								<div class="col-md-12">
									<div class="col-md-12">
										<span>{{ $order->delivery_text }}</span>
									</div>
									<div class="col-md-12">
										<a href="#" data-toggle="modal" class="change-delivery-service" data-target="#modalTracking" style="font-size:12px"> CAMBIAR </a>
									</div>
								</div>
							@else 
								<a href="#" data-toggle="modal" data-target="#modalTracking" class="btn btn-success change-delivery-service" style="font-size:12px"> ASIGNAR </a>
							@endif
						</div>
					</div>
				</div>
				<div class="m-portlet__head-tools">
					<ul class="m-portlet__nav">
						<li class="m-portlet__nav-item">
							<div class="row">
								<div class="col-12">
									<a href="{{ route('admin-messages-order',$order->id) }}" class="m-btn m-btn--pill m-btn--hover-brand btn btn-sm btn-secondary "> Ver mensaje </a>

									<a href="{{route('admin-order-export-pdf',["id"=> $order->id])}}" target="_blank" class="m-btn m-btn--pill m-btn--hover-brand btn btn-sm btn-secondary exportarClientes">Exportar</a>
								</div>
							</div>
						</li>
						<li class="m-portlet__nav-item">						
							<form class="m-form m-form--fit m-form--label-align-right">
								<div class="row">
									<label for="example-text-input" class="col-4 col-form-label">Estatus</label>
									<div class="col-8">
										<select class="form-control m-input m-input--square" id="sltStatus" data-type="estatus" data-statusnum="{{$order->status}}">
											@foreach($statuses as $id => $text)
												<option value="{{$id}}" @if($order->status == $id) selected @endif>{{$text}}</option>
											@endforeach
										</select>
									</div>
								</div>						
							</form>
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
					<!-- Datos de contacto-->
					<div class="col-md-12 col-lg-4 col-xl-4">
						<div class="m-widget13">
							<div class="m-widget13__header m--margin-bottom-30">
								<h5 class="m-widget13__title">
									Datos de envio
								</h5>
							</div>
							<div class="m-widget13__item">
								<span class="m-widget13__text">
									{{$order->fullname()}}
								</span>
							</div>
							<div class="m-widget13__item">
								<span class="m-widget13__text">
									{{$order->email}}
								</span>
							</div>
							<div class="m-widget13__item">
								<span class="m-widget13__text ">
									Celular: {{$order->cell_phone }}
								</span>
							</div>
							<div class="m-widget13__item">
								<span class="m-widget13__text ">
									Teléfono: {{$order->phone }}
								</span>
							</div>
							<div class="m-widget13__item">
								<span class="m-widget13__text ">
									{!!$order->fullAddress()!!}
								</span>
							</div>
							<div class="m-widget13__item">
								<span class="m-widget13__text ">
									{{$order->instructions_place}}
								</span>
							</div>
						</div>
					</div>
					<!--fin Datos de contacto-->
					<!--inicio Datos de metodo de pago-->
					<div class="col-md-12 col-lg-4 col-xl-4">
						<div class="m-widget13">
							<div class="m-widget13__header m--margin-bottom-30">
								<h5 class="m-widget13__title">
									Pago
								</h5>
							</div>
							<div class="m-widget13__item">
								<span class="m-widget13__desc">
									Total:
								</span>
								<span class="m-widget13__text">
									{{ dinero($order->total) }}
								</span>
							</div>
							<div class="m-widget13__item">
								<span class="m-widget13__desc">
									Estatus de pago
								</span>
								<form>
									<select class="form-control m-input m-input--square" id="sltPago" data-type="pago" data-statusnum="{{$order->payment_status}}">
										@foreach($payment as $id => $text)
											<option value="{{$id}}" @if($order->payment_status == $id) selected @endif>{{$text}}</option>
										@endforeach
									</select>
								</form>
							</div>
							<div class="m-widget13__item">
								<span class="m-widget13__desc">
									Fecha de pago:
								</span>
								<span class="m-widget13__text">
									{{ $order->date_payment() }}
								</span>
							</div>
							@if($payment_type_text)
							<div class="m-widget13__item">
								<span class="m-widget13__desc">
									Método de pago:
								</span>
								<span class="m-widget13__text">
									{{ $payment_type_text }}
								</span>
							</div>
							@endif
							<div class="m-widget13__item">
								<span class="m-widget13__desc">
									Método de envío:
								</span>
								<span class="m-widget13__text">
									{{ $order->order_shipment_type->text or 'Sin asignar'}}
								</span>
							</div>

						</div>
					</div>
					<!--fin Datos de metodo de pago-->
					<!--inicio Datos de facturación-->
					<div class="col-md-12 col-lg-4 col-xl-4">
						<div class="m-widget13">
							<div class="m-widget13__header m--margin-bottom-30">
								<h5 class="m-widget13__title">
									Facturación
								</h5>
							</div>
							<div class="m-widget13__item">
								<span class="m-widget13__desc">
									Requiere factura
								</span>
								<span class="m-widget13__text">
									{{ $order->invoiceRequired }}
								</span>
							</div>
							<div class="m-widget13__item">
								<span class="m-widget13__desc">
									Razon social
								</span>
								<span class="m-widget13__text">
									{{$order->f_name}}
								</span>
							</div>
							<div class="m-widget13__item">
								<span class="m-widget13__desc">
									R.F.C.:
								</span>
								<span class="m-widget13__text">
									{{$order->f_rfc}}
								</span>
							</div>
							<div class="m-widget13__item">
								<span class="m-widget13__desc">
									Correo electrónico:
								</span>
								<span class="m-widget13__text">
									{{$order->f_email}}
								</span>
							</div>
							<div class="m-widget13__item">
								<span class="m-widget13__desc">
									Dirección:
								</span>
								<span class="m-widget13__text">
									{!!$order->fullAddressInvoice()!!}
								</span>
							</div>
							<div class="m-widget13__item">
								<span class="m-widget13__desc">
									Teléfono:
								</span>
								<span class="m-widget13__text">
									{{ $order->f_phone }}
								</span>
							</div>
						</div>
					</div>
					<!--fin Datos de facturación-->
				</div>
			</div>
		</div>
	</div>
</div>
<!--Begin::Main Portlet-->
@if($order->comments)
	<div class="row">
		<div class="col-xl-12">
			<!--begin:: Widgets/Sales States-->
			<div class="m-portlet m-portlet--full-height">
				<div class="m-portlet__body">
					<div class="form-group m-form__group row">
						<label class="col-lg-2 col-form-label">
							Comentarios:
						</label>
						<div class="col-lg-10">
							<p class="txtComentarios">
								{{ $order->comments }}
							</p>
						</div>
					</div>
				</div>
			</div>
		</div><!--end:: Widgets/Sales States-->
	</div>
@endif
<!--End::Main Portlet-->
<!--Begin::Main Portlet-->
<div class="row">
	<div class="col-xl-12">
		<!--begin:: Widgets/Sales States-->
		<div class="m-portlet m-portlet--full-height">
			<div class="m-portlet__head">
				<div class="m-portlet__head-caption">
					<div class="m-portlet__head-title">
						<h3 class="m-portlet__head-text">
							Detalle de pedido {{$order->folio()}}
						</h3>
					</div>
				</div>
			</div>
			<div class="m-portlet__body">
				<!--Begin::Tab Content-->
				<div class="tab-content">
					<!--begin::tab 1 content-->
					<div class="tab-pane active" id="m_widget11_tab1_content">
						<!--begin::Widget 11-->
						<div class="m-widget11">
							<div class="table-responsive">
								<!--begin::Table-->
								<table class="table">
									<!--begin::Thead-->
									<thead>
										<tr>
											<td class="m-widget11__price m--align-left">
												Cantidad
											</td><!----><td class="m-widget11__app">
												Nombre
											</td><!----><td class="m-widget11__price m--align-center">
												Precio unitario
											</td><!----><td class="m-widget11__total m--align-right">
												Importe
											</td>
										</tr>
									</thead>
									<!--end::Thead-->
									<!--begin::Tbody-->
									<tbody>
										@foreach($order->order_details as $detail)
											<tr>
												<td>
													<span style="text-align:center">
														{{ $detail->quantity }}
													</span>
												</td>
												<td>
													<span class="">
														<small><b>{{$detail->sku}}</b></small> {{$detail->name}}
													</span>
												</td>
												<td class="m--align-center">
													<span class="">
														{{dinero($detail->unit_price)}}
													</span>
												</td>
												<td class="m--align-right">
													<span class="">
													{{ dinero($detail->amount) }}</span> 
												</td>
											</tr>
										@endforeach
									</tbody>
									<!--end::Tbody-->
								</table>
								<hr>
								<!--end::Table-->
							</div>
						</div>
						<!--end::Widget 11-->
					</div>
					<!--end::tab 1 content-->
				</div>
				<!--End::Tab Content-->
				<div class="col-xl-4" style="float: right;padding-bottom: 50px;">
						<div class="m-widget13">
							<div class="m-widget13__item">
								<span class="m-widget13__desc m--align-right">
									Subtotal:
								</span>
								<span class="m-widget13__text m--align-right">
									{{ dinero($order->subtotal)}}
								</span>
							</div>
							<div class="m-widget13__item">
								<span class="m-widget13__desc m--align-right">
									Envio:
								</span>
								<span class="m-widget13__text m--align-right">
									{{ dinero($order->shipping)}}
								</span>
							</div>
							<div class="m-widget13__item">
								<span class="m-widget13__desc m--align-right">
									Total:
								</span>
								<span class="m-widget13__text m--align-right m-widget13__text-bolder">
									{{ dinero($order->total) }}
								</span>
							</div>
						</div>
					</div><!--xl-4-->
			</div>
		</div>
		<!--end:: Widgets/Sales States-->
	</div>
</div>
<!--End::Main Portlet-->
@include('admin.order.modalTracking')
@stop

@section('js')
<script type="text/javascript">
$(document).ready(function(){
	var Config = [];
	Config['pago'] = "{{route('admin-order-change-payment-status-order',['order' => $order->id])}}";
	Config['estatus'] = "{{route('admin-order-change-status-order',['order' => $order->id,'statusnum' => 'newstatus'])}}";
	$.validator.messages.required = 'Campo requerido';
	$("#frmTracking").validate();
	$('#sltPago,#sltStatus').change(function () {
		if($(this).data('statusnum') == $(this).val()) return false;
		$('#modalRUsure .modal-title').html('Cambiar ' + $(this).data('type')+' a ' + $(this).find('option:selected').text());
		$('#modalRUsure .modal-body').html(`Se cambiará estatus a <b>`+$(this).find('option:selected').text()+
											`</b>.<br> Se notificará  por correo electrónico a cliente.<br>
											¿Desea continuar?`);
		$('#modalRUsure form').data('type', $(this).data('type'));//si se cancela, se pone status guardado
		$('#modalRUsure form').data('cancel', $(this).data('statusnum'));//si se cancela, se pone status guardado
		$('#modalRUsure form').attr('action', Config[$(this).data('type')].replace("newstatus", $(this).val()));//si da aceptar, se hace submit con val elegido
		$('#modalRUsure').modal();
	});

	$('#modalRUsure').on('hidden.bs.modal', function () {
		switch ($(this).find('form').data('type')) {
			case 'pago':
				$('#sltPago').val($(this).find('form').data('cancel'));
				break;
			case 'estatus':
				$('#sltStatus').val($(this).find('form').data('cancel'));
				break;
			default:
				// execute default code block
		}
	});

	$(".change-delivery-service").on("click",function(){
		var delivery_service = "{{$order->delivery_service}}";
		var tracking_number = "{{$order->tracking_number}}";
		$("#delivery_service").val(delivery_service).trigger('change');
		$("#tracking_number").val(tracking_number)
	})

	$("#delivery_service").on("change",function(){
		var value = $(this).val();
		if(value == 0){
			$("#tracking_number").parent().hide();
		}
		else{
			$("#tracking_number").parent().show();
		}
	})

});//document ready
</script>
@stop	