@foreach($groups as $group)
    <p>{{ $group->name }}</p>
    <select name="{{ $group->name }}" id="{{ $group->name }}">
        <option value="0">{{ $group->name }}</option>
        @foreach($additional as $item)
            @if($item->group_id == $group->id)
                <option value="{{ $item->id }}">{{ $item->name }}</option>
            @endif
        @endforeach
    </select>
@endforeach
