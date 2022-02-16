<div>
    <select name="{{ $name }}"
            id="{{ $name }}"
            onchange="getConfiguration('{{ $link }}', 'load', '{{ $name }}')"
            class="form-control w-25">
        <option>Выберите</option>
        @forelse($data as $item)
            <option value="{{ $item->id }}">{{ $item->name }}</option>
        @empty
        @endforelse
    </select>
</div>
<div id="load"></div>
