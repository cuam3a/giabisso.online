@extends('layouts.admin')
@section('css')
<style>
	.text-center{
		text-align: center;
	}
	.text-right{
		text-align: center;
	}
	.m-widget17 .m-widget17__stats .m-widget17__items .m-widget17__item{
		margin-top:0!important;
		padding: 5px;
	}
</style>
@stop
@section('content')
<!--Begin::Main Portlet-->
<form class="" action="{{ route('admin-customer-save' )}}" method="POST" enctype="multipart/form-data" >
	{{ csrf_field() }}
	<input hidden value="{{ $customer->id }}" name="customer_id"/>
	<div class="row">
		<div class="col-md-12">
			<div class="m-portlet">
				<div class="m-portlet__head">
					<div class="m-portlet__head-caption">
						<div class="m-portlet__head-title">
							<h3 class="m-portlet__head-text">
								Cliente
							</h3>
						</div>
					</div>
					<div class="m-portlet__head-tools">
						<ul class="m-portlet__nav">
							<li class="m-portlet__nav-item">
								<button type="submit" class="m-btn m-btn--pill m-btn--hover-brand btn btn-sm btn-secondary">Guardar</button>	
							</li>	
						</ul>
					</div>
				</div>
				<div class="m-portlet__body">
					<ul class="nav nav-tabs  m-tabs-line" role="tablist">
						<li class="nav-item m-tabs__item">
							<a class="nav-link m-tabs__link active" data-toggle="tab" href="#m_tabs_6_1" role="tab">
								Información
							</a>
						</li>
						<li class="nav-item m-tabs__item">
							<a class="nav-link m-tabs__link" data-toggle="tab" href="#m_tabs_6_3" role="tab">
								Direcciones de envio
							</a>
						</li>
						<li class="nav-item m-tabs__item">
							<a class="nav-link m-tabs__link" data-toggle="tab" href="#m_tabs_6_4" role="tab">
								Crédito
							</a>
						</li>
					</ul>
					<div class="tab-content">
						<div class="tab-pane active" id="m_tabs_6_1" role="tabpanel">
							<!-- Datos de contacto-->
							<div class="col-md-12 col-lg-4 col-xl-6">
								<div class="m-widget13">
									<div class="m-widget13__item">
										<span class="m-widget13__desc">
											Nombre
										</span>
										<span class="m-widget13__text">
											{{$customer->fullname()}}
										</span>
									</div>
									<div class="m-widget13__item">
										<span class="m-widget13__desc">
											Correo
										</span>
										<span class="m-widget13__text">
											{{$customer->email}}
										</span>
									</div>
									
								</div>
							</div>
							<!--fin Datos de contacto-->
							<!--inicio Datos de facturación-->
							<div class="col-md-12 col-lg-4 col-xl-6">
								<div class="m-widget13">
									<div class="m-widget13__item">
										<span class="m-widget13__desc">
											Celular
										</span>
										<span class="m-widget13__text">
											{{ $customer->cell_phone }}
										</span>
									</div>
									<div class="m-widget13__item">
										<span class="m-widget13__desc">
											Teléfono
										</span>
										<span class="m-widget13__text">
											{{ $customer->phone }}
										</span>
									</div>
								</div>
							</div>
							<!--fin Datos de facturación-->
							<hr/>
							<div class="col-lg-4 m-form__group-sub">
								<label for="category">Lista de Precios</label>
									<select class="form-control m-input" name="price_list_id">
									<option value="0">Ninguna</option>
									@foreach ($priceList as $item)
										<option value="{{ $item->id }}" {{ ( $item->id == $customer->price_list_id) ? 'selected' : '' }}> {{ $item->name }} </option>
									@endforeach    </select>
							</div>
						</div>
						<div class="tab-pane" id="m_tabs_6_3" role="tabpanel">
							<div class="row">
							@foreach($customer->address_book as $address)
									<div class="col-md-4">
										<div class="m-portlet">
											<div class="m-portlet__body">
												<b>{{ $address->fullname()}}</b><br>
												{!!$address->fullAddress()!!}
											</div>
										</div>
									</div>
								@endforeach
							</div>
						</div>
						<div class="tab-pane" id="m_tabs_6_4" role="tabpanel">
							<div class="row">
								<div class="col-md-3">
									<div class="m-portlet">
										<div class="m-portlet__body">
											<div class="col-lg-12 m-form__group-sub">
												<label for="category">Estado</label>
												<select class="col-12 form-control form-control-sm text-right" name="credit_status">
													<option value="0" {{ ( $customer->credit_status == 0) ? 'selected' : '' }}>INACTIVO</option>
													<option value="1" {{ ( $customer->credit_status == 1) ? 'selected' : '' }}>ACTIVO</option>
												</select>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-9"></div>
								<div class="col-md-3">
									<div class="m-portlet">
										<div class="m-portlet__body">
											<div class="col-lg-12 m-form__group-sub">
												<label for="category">Crédito</label>
												<input class="col-12 form-control form-control-sm text-right" type="number" name="credit" value="{{ $customer->credit }}"/>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-3">
									<div class="m-portlet">
										<div class="m-portlet__body">
											<div class="col-lg-12 m-form__group-sub">
												<label for="category">Días de Crédito</label>
												<input class="col-12 form-control form-control-sm text-right" type="number" name="credit_days" value="{{ $customer->credit_days }}"/>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-3">
									<div class="m-portlet">
										<div class="m-portlet__body">
											<div class="col-lg-12 m-form__group-sub">
												<label for="category">Saldo</label>
												<input class="col-12 form-control form-control-sm text-right" type="number" value="{{ $customer->getSaldo() }}" disabled/>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-3">
									<div class="m-portlet">
										<div class="m-portlet__body">
											<div class="col-lg-12 m-form__group-sub">
												<label for="category">Disponible</label>
												<input class="col-12 form-control form-control-sm text-right" type="number" value="{{ $customer->getAvailable() }}" disabled/>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>
<div class="row">
	<div class="col-xl-12">
		<!--begin:: Widgets/Sales States-->
		<div class="m-portlet m-portlet--full-height">
			<div class="m-portlet__head">
				<div class="m-portlet__head-caption">
					<div class="m-portlet__head-title">
						<h3 class="m-portlet__head-text">
							Pedidos
						</h3>
					</div>
				</div>
				<div class="m-portlet__head-tools">
					<ul class="m-portlet__nav">
						<li class="m-portlet__nav-item">
							<a href="#" class="void m-btn m-btn--pill m-btn--hover-brand btn btn-sm btn-secondary exportarClientesPedidos" >Exportar</a> 	{{-- <button  class="m-btn m-btn--pill m-btn--hover-brand btn btn-sm btn-secondary">Exportar</button>--}}
						</li>	
					</ul>
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
								<table class="table m-table m-table--head-no-border">
									<!--begin::Thead-->
									<thead>
										<tr>
											<td class="" style="width:10%!important">
												Folio
											</td><!----><td style="width:10%!important">
												Fecha
											</td><!----><td class="text-center">
												Requiere factura
											</td><!----><td class="text-center">
												Estatus
											</td><!----><td class="text-center">
												Estatus de pago
											</td><!----><td class="text-center">
												Productos
											</td><!----><td class="text-right">
												Total
											</td><!----><td class="text-center" style="width:10%!important">Acciones</td>
										</tr>
									</thead>
									<!--end::Thead-->
									<!--begin::Tbody-->
									<tbody>
										@foreach($customer->orders as $order)
											<tr>
												<td>
													<span class="m--align-center">
														 {{$order->folio()}}
													</span>
												</td>
												<td>
													<span class="m-datatable__cell--center m-datatable__cell">
														 {{$order->date_created()}}
													</span>
												</td>
												<td class="text-center">
													<span>
													{!! $order->getInvoiceBadgeAttribute() !!}
													</span>
												</td>
												<td class="text-center">
													<span>
														 {!!$order->status_badge!!}
													</span>
												</td>
												<td class="text-center">
													<span>
														 {!!$order->payment_badge!!}
													</span>
												</td>
												<td class="text-center">
													<span>
														 {{ $order->order_details->count() }}
													</span>
												</td>
												<td class="text-right">
													<span>
														 {{ $order->total_money}}
													</span>
												</td>
												<td  class="text-center">
													<a href="{{route('admin-order-detail',['order' => $order->id])}}" class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill">
															<i class="la la-search"></i></a>
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
			</div>
		</div>
		<!--end:: Widgets/Sales States-->
	</div>
</div>
<!--End::Main Portlet-->
@stop

@section('js')
<script>
    SuscribeActions = {
    focus: null,
    exportClientPedidos: function () {
        var action = "{{route('admin-customer-export',['customer' => $customer->id])}}";
        $("#modalRUsure form").attr('action', action);
        $("#modalRUsure").modal("hide");
    },
}

$(document).ready(function() {
    $('body').on('click','.exportarClientesPedidos', function(e){	
        modal.launch({
            'title': "Exportar Pedidos",
            'body': "Desea exportar pedidos <b>" + "</b>.<br> ¿Desea continuar?"
        }, SuscribeActions.exportClientPedidos);
    });
});
</script>

@stop	