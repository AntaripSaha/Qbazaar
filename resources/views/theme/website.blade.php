<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" value="{{ csrf_token() }}" />
    <link rel="shortcut icon" href="{{ get_favicon() }}">

    <title>{{ get_option('site_title', config('app.name')) }}</title>
    <meta name="keywords" content="{{ get_option('meta_keywords','grocerry, medicine, online shop') }}"/>
    <meta name="description" content="{{ get_option('meta_content','Online Grocerry and Medicine Shop') }}"/>

    <link rel="stylesheet" href="{{ asset('backend/assets/fonts/icofont/icofont.min.css') }}">
    <link href="{{ mix('theme/css/app.css') }}" type="text/css" rel="stylesheet" defer>
    <link rel="stylesheet" href="{{ asset('theme/css/style.css') }}" defer>
    <link rel="stylesheet" href="{{ asset('theme/css/responsive.css') }}" defer>

    @include('theme.includes.variables')

    @if(get_option('paypal_active','No') == 'Yes')
    <script src="https://www.paypal.com/sdk/js?client-id={{ get_option('paypal_client_id') }}&currency={{ get_option('currency') }}&disable-funding=paylater" defer></script>
    @endif

	@if(get_option('stripe_active','No') == 'Yes')
	<script src="https://js.stripe.com/v3/"></script>
	@endif

</head>

<body>
    <div id="app"></div>

    <script src="{{ mix('theme/js/app.js') }}" type="text/javascript"></script>
    <script src="{{ asset('theme/js/main.js') }}"></script>
</body>

</html>