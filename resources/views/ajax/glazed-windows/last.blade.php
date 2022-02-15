<select name="last" id="last" onchange="getConfiguration('/ajax/glazed-windows/additional', 'load-additional')">
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
