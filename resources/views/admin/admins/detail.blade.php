@extends('layouts.admin')
@section('content')
<div class="m-portlet m-portlet--mobile">
    <div class="m-portlet__head">
        <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title">
                <h3 class="m-portlet__head-text">
                    @if(!isset($admin->id)) Crear usuario @else Editar usuario @endif
                </h3>
            </div>
        </div>
        <div class="m-portlet__head-tools">
            <ul class="m-portlet__nav">
                <li class="m-portlet__nav-item">
                    <a href="#" id="btnSave" class="m-btn m-btn--pill m-btn--hover-brand btn btn-sm btn-secondary void">Guardar</a>
                </li>	
            </ul>
        </div>	
    </div>
</div>

<!--begin::Form-->
<form class="m-form m-form--fit m-form--label-align-right" id="frmAdmin" action="{{ route("admin-save",['admin' => $admin->id]) }}">
	<div class="row">
		<div class="col-md-6">
			<!--begin::Portlet-->
			<div class="m-portlet">
				<div class="m-portlet__head">
					<div class="m-portlet__head-caption">
						<div class="m-portlet__head-title">
							<span class="m-portlet__head-icon m--hide">
							<i class="la la-gear"></i>
							</span>
							<h3 class="m-portlet__head-text">
								Información
							</h3>
						</div>
					</div>
				</div>
					<div class="m-portlet__body">
						{{ csrf_field() }}
						<input type="hidden" name="admin" value="{{$admin->id}}">
						<div class="form-group">
							<label for="name">Nombre</label>
							<input type="text" name="name" class="form-control m-input m-input--square" value="{{$admin->name}}">
						</div>
						<div class="form-group">
							<label for="email">Correo electrónico</label>
							<input type="email"  name="email" class="form-control m-input m-input--square" aria-describedby="emailHelp" value="{{$admin->email}}">
						</div>
						<div class="form-group">
							<label for="password">Contraseña</label>
							<input type="password"  name="password" id="password" class="form-control m-input m-input--square">
						</div>
						<div class="form-group">
							<label for="repeat_password">Confirmar contraseña</label>
							<input type="password" name="repeat_password" class="form-control m-input m-input--square" >
						</div>
					</div>		
			</div>
			<!--end::Portlet-->
		</div>
		<div class="col-md-6">
			<!--begin::Portlet-->
			<div class="m-portlet">
				<div class="m-portlet__head">
					<div class="m-portlet__head-caption">
						<div class="m-portlet__head-title">
							<span class="m-portlet__head-icon m--hide">
							<i class="la la-gear"></i>
							</span>
							<h3 class="m-portlet__head-text">
								Permisos
							</h3>
						</div>
					</div>
				</div>
					<div class="m-portlet__body">
						<div class="form-group">
							@foreach($modules as $key => $module)
							<div class="m-checkbox-list">
								<label class="m-checkbox m-checkbox--state-success">
								<input type="checkbox" style="font-weight:bold" @if(in_array($module->id, $permissions)) checked="checked" @endif name="module_{{$module->id}}"> {{$module->name}}
								<span></span>
								</label>
							</div>
							@endforeach
						</div>
					</div>
			</div>
			<!--end::Portlet-->
		</div>
	</div>
</form>
@stop	
@section('js')
<script>
	$("#frmAdmin").validate({
		rules: {
			name:  {
				required: true,
				maxlength:100
			},
			email: {
				required: true,
				email: true,
				remote: {
					url: "{{route('admin-email-exist')}}",
					type: "POST",
					data: {
						'_token': "{{ csrf_token() }}",
						'admin_id': $( "input[name='admin']" ).val(),
						'email': function() {
							return $( "input[name='email']" ).val();
						}
					}
				}
			},
			password: {
				required:{	
					depends:function(){
						if($(this).val().length > 0 || $('input[name="admin"]').val().length === 0){
							return true;
						}else{
							return false;//0
						}
					}
				},
				minlength: function(){
					if($('input[name="password"]').val().length > 0 || $('input[name="admin"]').val().length === 0){
						return 6;
					}else{
						return 0;
					}
				}
			},
			repeat_password: {				
				required:{
					depends:function(){
						return !$('input[name="admin"]').val().length > 0;
					}
				},
				equalTo: 'input[name="password"]'
			},
		},
		messages: {
			name:  {
				required: "Nombre requerido",
				maxlength: "Máximo 100 caracteres"
			},
			email: {
				email: "Ingrese un correo electrónico válido",
				required: "Correo electrónico requerido",
				maxlength: "Máximo 100 caracteres",
				remote: "Correo electrónico en uso"
			},
			password: {
				required: "Contraseña requerida",
				minlength: "Minimo 6 caracteres",			
				maxlength: "Máximo 30 caracteres"
			},
			repeat_password: {
				required: "Contraseña requerida",
				minlength: "Minimo 6 caracteres",
				maxlength: "Máximo 30 caracteres",
				equalTo:"Contraseñas deben ser iguales",
			}
		}
	});
	$(document).ready(function(){
		$('#btnSave').click(function(e){
			e.preventDefault();
			if($("#frmAdmin").valid()===true){
				$("#frmAdmin").submit();
			}
		});		
	});
</script>
@stop