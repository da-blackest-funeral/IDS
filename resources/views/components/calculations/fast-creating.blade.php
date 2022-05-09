<div class="col-12 col-md-3 mt-4">
    <label for="fast">Срочное изготовление</label>
    <select name="fast" id="fast" class="form-control">
        <option value="0">Обычное изготовление</option>
        <option
            @if(requestHasProduct() && requestProduct()->data->fast)
            selected
            @endif
            value="1">
            Срочное изготовление
        </option>
    </select>
</div>
