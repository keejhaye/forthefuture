<!doctype html>
<html ng-app='MainApp'>
    <head>
        <div class="pre-load" id="pre-load"></div>
        <link rel="stylesheet" href="{{asset('css/pre-load.css')}}">
        <script type="text/javascript">
            var base_url = "{{ asset('/') }}"
            var user_id = "{{ \Session::get('user.id') }}"
            var user_services = "{{ json_encode(\Session::get('user.services')) }}"
            var role_id = "{{\Session::get('user.role_id')}}"
            outbound_count = {{\Session::get('outbound_count')}}
            chat_time = "{{\Session::get('chat_time')}}"
        </script>

        <title>{{ config('application.page_title') }} {{ isset($page_title) ? '- ' . $page_title : '' }}</title>
        <!-- start: META -->
        <meta charset="utf-8" />
        <!--[if IE]><meta http-equiv='X-UA-Compatible' content="IE=edge,IE=9,IE=8,chrome=1" /><![endif]-->
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
        <meta content="" name="description" />
        <meta content="" name="author" />

        <!-- PLUGIN CSS -->
        <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
        <link rel="stylesheet" href="{{asset('css/responsive.bootstrap.min.css')}}">

        <!-- CORE CSS -->
        <link rel="stylesheet" href="{{asset('fonts/font-awesome/css/font-awesome.min.css')}}">
        <link rel="stylesheet" href="{{asset('css/styles.css')}}">
        <link rel="stylesheet" href="{{asset('css/sticky-footer.css')}}">
        <link rel="shortcut icon" href="{{ asset('img/favicon.ico') }}" />
        <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/angular_material/1.1.1/angular-material.min.css">
        <!--CUSTOM CSS-->
        @yield('css')
    </head>
   
<body class="platform-name">
	<!--Main Header-->
	@include('panel.header')
	<!--End Main Header-->
		
	<!--Main Container-->
	<div class="container-fluid">
        <div class="container-custom">
                @yield('content')
        </div>
	</div>
	<!--End Main Container-->
	
	<!--Main Footer-->
	@include('panel.footer')
	<!--End Main Footer-->
    
    <!-- GLOBAL SCRIPTS -->
    <script type="text/javascript" src="http://localhost:3000/socket.io/socket.io.js"></script>
    <script type="text/javascript" src="{{ asset('js/angular/angular.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('js/angular/services/angular-socket.js')}}"></script>
    <script type="text/javascript" src="{{ asset('bower_components/angular-bootstrap/ui-bootstrap.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('js/angular/factory/statistics.js')}}"></script>
    <script type="text/javascript" src="{{ asset('js/angular/controllers/header.js')}}"></script>
    <script type="text/javascript" src="{{ asset('js/angular/app/header.js')}}"></script>
    <!-- <script type="text/javascript" src="{{ asset('js/angular/app/d74c7455b8cbe5be6f644690ecb1b8a.js')}}"></script> -->

    <script type="text/javascript" src="{{asset('/')}}/bower_components/angular-animate/angular-animate.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.5.3/angular-sanitize.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.5.3/angular-aria.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.5.3/angular-messages.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/angular_material/1.1.1/angular-material.min.js"></script>
    @yield('script')
</body>

</html>
