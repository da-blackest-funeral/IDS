<!DOCTYPE html>
<html lang="ru">
<head>
    <title>@yield('title')</title>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
    {{--    <link rel="SHORTCUT ICON" href="images/1.ico" type="image/x-icon"/>--}}
    <meta name="keywords" content=""/>
    <meta name="robots" content="noindex"/>
    <script src="https://api-maps.yandex.ru/2.1/?apikey={{ env('YANDEX_API_KEY') }}&lang=ru_RU"
            type="text/javascript">
    </script>

    {{--    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">--}}

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
    {{--    <link rel="SHORTCUT ICON" href="https://03-okna.ru/images/1.ico" type="image/x-icon"/>--}}

    {{--    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">--}}
    <link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}"/>

</head>
<body>
@include('layouts.header')

@section('success')
    <div class="alert-success h-25 w-25 bordered">
        {{ session()->pull('registered') }}
    </div>
@show

@section('errors')
    @if($errors->any())
        @foreach($errors->all() as $error)
            <div class="alert-danger h-25 w-25 bordered">
                {{ $error }}
            </div>
        @endforeach
    @endif
@show

@yield('content')

@include('layouts.scripts')
</body>
</html>
