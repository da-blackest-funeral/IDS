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
    <link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}"/>

</head>
<body>
<header class="header">
    @include('layouts.header')
</header>

<main class="main">

    <div class="h-25 ml-3" style="min-width: 200px; max-width: 550px;">
        @include('components.errors')
    </div>

    <div class="h-25 ml-3" style="min-width: 200px; max-width: 550px;">
        @include('components.success')
    </div>


    <div class="h-25 ml-3" style="min-width: 200px; max-width: 550px;"
         onclick="$('#notification').hide(600); $('#notifications').hide(500);">
        @include('components.notifications')
    </div>

    @yield('content')
</main>

@include('layouts.scripts')
</body>
</html>
