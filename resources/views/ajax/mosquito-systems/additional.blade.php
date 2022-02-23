<div class="row">
    @foreach($groups as $group)
        <div class="col-10 col-md-3 mt-1 pb-xxl-3">
            <label for="{{ $group->name }}">{{ $group->name }}</label>
            <select name="{{ $group->name }}" id="{{ $group->name }}" class="form-control">
                <option value="0">{{ $group->name }}</option>
                @foreach($additional as $item)
                    @if($item->group_id == $group->id)
                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                    @endif
                @endforeach
            </select>
        </div>
    @endforeach
</div>
