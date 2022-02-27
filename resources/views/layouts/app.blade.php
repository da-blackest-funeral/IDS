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
    @section('success')
        <div class="alert-success h-25 w-25 bordered">
            {{ session()->pull('registered') }}
        </div>
    @show

    @section('errors')
        @if($errors->any())
            <div class="m-xxl-3 p-3 alert-danger border shadow border-danger rounded w-25 h-25">
                @foreach($errors->all() as $error)
                    <div class="pb-2">
                        <span class="font-weight-bold">{{ $error }}</span>
                    </div>
                @endforeach
            </div>
        @endif
    @show
    @yield('content')
</main>

@include('layouts.scripts')
</body>
</html>
