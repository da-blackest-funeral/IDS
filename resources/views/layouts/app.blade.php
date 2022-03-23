<!DOCTYPE html>
<html lang="ru">
<head>
    <title>@yield('title')</title>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
    <meta name="keywords" content=""/>
    <meta name="robots" content="noindex"/>
    <script src="https://api-maps.yandex.ru/2.1/?apikey={{ env('YANDEX_API_KEY') }}&lang=ru_RU"
            type="text/javascript">
    </script>

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}"/>

</head>
<body>
<header class="header">
    @include('layouts.header')
</header>

<main class="main">
    @section('success')
        <div class="alert-success h-25 w-25 bordered">
            {{ session()->pull('registered') }}
        </div>
    @show

    <div class="w-25 h-25 ml-3" onclick="$('#error').hide(600); $('#errors').hide(500);">
        @include('components.errors')
    </div>

    <div class="w-25 h-25 ml-3" onclick="$('#notification').hide(600); $('#notifications').hide(500);">
        @include('components.notifications')
    </div>


    @yield('content')
</main>

@include('layouts.scripts')
</body>
</html>
