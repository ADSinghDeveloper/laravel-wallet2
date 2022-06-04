<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta http-equiv="Content-Type" content="text/html;" charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no">

    <title>@yield('pageTitle') :: {{ config('app.name', 'Wallet') }}</title>

    <!-- Styles -->
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
    <!-- CSS Files -->
    <link href="{{ url('matim/css/material-kit.css?v=2.0.4') }}" rel="stylesheet" />
    <link href="{{ url('matim/css/material-dashboard.css?v=2.1.1') }}" rel="stylesheet" />

    <link href="{{ url('css/wallet.css') }}" rel="stylesheet" />
</head>
<body>
<div class="wrapper print">
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-lg-12">
          @include('alerts')
          @yield('content')
        </div>
      </div>
    </div>
  </div>
</div>
</body>
</html>
