@foreach($groups as $group)
    <select name="{{ $group->name }}" id="{{ $group->name }}">
        @foreach($additional as $item)
            @if($item->group_id == $group->id)
                <option value="{{ $item->id }}">{{ $item->name }}</option>
            @endif
        @endforeach
    </select>
@endforeach
