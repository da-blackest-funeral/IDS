<select name="types" id="types" onchange="getConfiguration('/ajax/windowsills/additional', 'load-additional')">
    @foreach($data as $item)
        <option value="{{ $item->id }}">{{ $item->name }}</option>
    @endforeach
</select>
<div id="load-additional"></div>
