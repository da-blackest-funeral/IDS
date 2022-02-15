@foreach($groups as $group)
    <select name="{{ $group->name }}" id="{{ $group->name }}">
        @foreach($additional as $item)
            @if($item->pivot->type_id == $group->pivot->type_id)
                <option value="{{ $item->id }}">{{ $item->name }}</option>
            @endif
        @endforeach
    </select>
@endforeach
