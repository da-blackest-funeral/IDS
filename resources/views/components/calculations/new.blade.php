<div class="col-12 col-md-3 mt-4">
    <label for="new">Новая сетка или ремонт</label>
    <select name="new" id="new" class="form-control">
        <option value="1">Новая сетка</option>
        <option
            @if(requestHasProduct() && !requestProduct()->data->new)
            selected
            @endif
            value="0">
            Ремонт сетки
        </option>
    </select>
</div>
