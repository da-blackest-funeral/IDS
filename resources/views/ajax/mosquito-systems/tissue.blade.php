<select name="tissues" id="tissues">
    @forelse($data as $item)
        <option value="{{ $item->id }}">{{ $item->name }}</option>
    @empty
        Нет сеток
    @endforelse
</select>
<div id="load"></div>
