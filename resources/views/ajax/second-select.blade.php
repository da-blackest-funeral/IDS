<div id="select-wrapper">
    <select name="{{ $name }}"
            id="{{ $name }}"
            onchange="getConfiguration('{{ $link }}', '{{ $name }}', '{{ $name }}')"
            class="form-control">
        <option>Выберите</option>
        @forelse($data as $item)
            <option value="{{ $item->id }}">{{ $item->name }}</option>
        @empty
        @endforelse
    </select>
</div>
