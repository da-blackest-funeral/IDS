<label for="types">Тип</label>
<select name="types" id="types" class="form-control"
        onchange="getConfiguration('/ajax/windowsills/additional', 'additional')">
    <option value="0">Тип</option>
    @foreach($data as $item)
        <option value="{{ $item->id }}">{{ $item->name }}</option>
    @endforeach
</select>

