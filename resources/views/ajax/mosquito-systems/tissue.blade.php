<select name="{{ $name }}" id="{{ $name }}" onchange="getConfiguration('{{ $link }}', 'load', '{{ $name }}')">
    <option>Выберите</option>
    @forelse($data as $item)
        <option value="{{ $item->id }}">{{ $item->name }}</option>
    @empty
    @endforelse
</select>
<div id="load"></div>
