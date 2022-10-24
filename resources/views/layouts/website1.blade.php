<!DOCTYPE html>
<html lang="es" >
	<!-- begin::Head -->
	<head>
		<meta charset="utf-8" />
		<title>
			{{ 'Justo A Tiempo | ' }}{{ $webtitle or 'Bienvenido' }}
		</title>

		@if(isset($custom_description))
		<meta name="description" content="{{$custom_description}}">
		@else
		<meta name="description" content="{{$main_description}}">
		@endif

		@if(isset($custom_keywords))
		<meta name="keywords" content="{{$custom_keywords}}">
		@else
		<meta name="keywords" content="{{$main_keywords}}">
		@endif
		
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<link rel="shortcut icon" href="{{ asset('/img/favicon.png') }}" />

        <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro" rel="stylesheet">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
		<link rel="stylesheet" href="{{ asset('/website/css/website.css') }}">
		<link rel="stylesheet" href="{{ asset('/website/css/mobile.css') }}">
		<link rel="stylesheet" href="{{ asset('/website/plugins/owl-carousel/assets/owl.carousel.min.css') }}">
        <link rel="stylesheet" href="{{ asset('/website/plugins/jquery-bar-rating/dist/themes/fontawesome-stars.css') }}">
    	<link rel="stylesheet" href="{{ asset('/website/fonts/line-awesome/css/line-awesome.min.css') }}">
		@yield('extra-head')
		<script src="https://use.fontawesome.com/463f9f67af.js"></script>

		@if (app()->environment() === 'production')
		<!-- Facebook Pixel Code -->
		<script>
			!function(f,b,e,v,n,t,s)
			{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
			n.callMethod.apply(n,arguments):n.queue.push(arguments)};
			if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
			n.queue=[];t=b.createElement(e);t.async=!0;
			t.src=v;s=b.getElementsByTagName(e)[0];
			s.parentNode.insertBefore(t,s)}(window,document,'script',
			'https://connect.facebook.net/en_US/fbevents.js');
			
			fbq('init', '431282360708981'); 
			fbq('track', 'PageView');
		</script>
		<noscript>
			<img height="1" width="1" 
			src="https://www.facebook.com/tr?id=431282360708981&ev=PageView
			&noscript=1"/>
		</noscript>
		@endif

		<!--Start of Zendesk Chat Script-->
		<script type="text/javascript">
		window.$zopim||(function(d,s){var z=$zopim=function(c){
		z._.push(c)},$=z.s=
		d.createElement(s),e=d.getElementsByTagName(s)[0];z.set=function(o){z.set.
		_.push(o)};z._=[];z.set._=[];$.async=!0;$.setAttribute('charset','utf-8');
		$.src='https://v2.zopim.com/?64aevoZT5s7r27H7BS6eigYa6cIjMGzw';z.t=+new Date;$.
		type='text/javascript';e.parentNode.insertBefore($,e)})(document,'script');
		</script>
		<!--End of Zendesk Chat Script-->
    	<link rel="stylesheet" href="{{ asset('/css/rh.css') }}">

	</head>
	<!-- end::Head -->
    <!-- begin::Body -->
	<body>
        @include('layouts.includes.header')
        @yield('content')
		@include('layouts.includes.footer')

        <script src="https://code.jquery.com/jquery-3.2.1.min.js" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
		<script src="{{ asset('/website/ts/header.js') }}"></script>
		<script src="{{ asset('/website/plugins/owl-carousel/owl.carousel.min.js') }}"></script>
		<script src="{{ asset('/website/plugins/jquery-bar-rating/dist/jquery.barrating.min.js') }}"></script>
		<script src="{{ asset('/js/validation/jquery.validate.js')}}"></script>		
		<script src="{{ asset('/website/js/sweetalert2/sweetalert2.all.js') }}"></script>
		<script type="text/javascript">var login_url = "{{route('website-login')}}";</script>
		<script src="{{ asset('/website/ts/custom.js') }}"></script>
		
		{{-- Google analytics --}}
		@if (app()->environment() === 'production')
		<!-- Global site tag (gtag.js) - Google Analytics -->
		<script async src="https://www.googletagmanager.com/gtag/js?id=UA-110827907-1"></script>
			<script>
			window.dataLayer = window.dataLayer || [];
			function gtag(){dataLayer.push(arguments);}
			gtag('js', new Date());

			gtag('config', 'UA-110827907-1');

    
	        




		</script>
		@endif

        @yield('js')
	</body>
	<!-- end::Body -->


	<script type="text/javascript">
		
		$(".averageRating").barrating('show', {
	            theme: 'fontawesome-stars',
	            readonly: true,
	            onSelect: function(value, text, event) {
	                if (typeof(event) !== 'undefined') {
	                // rating was selected by a user
	                console.log(event.target);
	                } else {
	                // rating was selected programmatically
	                // by calling `set` method
	                }
	            }
	        });
	</script>
</html>

