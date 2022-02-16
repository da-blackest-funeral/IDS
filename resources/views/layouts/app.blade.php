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

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css"/>
    <script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
    <script src="https://canvasjs.com/assets/script/jquery.canvasjs.min.js"></script>

    <link rel="stylesheet" type="text/css" href="css/app.css"/>

</head>
<body>
{{--@section('header')--}}

{{--@endsection--}}
<div class="container">
    @yield('content')
</div>
</body>
</html>
