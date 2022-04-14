<label for="{{ $name }}">{{ $label }}</label>
<select class="form-control" name="{{ $name }}" id="{{ $name }}"
        onchange="getConfiguration(
            '/api/glazed-windows/additional',
            'additional',
            '{{ $name }}')">
    <option value="0">{{ $label }}</option>
    @if(empty($data))
        <option value="1">Однокамерный</option>
        <option value="2">Двухкамерный</option>
    @else
        @foreach($data as $item)
            <option value="{{ $item->id }}">{{ $item->thickness ?? $item->name }}</option>
        @endforeach
    @endif
</select>
