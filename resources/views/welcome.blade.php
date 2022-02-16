<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Document</title>
</head>
<body>
<div class="container">
    <select name="test" id="categories">
        <option>Категория</option>
        @foreach($superCategories as $category)
            <optgroup label="{{ $category->name }}">
                @foreach($data as $item)
                    @if($item->parent_id == $category->id)
                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                    @endif
                @endforeach
            </optgroup>
        @endforeach
    </select>
    <div id="items">

    </div>
</div>

<script src="{{ asset('js/vendor.js') }}"></script>
<script src="{{ asset('js/manifest.js') }}"></script>
<script src="{{ asset('js/app.js') }}"></script>
<script>
    function getConfiguration(url, id, selectorId = false) {
        let data = {
            categoryId: $('#categories').find('option:selected').val(),
        }
        if (selectorId) {
            data.additional = $('#' + selectorId).find('option:selected').val();
        }
        $.ajax({
            type: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: url,
            data: data,
            success: function (data) {
                console.log(data)
                $('#' + id).html(data)
            }
        });
    }
</script>

</body>
</html>
