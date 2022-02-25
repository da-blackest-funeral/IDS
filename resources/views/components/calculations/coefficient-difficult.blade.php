<div class="col-12 col-md-3 mt-4">
    <label for="coefficient">Коэффициент сложности монтажа</label>
    <select name="coefficient" id="coefficient" class="form-control">
        @for($i = 1; $i <= 4; $i += 0.1)
            <option value="{{ $i }}">{{ $i }}</option>
        @endfor
    </select>
</div>
