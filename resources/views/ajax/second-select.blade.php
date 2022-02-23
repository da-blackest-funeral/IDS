<div id="{{ $name }}" class="mt-0 mb-3">
    <label for="{{ $name }}" >{{ $label }} </label>
        <select name="{{ $name }}"
                id="{{ $name }}"
                onchange="getConfiguration('{{ $link }}', 'third', '{{ $name }}')"
                class="form-control">
            <option>Выберите</option>
            @forelse($data as $item)
                <option value="{{ $item->id }}">{{ $item->name }}</option>
            @empty
            @endforelse
        </select>
</div>
