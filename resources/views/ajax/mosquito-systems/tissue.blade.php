<select name="{{ $name }}" id="{{ $name }}" onchange="getConfiguration('{{ $link }}', 'load', '{{ $name }}')">
    @forelse($data as $item)
        <option value="{{ $item->id }}">{{ $item->name }}</option>
    @empty
        Нет сеток
    @endforelse
</select>
<div id="load"></div>
