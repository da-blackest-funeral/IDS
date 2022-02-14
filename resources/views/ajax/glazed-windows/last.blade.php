<select name="last" id="last">
    @if(empty($data))
        <option value="1">Однокамерный</option>
        <option value="2">Двухкамерный</option>
    @else
        @foreach($data as $item)
            <option value="{{ $item->id }}">{{ $item->thickness }}</option>
        @endforeach
    @endif
</select>
<div id="load-additional"></div>
