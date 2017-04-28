<!doctype html>
<html>
    <head>
        <title>{{ config('application.page_title') }} {{ isset($page_title) ? '- ' . $page_title : '' }}</title>
        <!-- start: META -->
        <meta charset="utf-8" />
        <!--[if IE]><meta http-equiv='X-UA-Compatible' content="IE=edge,IE=9,IE=8,chrome=1" /><![endif]-->
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
        <meta content="" name="description" />
        <meta content="" name="author" />
        
        <link rel="shortcut icon" href="{{ url('img/favicon.ico') }}" />

        <!-- PLUGIN CSS -->
        <link rel="stylesheet" href="<?php echo URL::asset('css/bootstrap.min.css') ?>">
        <link rel="stylesheet" href="<?php echo URL::asset('css/responsive.bootstrap.min.css') ?>">


        <!--CUSTOM CSS-->
        @yield('css')
    </head>
   
<body>
    <br>
	<!--Main Container-->
    <div class="container-fluid">
    @yield('content')
    </div>
	<!--End Main Container-->

    <!--SCRIPTS-->

    <!--CUSTOM SCRIPTS-->
    @yield('script')

</body>

</html>
