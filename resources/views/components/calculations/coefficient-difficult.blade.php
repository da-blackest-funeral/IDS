<div class="col-12 col-md-3 mt-4">
    <label for="coefficient">Коэфф. сложности монтажа</label>
    <select name="coefficient" id="coefficient" class="form-control">
        <option value="1">Коэффициент</option>
        @for($i = 1.1; $i <= 4; $i += 0.1)
            <option
                @if(isset($productData, $productData->coefficient) && equals($productData->coefficient, $i))
                selected
                @endif
                value="{{ $i }}">
                {{ $i }}
            </option>
        @endfor
    </select>
</div>
