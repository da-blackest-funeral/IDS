<select name="profiles" id="profiles" onchange="getConfiguration('/ajax/mosquito-systems/additional', 'load-additional')">
    <option value="0">Профиль</option>
    @forelse($data as $item)
        <option value="{{ $item->id }}">{{ $item->name }}</option>
    @empty
        Нет профилей
    @endforelse
</select>
<div id="load-additional" class="form-row justify-content-between">

</div>
