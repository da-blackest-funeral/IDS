<div id="profiles">
    <label for="profiles">Профиль</label>
    <select name="profiles"
            id="profiles"
            class="form-control"
            onchange="getConfiguration('/ajax/mosquito-systems/additional', 'additional', 'profiles', 'tissues')">
        <option value="0">Профиль</option>
        @forelse($data as $item)
            <option value="{{ $item->id }}">{{ $item->name }}</option>
        @empty
            Нет профилей
        @endforelse
    </select>
</div>
