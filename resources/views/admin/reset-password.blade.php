<!DOCTYPE html>
<!-- 
Template Name: Metronic - Responsive Admin Dashboard Template build with Twitter Bootstrap 4
Version: 5.0.5
Author: KeenThemes
Website: http://www.keenthemes.com/
Contact: support@keenthemes.com
Follow: www.twitter.com/keenthemes
Dribbble: www.dribbble.com/keenthemes
Like: www.facebook.com/keenthemes
Purchase: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes
Renew Support: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes
License: You must have a valid license purchased only from themeforest(the above link) in order to legally use the theme for your project.
-->
<html lang="en" >
	<!-- begin::Head -->
	<head>
		<meta charset="utf-8" />
		<title>
			Home Express Center | Iniciar sesión
		</title>
		<meta name="description" content="Latest updates and statistic charts">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<!--begin::Web font -->
		<script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
		<script src='https://www.google.com/recaptcha/api.js'></script>
		<script>
          WebFont.load({
            google: {"families":["Poppins:300,400,500,600,700","Roboto:300,400,500,600,700"]},
            active: function() {
                sessionStorage.fonts = true;
            }
          });
		</script>
		<!--end::Web font -->
        <!--begin::Base Styles -->
		<link href="/metronic_v5.0.5/theme/default/dist/default/assets/vendors/base/vendors.bundle.css" rel="stylesheet" type="text/css" />
		<link href="/metronic_v5.0.5/theme/default/dist/default/assets/demo/default/base/style.bundle.css" rel="stylesheet" type="text/css" />
		<!--end::Base Styles -->
		<link rel="shortcut icon" href="/img/favicon.png" />
	</head>
	<!-- end::Head -->
    <!-- end::Body -->
	<body class="m--skin- m-header--fixed m-header--fixed-mobile m-aside-left--enabled m-aside-left--skin-dark m-aside-left--offcanvas m-footer--push m-aside--offcanvas-default"  >
		<!-- begin:: Page -->
		<div class="m-grid m-grid--hor m-grid--root m-page">
			<div class="m-grid__item m-grid__item--fluid m-grid m-grid--ver-desktop m-grid--desktop m-grid--tablet-and-mobile m-grid--hor-tablet-and-mobile m-login m-login--1 m-login--singin" id="m_login">
				<div class="m-grid__item m-grid__item--order-tablet-and-mobile-2 m-login__aside">
					<div class="m-stack m-stack--hor m-stack--desktop">
						<div class="m-stack__item m-stack__item--fluid">
							<div class="m-login__wrapper">
								<div class="m-login__logo">
									<a href="{{route('login-admin')}}">
										<img src="/img/hec_full.png">
									</a>
								</div>

								@if($errors->any())
								<div class="row">
									<div class="col-md-6 offset-md-3">
										<ul>
											@foreach ($errors->all() as $error)
											<li>{{ $error }}</li>
											@endforeach
										</ul>
									</div>
								</div>
								@endif
								@if($valid)
								<div class="m-login__signin">
									<div class="m-login__head">
										<h3 class="m-login__title">
											Recuperar contraseña
										</h3>
									</div>
									<form action="{{route('admin-recover-password',[$user->email,$user->reset_token])}}" method="POST" id="frmRecover">
                                        {{ csrf_field() }}
                                        <div class="form-group">
                                            <label for="email">Contraseña<span class="required">*</span></label>
                                            <input type="password" name="password" class="form-control" id="password">
                                        </div>
                                        <div class="form-group">
                                            <label for="email">Repetir contraseña<span class="required">*</span></label>
                                            <input type="password" name="repassword" class="form-control">
                                        </div>
                                        <div class="m-login__form-action">
											<button type="submit" class="btn btn-focus m-btn m-btn--pill m-btn--custom m-btn--air">Continuar</button>
										</div>
                                    </form>
								</div>
								@else
									<div class="alert alert-warning text-center mt-5" style="padding:50px 0">
										<span class="fa fa-warning mb-3" style="color: #262b36;font-size: 30px;"></span>
										<h3>Correo o Token inválido, revisa tu correo para poder recuperar tu contraseña</h3>      
									</div>
									<div class="m-login__form-action">
									<a id="m_login" href="{{route('login-admin')}}" class="btn btn-focus m-btn m-btn--pill m-btn--custom m-btn--air">
											Login
										</a>
									</div>
								@endif
							</div>
						</div>
					</div>
					<!--<div class="m-stack__item m-stack__item--center">
							<div class="m-login__account">
								<span class="m-login__account-msg">
									Don't have an account yet ?
								</span>
								&nbsp;&nbsp;
								<a href="javascript:;" id="m_login_signup" class="m-link m-link--focus m-login__account-link">
									Sign Up
								</a>
							</div>
						</div>-->
				</div>
				<div class="m-grid__item m-grid__item--fluid m-grid m-grid--center m-grid--hor m-grid__item--order-tablet-and-mobile-1	m-login__content" style="background-image: url(/img/fondo.jpg)">
					<div class="m-grid__item m-grid__item--middle">
						<h3 class="m-login__welcome">
							Bienvenido
						</h3>
						<p class="m-login__msg">
							Lorem ipsum dolor sit amet, coectetuer adipiscing
							<br>
							elit sed diam nonummy et nibh euismod
						</p>
					</div>
				</div>
			</div>
		</div>
		<!-- end:: Page -->
    	<!--begin::Base Scripts -->
		<script src="/metronic_v5.0.5/theme/default/dist/default/assets/vendors/base/vendors.bundle.js" type="text/javascript"></script>
		<script src="/metronic_v5.0.5/theme/default/dist/default/assets/demo/default/base/scripts.bundle.js" type="text/javascript"></script>
		<!--end::Base Scripts -->   
		<!--begin::Page Snippets -->
		<script>
		$('#frmRecover').validate({
			rules:{
			"password": {
					minlength: 4,
					required: true,
				},
			"repassword": {
					minlength: 4,
					required: true,
					equalTo: "#password"
				}
			},
			messages: {
				password:{
				required: "Una contraseña es requerida",
				minlength: "La contraseña debe contener al menos {0} caracteres"
				},
				repassword:{
				required: "Este campo es requerido",
				equalTo: "Las contraseñas no coinciden",
				minlength: "La contraseña debe contener al menos {0} caracteres"
				}
			},
		});
		</script>
		<!--end::Page Snippets -->
	</body>
	<!-- end::Body -->
</html>
