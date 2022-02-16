@for($i = 1; $i <= $camerasCount; $i++)
    <p>Ширина камеры №{{ $i }}</p>
    <select name="width-{{ $i }}" id="width-{{ $i }}">
        <option>Ширина</option>
        @foreach($widthArray as $width)
            <option value="{{ $width->id }}">{{ $width->width }}</option>
        @endforeach
    </select>
@endfor
<select name="temperature-controller" id="temperature-controller">
    <option>Нужен ли терморегулятор?</option>
    @foreach($temperatureControllers as $controller)
        <option value="{{ $controller->id }}">{{ $controller->name }}</option>
    @endforeach
</select>
<p>Дополнительные поля, одинаковые для всех</p>
