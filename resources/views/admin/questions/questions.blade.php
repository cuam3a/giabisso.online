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
	                    Preguntas y respuestas
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

        	<ul class="nav nav-tabs" role="tablist">
				<li class="nav-item">
					<a class="nav-link active" data-toggle="tab" href="#pending">
						Pendientes
					</a>
				</li>
				
				<li class="nav-item">
					<a class="nav-link" data-toggle="tab" href="#all">
						Resueltas
					</a>
				</li>
				
			</ul>

			<div class="tab-content">
				<div class="tab-pane active" id="pending" role="tabpanel">
					<!-- inicio de tabla -->
					<div class="row">
					  <div class="col-12">
			            	<div class="table-responsive">
				            	<table class="table table-bordered">
									<thead>
										<th>  </th>
										<th> Producto </th>
										<th> De </th>
										<th> Pregunta </th>
										<th> Fecha </th>
										<th> Opciones </th>
									</thead>   
									<tbody>
										@foreach ($questions as $question)
										<tr>
											<td> 
												<img src="{{ $question->producto->image }}" style="height:80px; width: 80px;">
											</td>
											<td> 
												{{ $question->producto->name }} 
											</td>
											<td> 
												{{ $question->name }} <br>
												<small> {{ $question->email }} </small>
											</td>
											<td> {{ $question->question }} </td>
											<td> 
												<small> {{ date_format($question->created_at,'Y-m-d') }} </small>
											</td> 
											<td> 
												<!-- <div class="btn-group"> -->
													<button data-id="{{ $question->id }}" data-qst="{{ $question->question }}" class="btn-sm btn btn-info reply-question" title="Responder"> <i class="fa fa-reply"></i> </button>

													<button data-url="{{ route('trash-question',$question->id) }}" class="btn-sm btn btn-danger trash-question" title="Eliminar"> <i class="fa fa-trash"></i> </button>
												<!-- </div> -->
											</td>
										</tr>
										@endforeach
									</tbody>         		
				            	</table>
							</div>
			            </div>
			        </div>
			            <!-- fin de la tabla -->
				</div>
				<div class="tab-pane" id="all" role="tabpanel">
					<!-- inicio de tabla -->
					  
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
										<th> De </th>
										
										<th> Pregunta </th>
										<th> Respuesta </th>
										<th> Fecha </th>
										<th> Opciones </th>
									</thead>   
									<tbody>
										@foreach ($questions_all as $question)
										<tr>
											<td> 
												<img src="{{ $question->producto->image }}" style="height:80px; width: 80px;">
											</td>
											<td> 
												{{ $question->name }} <br>
												{{ $question->email }} 
											</td>
											<td> {{ $question->question }} </td>
											<td> {{ $question->answer }} </td>
											<td> {{ date_format($question->created_at,'Y-m-d') }} </td>
											<td> 
												<!-- <div class="btn-group"> -->
													<button data-id="{{ $question->id }}" data-qst="{{ $question->question }}" data-anw="{{ $question->answer }}" class="btn-sm btn btn-info edit-response" title="Editar respuesta"> <i class="fa fa-edit"></i> </button>

													<button data-url="{{ route('trash-question',$question->id) }}" class="btn-sm btn btn-danger trash-question" title="Eliminar"> <i class="fa fa-trash"></i> </button>
												<!-- </div> -->
											</td>
										</tr>
										@endforeach
									</tbody>         		
				            	</table>
								
							</div>
						</div>
			            
			            <!-- fin de la tabla -->

				</div>
			</div>
		</div>


            <!--begin: Search Form -->      
    </div>
    


    <!-- Modal -->
	<div id="answer-modal" class="modal fade" role="dialog">
	 	<div class="modal-dialog">

	    <!-- Modal content-->
	    	<div class="modal-content">
		      	<div class="modal-header">
		        	<h4 class="modal-title"> Respuesta </h4>
		      	</div>
		      <div class="modal-body">
		        
		        <div class="row">
		        	<div class="col-12">

		        		 <form id="form" action="{{ route('save-answers') }}" method="post">
		                    {{ csrf_field() }}
		                   	<input type="hidden" name="id" id="id" value="0">
		                   	<input type="hidden" name="edition" id="edition" value="0">
		                    <div class="form-group">
		                    	<b> <label id="label_question"></label></b>
		                        <textarea class="form-control" name="answer" id="answer" rows="5" placeholder="Respuesta" required></textarea>
		                    </div>
		                </form>
		        		
		        	</div>
		        </div>

		      </div>
		      
		      <div class="modal-footer">
		      	<button class="btn btn-primary" id="btn-answer"> <i class="fa fa-reply"></i> Responder </button>
		        <button type="button" class="btn btn-default" data-dismiss="modal"> <i class="fa fa-close"></i> Cancelar </button>
		      </div>
	    </div>

	  </div>
	</div>


@stop
@section('js')

<script type="text/javascript" src="{{ asset('js/sweetalert2.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/html-table.js') }}"></script>

<script src="/metronic_v5.0.5/theme/default/dist/default/assets/demo/default/custom/components/forms/widgets/summernote.js" type="text/javascript"></script>
<script type="text/javascript" src="{{ asset('/js/summernote-es-ES.js') }}"></script>
<script>
    var Config = [];
    $(function() {
        $("#btn-save-refunds").on("click",function(){
            $(".frmRefunds").submit();
        });

        $(".reply-question").on("click",function(){
        	var id = $(this).data("id");
        	var qst = $(this).data("qst");
        	$("#label_question").html(qst);
        	$("#id").val(id);
        	$("#edition").val(0);
        	$("#answer").val("");
        	$("#answer-modal").modal("show");
        });

        $(".edit-response").on("click",function(){
        	var id = $(this).data("id");
        	var qst = $(this).data("qst");
        	var anw = $(this).data("anw");
        	$("#label_question").html(qst);
        	$("#id").val(id);
        	$("#edition").val(1);
        	$("#answer").val(anw);
        	$("#answer-modal").modal("show");
        });

        $("#btn-answer").on("click",function(){
        	( $("#answer").val() != "" ) ? $("#form").submit() : toastr.info("Es necesario que se llene el campo de respuesta para poder continuar");
        });

        $(".trash-question").on("click",function(){
        	//toastr.info("click");
        	var url = $(this).data("url");
        	swal({
                title :  "¿Seguro de eliminar?",
                text  :  "Si presiona eliminar, la pregunta se eliminará",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Eliminar'
            }).then((result) => {
                if (result.value) { location.href = url ; }
            });
        });

        $('.summernote-es').summernote({
            // height: 300,                 // set editor height
            minHeight: 200,             // set minimum height of editor
            maxHeight: null,             // set maximum height of editor
            focus: true,                  // set focus to editable area after initializing summernote
            lang:'es-ES',
            // airMode: true

        });
    });

</script>


@stop