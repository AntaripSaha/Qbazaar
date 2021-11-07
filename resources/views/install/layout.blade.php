<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<!-- CSRF Token -->
		<meta name="csrf-token" content="{{ csrf_token() }}">

		<title>Green Store Installation</title>
		<!-- Google font -->
		<link href="https://fonts.googleapis.com/css?family=Lato:700%7CMontserrat:400,600" rel="stylesheet">

		<!-- Bootstrap -->
		<link type="text/css" rel="stylesheet" href="{{ asset('install_asset/css/bootstrap.min.css') }}"/>

		<link type="text/css" rel="stylesheet" href="{{ asset('install_asset/css/select2.css') }}"/>

		<!-- Custom stlylesheet -->
		<link type="text/css" rel="stylesheet" href="{{ asset('install_asset/css/style.css') }}"/>

		<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
		  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
		  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->

    </head>
	<body>

		<div class="container">
		    <div class="install-container col-md-6 offset-md-3">
				@yield('content')
			</div>
		</div>

		<!-- jQuery Plugins -->
		<script type="text/javascript" src="{{ asset('install_asset/js/jquery-3.6.0.min.js') }}"></script>
		<script type="text/javascript" src="{{ asset('install_asset/js/bootstrap.min.js') }}"></script>
		<script type="text/javascript" src="{{ asset('install_asset/js/select2.min.js') }}"></script>
		@yield('js-script')
	</body>
</html>