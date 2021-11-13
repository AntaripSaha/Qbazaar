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
    <!-- Google Translator Css Start -->
    <style>
      .translated-ltr {
        margin-top: -40px;
      }
    
      .translated-ltr {
        margin-top: -40px;
      }
    
      .goog-te-banner-frame {
        display: none;
        margin-top: -20px;
      }
    
      .goog-logo-link {
        display: none !important;
      }
    
      .goog-te-gadget {
        color: transparent !important;
      }
    </style>
    <!-- Google Translator Css End -->
</head>

<body>
    
    <div id="app"></div>
    <script src="{{ mix('theme/js/app.js') }}" type="text/javascript"></script>
    <script src="{{ asset('theme/js/main.js') }}"></script>
</body>

</html>

<!-- Google Trasnlator Script Start -->

<script type="text/javascript">
  function googleTranslateElementInit() {
    new google.translate.TranslateElement({ pageLanguage: 'en' }, 'google_translate_element');
  }
</script>

<script type="text/javascript"
  src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

<!-- Google Trasnlator Script End -->