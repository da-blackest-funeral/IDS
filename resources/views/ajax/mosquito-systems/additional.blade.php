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

{{-- Для раздвижных сеток добавить дополнительный инпут с полозьями --}}
@if($product->type_id == 3)
    <div class="w-25">
        <label for="poloz">
            <strong>Внимание! Указывается длина за одну направляющую. Т.е. если нужно сделать 2 направляющие по 1000 мм, указать 1000 мм</strong>
        </label>
        <input id="poloz" type="text" placeholder="Полозья, мм." name="poloz" class="form-control">
    </div>
@endif

<div class="row w-75">
    @include('components.calculations.additional-mounting-tools')
    @include('components.calculations.coefficient-difficult')

    {{-- Для рулонных сеток не выводятся эти поля --}}
    @if($product->type_id != 4)
        @include('components.calculations.new')
        @include('components.calculations.immediately')
    @endif

</div>
@include('components.calculations.comment')
{{-- Добавить тут функционал кнопки "дополнительное крепление" --}}
@include('components.calculations.submit')
