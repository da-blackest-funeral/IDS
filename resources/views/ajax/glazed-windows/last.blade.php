<select name="@if(!$isWithHeating) with-heating @else cameras-count @endif"
        id="@if(!$isWithHeating) with-heating @else cameras-count @endif"
        onchange="getConfiguration(
            '/ajax/glazed-windows/additional',
            'load-additional',
            '@if(!$isWithHeating) with-heating @else cameras-count @endif'
            )"

    {{--getConfiguration('/ajax/glazed-windows/additional', 'load-additional', @if ($isWithHeating)--}}
    {{--'cameras-count' @else  @endif--}}
    {{--)"--}}
>
    <option value="0">Количество камер</option>
    @if(empty($data))
        <option value="1">Однокамерный</option>
        <option value="2">Двухкамерный</option>
    @else
        @foreach($data as $item)
            <option value="{{ $item->id }}">{{ $item->thickness ?? $item->name }}</option>
        @endforeach
    @endif
</select>
<div id="load-additional"></div>
