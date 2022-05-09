<div class="col-12 col-md-3">
    <label class="mb-1 mt-2 mt-md-0" for="height">Высота (длина)</label>
    <input name="height"
           id="height"
           placeholder="Высота (габаритн.) в мм."
           type="text"
           class="form-control"
           @if(needPreload())
           value="{{ $product->data->size->height ?? '' }}"
           @endif
           required>
</div>
