<!DOCTYPE html>
<!--[if IE 8]><html class="ie8" lang="en"><![endif]-->
<!--[if IE 9]><html class="ie9" lang="en"><![endif]-->
<!--[if !IE]><!-->
<html lang="en">
  <!--<![endif]-->
  <!-- start: HEAD -->
<head>
  <title>{{ config('application.page_title') }} - Login</title>
  <!-- start: META -->
  <meta charset="utf-8" />
  <!--[if IE]><meta http-equiv='X-UA-Compatible' content="IE=edge,IE=9,IE=8,chrome=1" /><![endif]-->
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black">
  <meta content="" name="description" />
  <meta content="" name="author" />

  <link rel="stylesheet" href="{{ asset('css/login.css') }}">

  <link rel="shortcut icon" href="{{ asset('img/favicon.ico') }}" />
</head>

<body class="platform-name">
  
  <!--Login Container-->
  <form method="POST" action="{{ asset('auth/login') }}" id="loginFRM">
    <fieldset>
      <h1><img src="{{ asset('img/loop_im.png') }}" /></h1>

      @if (Session::has('message'))
        <p class="error">
              {{ Session::get('message') }}
        </p>
      @endif
      
      {{ csrf_field() }}
      <label for="username">Username:</label>
      <input type="text" name="username" id="username" value="" />
      <label for="password">Password:</label>
      <input type="password" name="password" id="password" />
      <input type="submit" class="loginBTN" value="Login"/>
      <p>
          <a href="{{ asset('forgot_password') }}" title="Forgot Password">Forgot Password</a>
      </p>
    </fieldset>
  </form>
  <!--End Login Container-->

</body>
  
</html>