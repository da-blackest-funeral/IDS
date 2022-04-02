{{-- Для Сеток плиссе Россия добавить картинку --}}
@if($product->type_id == 9)
    <img src="https://03-okna.ru/images/plisse_type1.png" alt="Картинка с открыванием" height="200">
@endif

<div class="row w-75">
    @foreach($groups as $group)
        <div class="col-10 col-md-3 mt-1 pb-xxl-3">
            <label for="{{ $group->name }}">{{ $group->name }}</label>
            <select name="group-{{ $loop->iteration }}" id="{{ $group->name }}" class="form-control">
                @foreach($additional as $item)
                    @if($item->group_id == $group->id)
                        <option
                            @isset($group->selected)
                            @if($group->selected == $item->id)
                            selected
                            @endif
                            @endisset
                            value="{{ $item->id }}">
                            {{ $item->name }}
                        </option>
                    @endif
                @endforeach
            </select>
        </div>
    @endforeach
</div>

{{-- Для сеток плиссе Италия вывести дополнительное примечание --}}
@if($product->type_id == 5)
    @section('info', 'Если одностороннее открывание, то это одно полотно и одна ручка, которая двигается в проеме.')
@include('components.alert-info')
@endif

{{-- Для раздвижных сеток добавить дополнительный инпут с полозьями --}}
@if($product->type_id == 3)
@section('info')
    <div class="mt-2 w-75">
        <label for="poloz">
            <strong>Указывается длина за одну направляющую. Т.е. если нужно сделать 2 направляющие по 1000
                мм,
                указать 1000 мм</strong>
        </label>
        <input id="poloz" type="text" placeholder="Полозья, мм." name="poloz" class="form-control mt-2">
    </div>
@endsection
@include('components.alert-info', ['cantClose' => true])
@endif

<div class="row w-75">
    @include('components.calculations.additional-mounting-tools')
    @include('components.calculations.coefficient-difficult')

    {{-- Для рулонных сеток не выводятся эти поля --}}
    @if(!in_array($product->type_id, [4, 5]))
        @include('components.calculations.new')
        @include('components.calculations.immediately')
    @endif

</div>
@include('components.calculations.comment')

<div class="row">
    @include('components.calculations.submit', ['value' => 'Добавить'])
    @include('components.calculations.additional-bracing')
    {{-- todo Добавить тут функционал кнопки "дополнительное крепление" --}}
</div>
