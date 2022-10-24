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
	<div class="m-portlet m-portlet--mobile">
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
	                    Valoraciones de clientes
	                    <small>
	                        
	                    </small>
	                </h3>
	            </div>
	        </div>
	        <div class="m-portlet__head-tools">
	            <!-- <ul class="m-portlet__nav">
	                <li class="m-portlet__nav-item">
	                    <a id="btn-save-refunds" class="m-btn m-btn--pill m-btn--hover-brand btn btn-sm btn-secondary">Guardar</a>	
	                </li>
	            </ul> -->
	        </div>
	    </div>
	</div>

    <div class="m-portlet m-portlet--mobile">
        <div class="m-portlet__body">

			<div class="row">

            	<div class="col-12">
            		<form class=" m-form m-form--fit m-form--label-align-right"> 
                        <div class="form-group m-form__group row">
                            <div class="col-md-8"></div>
                            <div class="col-md-4 m-input-icon m-input-icon--left">
                                <input type="text" class="form-control m-input" placeholder="Buscar..." id="generalSearch">
                                    <span class="m-input-icon__icon m-input-icon__icon--left">
                                        <span>
                                            <i class="la la-search"></i>
                                        </span>
                                    </span>
                            </div>
                        </div>
                    </form> 
            	</div>

			  	<div class="col-12">
	            	
	            	<table class="table table-bordered m-datatable">
						<thead>
							<th> Producto </th>
							<th> Cliente </th>
							<th> Valoración </th>
							<th> Comentario </th>
							<th> Fecha </th>
							<th> Estado </th>
							<th> Ocultar </th>
						</thead>   
						<tbody>
							@foreach ($ratings as $rating)
							<tr>
								<td>  <img src="{{ $rating->producto->image }}" style="height:80px; width: 80px;"> </td>
								<td>  {{ $rating->customer->name.' '.$rating->customer->lastname }} </td>
								<td>  {{ $rating->rating }} </td>
								<td>  {{ $rating->review }} </td>
								<td>  {{ date_format($rating->created_at,'Y-m-d') }} </td>
								<td>  {{ ( $rating->status == 1 ) ? "Visible" : "Invisible" }} </td>
								<td> 
									@if ( $rating->status == 1 )
									<button data-url="{{ route('change-review',[ 'id' => $rating->id, 'status' => 0 ]) }}" class="btn-sm btn btn-danger trash-rating" title="Ocultar comentario" data-title="Ocultar"> <i class="fa fa-close"></i> </button>

									@else
									<button data-url="{{ route('change-review',[ 'id' => $rating->id, 'status' => 1 ]) }}" class="btn-sm btn btn-success trash-rating" title="Visualizar comentario" data-title="Visualizar"> <i class="fa fa-eye"></i> </button>

									@endif
								</td>
							</tr>
							@endforeach
						</tbody>         		
	            	</table>
					
				</div>
			</div>

	
		</div>


            <!--begin: Search Form -->      
    </div>
    
@stop
@section('js')

<script type="text/javascript" src="{{ asset('js/sweetalert2.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/html-table.js') }}"></script>

<script>
    var Config = [];
    $(function() {
       


        $(".trash-rating").on("click",function(){
        	//toastr.info("click");
        	var url = $(this).data("url");
        	var title = $(this).data("title");
        	swal({
                title :  "¿Seguro?",
                text  :  "Si presiona "+title+" realizará esta acción para el comentario",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Cancelar',
                confirmButtonText: title
            }).then((result) => {
                if (result.value) { location.href = url ; }
            });
        });

    });

</script>


@stop