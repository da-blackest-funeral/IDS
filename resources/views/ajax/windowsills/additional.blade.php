<div class="row">
    @include('components.calculations.glazed-window-installation')
    <div class="col-10 col-md-3 mt-4">
        <label for="plug">Нужна ли заглушка?</label>
        <select name="plug" id="plug" class="form-control">
            <option value="0">Не нужна</option>
            <option value="1">Нужна</option>
        </select>
    </div>
    <div class="col-10 col-md-3 mt-4">
        <label for="docking-profile">Стыковочный профиль</label>
        <select name="docking-profile" id="docking-profile" class="form-control">
            <option value="0">Не нужен</option>
            @for($i = 1; $i <= 5; $i++)
                <option value="{{ $i }}">{{ $i }}</option>
            @endfor
        </select>
    </div>
</div>

<div class="row">
    @include('components.calculations.coefficient-difficult')
    <div class="col-10 col-md-3 mt-4">
        <label for="dismantling">Демонтаж старого подоконника</label>
        <select name="dismantling" id="dismantling" class="form-control">
            <option value="0">Не нужен</option>
            <option value="1">Нужен</option>
        </select>
    </div>
    <div class="col-10 col-md-3 mt-4">
        <label for="color">Цвет</label>
        <input name="color" id="color" class="form-control" placeholder="(как у поставщика)">
    </div>
</div>

@include('components.calculations.comment')
@include('components.calculations.submit')
