<div class="row">
    @for($i = 1; $i <= $camerasCount; $i++)
        <div class="col-12 col-md-3 mt-4">
            <label for="width-{{ $i }}">Ширина камеры №{{ $i }}</label>
            <select name="width-{{ $i }}" id="width-{{ $i }}" class="form-control">
                <option>Ширина</option>
                @foreach($widthArray as $width)
                    <option value="{{ $width->id }}">{{ $width->width }} .мм</option>
                @endforeach
            </select>
        </div>
    @endfor
</div>
<div class="row">
    @include('components.calculations.glazed-window-installation')
    @include('components.calculations.coefficient-difficult')
    @include('components.calculations.additional-mounting-tools')
</div>
<div class="row mt-3">
    <div class="col-12 col-md-3 mt-3">
        <label for="temperature-controller">Нужен ли терморегулятор?</label>
        <select name="temperature-controller" id="temperature-controller" class="form-control">
            <option value="0">Нет</option>
            @foreach($temperatureControllers as $controller)
                <option value="{{ $controller->id }}">{{ $controller->name }}</option>
            @endforeach
        </select>
    </div>
    @include('components.calculations.takeaway')
</div>
@include('components.calculations.comment')
@include('components.calculations.submit')
