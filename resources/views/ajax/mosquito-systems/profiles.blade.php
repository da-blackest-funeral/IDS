<select name="profiles" id="profiles">
    @forelse($data as $item)
        <option value="{{ $item->id }}">{{ $item->name }}</option>
    @empty
        Нет профилей
    @endforelse
</select>
<div id="load-additional"></div>
