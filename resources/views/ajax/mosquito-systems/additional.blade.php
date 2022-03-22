<div class="row w-75">
    @foreach($groups as $group)
        <div class="col-10 col-md-3 mt-1 pb-xxl-3">
            <label for="{{ $group->name }}">{{ $group->name }}</label>
            <select name="group-{{ $loop->iteration }}" id="{{ $group->name }}" class="form-control">
                <option value="0">Выбрать</option>
                @foreach($additional as $item)
                    @if($item->group_id == $group->id)
                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                    @endif
                @endforeach
            </select>
        </div>
    @endforeach
</div>
<div class="row w-75">
    @include('components.calculations.additional-mounting-tools')
    @include('components.calculations.coefficient-difficult')
    @include('components.calculations.new')
    @include('components.calculations.immediatly')
</div>
@include('components.calculations.comment')
@include('components.calculations.submit')
