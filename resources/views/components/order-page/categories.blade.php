<div class="col-10 col-md-3 mt-2 mt-md-0" id="categories-container">
    <label class="mb-1 mt-2 mt-md-0" for="categories">Тип изделия</label>
    <select name="categories" id="categories" class="form-control">
        <option>Тип изделия</option>
        @foreach($superCategories as $category)
            <optgroup label="{{ $category->name }}">
                @foreach($data as $item)
                    @if($item->parent_id == $category->id)
                        <option
                            @if(isset($product) && $product->category_id == $item->id && (needPreload()))
                            selected
                            @endif
                            value="{{ $item->id }}">{{ $item->name }}</option>
                    @endif
                @endforeach
            </optgroup>
        @endforeach
    </select>
</div>
