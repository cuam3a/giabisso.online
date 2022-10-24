@extends('layouts.admin')
<link rel="stylesheet" href="//cdn.jsdelivr.net/bootstrap.tagsinput/0.4.2/bootstrap-tagsinput.css" />
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
.label-info{
    background-color: #5da814;
    padding:2px;
}


</style>
@stop

@section('content')
	<!-- <div class="m-portlet m-portlet--mobile">
	        @if(count($errors))
	        <div class="alert alert-danger alert-dismissible">
	            <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar"></button>
	            <ul class="list-unstyled">
	            @foreach($errors->all() as $error)
	            <li><strong>{{$error}}</strong></li>@endforeach</ul></div>
	            @endif
	    <div class="m-portlet__head">
	        <div class="m-portlet__head-caption">
	            <div class="m-portlet__head-title">
	                <h3 class="m-portlet__head-text">
	                    DASHBOARD
	                </h3>
	            </div>
	        </div>
	        <div class="m-portlet__head-tools">
	             <ul class="m-portlet__nav">
	                <li class="m-portlet__nav-item">
	                    <a id="btn-save-refunds" class="m-btn m-btn--pill m-btn--hover-brand btn btn-sm btn-secondary">Guardar</a>	
	                </li>
	            </ul> 
	        </div>
	    </div>
	</div> -->

	<div class="row">

		<div class="col-3">
			<div class="m-portlet m-portlet--half-heig4ht m-portlet--border-bottom-success ">
				<div class="m-portlet__body">
					<div class="m-widget26">
						<div class="m-widget26__number">
							{{ $total_ordenes }}
							<small>
								Total de pedidos
							</small>
						</div>
						
					</div>
				</div>
			</div>
		</div>

		<div class="col-3">
			<div class="m-portlet m-portlet--half-heig4ht m-portlet--border-bottom-danger ">
				<div class="m-portlet__body">
					<div class="m-widget26">
						<div class="m-widget26__number">
							{{ $total_ordenes_pendiente }}
							<small>
								Pedidos pendientes
							</small>
						</div>
						
					</div>
				</div>
			</div>
		</div>


		<div class="col-3">
			<div class="m-portlet m-portlet--half-heig4ht m-portlet--border-bottom-brand ">
				<div class="m-portlet__body">
					<div class="m-widget26">
						<div class="m-widget26__number">
							{{ $total_ordenes_pagado }}
							<small>
								Pedidos pagadas
							</small>
						</div>
						
					</div>
				</div>
			</div>
		</div>

		<div class="col-3">
			<div class="m-portlet m-portlet--half-heig4ht m-portlet--border-bottom-brand ">
				<div class="m-portlet__body">
					<div class="m-widget26">
						<div class="m-widget26__number">
							{{ $olvidados }}
							<small>
								Carritos olvidados
							</small>
						</div>
						
					</div>
				</div>
			</div>
		</div>



	</div>

    <div class="m-portlet">
		<div class="m-portlet__body  m-portlet__body--no-padding">
			<div class="row m-row--no-padding m-row--col-separator-xl">
				<div class="col-xl-12">
					<br>
				</div>
				<div class="col-xl-12">
					<div class="container">
						<div id="ordenes_ventas" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
					</div>
				</div>
				<div class="col-xl-12">
					<br>
				</div>
			</div>
		</div>
	</div>


	<div class="m-portlet">
		<div class="m-portlet__body  m-portlet__body--no-padding">
			<div class="row m-row--no-padding m-row--col-separator-xl">
				<div class="col-xl-12">
					<br>
				</div>
				<div class="col-xl-12">
					<div class="container">
						<div id="visitas" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
					</div>
				</div>
				<div class="col-xl-12">
					<br>
				</div>
			</div>
		</div>
	</div>



	<!-- segunda parte -->



	<div class="m-portlet">
		<!-- <div class="m-portlet__head">
			<div class="m-portlet__head-caption">
				<div class="m-portlet__head-title">
					<h3 class="m-portlet__head-text">
						Productos
					</h3>
				</div>
			</div>
		</div> -->
		<div class="m-portlet__body">
				<div class="row">
					<div class="col-4" >
						<h4>Los más vistos</h4>
						<div style="max-height: 400px; overflow-y: scroll;">
							<table class="table table-bordered table-hover">
								<thead>
									<tr>
										<th> vistas </th>
										<th> Producto </th>
									</tr>
								</thead>
								<tbody>
									@foreach ( $vistas as $vista )
										<tr>
											<th scope="row" style="vertical-align: middle;text-align: center;"> 
												<small> <b> {{ $vista->views }} </b> </small> 
											</th>
											<td style="display: flex; flex-wrap: nowrap;"> 
												<div style="order: 1;">
													<img height="80px" width="80px" src="{{ $vista->product->image }}">
												</div>
												<div style="order: 2; padding: 15px;">
													<small> {{ $vista->product->name }} <br> <b> {{ $vista->product->sku }} </b> </small>        
												</div>
											</td>
										</tr>
									@endforeach
								</tbody>
							</table>
						</div>
					</div>
					<div class="col-4" >
						<h4>Los más pedidos</h4>
						<div style="max-height: 400px; overflow-y: scroll;">
							<table class="table table-bordered table-hover">
								<thead>
									<tr>
										<th> Pedidos </th>
										<th> Producto </th>
									</tr>
								</thead>
								<tbody>
									@foreach ( $mas_pedidos as $mas_pedido )
										<tr>
											<th scope="row" style="vertical-align: middle;text-align: center;"> 
												<small> <b> {{ $mas_pedido->total }} </b> </small> 
											</th>
											<td style="display: flex; flex-wrap: nowrap;"> 
												<div style="order: 1;">
													<img height="80px" width="80px" src="{{ $mas_pedido->image }}">
												</div>
												<div style="order: 2; padding: 15px;">
													<small> {{ $mas_pedido->product }} <br> <b> {{ $mas_pedido->sku }} </b> </small>         
												</div>
											</td>
										</tr>
									@endforeach
								</tbody>
							</table>
						</div>
					</div>
					<div class="col-4" >
						<h4>Los más comprados</h4>
						<div style="max-height: 400px; overflow-y: scroll;">
							<table class="table table-bordered table-hover">
								<thead>
									<tr>
										<th> Comprados </th>
										<th> Producto </th>
									</tr>
								</thead>
								<tbody>
									@foreach ( $mas_pedidos_pagados as $mas_pedido )
										<tr>
											<th scope="row" style="vertical-align: middle;text-align: center;"> 
												<small> <b> {{ $mas_pedido->total }} </b> </small> 
											</th>
											<td style="display: flex; flex-wrap: nowrap;"> 
												<div style="order: 1;">
													<img height="80px" width="80px" src="{{ $mas_pedido->image }}">
												</div>
												<div style="order: 2; padding: 15px;">
													<small> {{ $mas_pedido->product }} <br> <b> {{ $mas_pedido->sku }} </b> </small>         
												</div>
											</td>
										</tr>
									@endforeach
								</tbody>
							</table>
						</div>
					</div>
				</div>
			<!--end::Section-->
		</div>
		<!--end::Form-->
	</div>

	<div class="m-portlet">
		<!-- <div class="m-portlet__head">
			<div class="m-portlet__head-caption">
				<div class="m-portlet__head-title">
					<h3 class="m-portlet__head-text">
						Categoría
					</h3>
				</div>
			</div>
		</div> -->
		<div class="m-portlet__body">
				<div class="row">
					<div class="col-12" >
						<h4> Categorías más vendidas </h4>
						<div style="max-height: 450px; overflow-y: scroll;">
							<table class="table table-bordered table-hover">
								<thead>
									<tr>
										<th> $ </th>
										<th> Categoría </th>
									</tr>
								</thead>
								<tbody>
									@foreach ( $categorias as $categoria )
										<tr>
											<th scope="row" style="vertical-align: middle;text-align: center;"> 
												<small> <b> {{ dinero($categoria->total) }} </b> </small> 
											</th>
											<td style="display: flex; flex-wrap: nowrap;"> 
												
												<div style="order: 2; padding: 15px;">
													<small>  @if ( $categoria->category_parent != "_" ) {{ $categoria->category_parent." / " }} @endif  {{ $categoria->category }} </small>         
												</div>
											</td>
										</tr>
									@endforeach
								</tbody>
							</table>
						</div>
					</div>
				</div>
			<!--end::Section-->
		</div>
		<!--end::Form-->
	</div>

@stop
@section('js')
<script type="text/javascript" src="{{ asset('js/sweetalert2.js') }}"></script>

<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>

<script type="text/javascript">


	//const meses = {!! json_encode($graficaOrderVentas["mes"]) !!};
	
	$(document).ready(function(){


		var chart = Highcharts.chart('visitas', {
			credits:{
				enabled : true,
				//href : "http://dpointweb.com/",
				text : "DPointWeb.com"
				//target : "_blank"
			},
		    title: {
		        text: 'Visitas'
		    },

		    subtitle: {
		        text: 'Últimos 6 meses'
		    },

		    xAxis: {
		        categories: {!! json_encode($visits["fecha"]) !!}
		    },
		    yAxis: {
		        title: {
		            text: 'Visitas (#)'
		        }
		    },
		     chart: {
			        events:{
			            load: function() {
			                this.credits.element.onclick = function() {
			                    window.open(
			                      "http://dpointweb.com/",
			                      '_blank'
			                    );
			                 }
			            }
			        }                
			    },

		    series: [{
		        type: 'column',
		        colorByPoint: true,
		        data: [{{ implode(",",$visits["visits"]) }} ],
		        showInLegend: false
		    }]

		});




		Highcharts.chart('ordenes_ventas', {
		    chart: {
		        type: 'line'
		    },
		    credits:{
				enabled : true,
				//href : "http://dpointweb.com/",
				text : "DPointWeb.com"
				//target : "_blank"
			},
		    title: {
		        text: 'Pedidos y ventas'
		    },
		    subtitle: {
		        text: 'Últimos 6 meses'
		    },
		    xAxis: {
		        categories: {!! json_encode($graficaOrderVentas["mes"]) !!}
		        // categories: [{{ implode(",", $graficaOrderVentas["mes"]) }}]
		    },
		    yAxis: {
		        title: {
		            text: 'Montos ($)'
		        }
		    },
		    plotOptions: {
		        line: {
		            dataLabels: {
		                enabled: true
		            },
		            enableMouseTracking: false
		        }
		    },

		     chart: {
			        events:{
			            load: function() {
			                this.credits.element.onclick = function() {
			                    window.open(
			                      "http://dpointweb.com/",
			                      '_blank'
			                    );
			                 }
			            }
			        }                
			    },


		    series: [{
		        name: 'Pedidos',
		        data: [{{ implode(",",$graficaOrderVentas["orders"]) }}]
		    }, {
		        name: 'Ventas',
		        data: [{{ implode(",",$graficaOrderVentas["ventas"]) }}]
		    }]
		});






	});

</script>

@stop