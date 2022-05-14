<div class="col-12 col-md-9 mt-4" style="max-width: 600px;">
    <label for="comment">{{ $label ?? 'Примечание к этой позиции' }}</label>
    <textarea name="{{ $name ?? 'comment' }}"
              id="comment"
              class="form-control"
              rows="7"
              placeholder="Примечание">{{ requestOrder()->comment }}</textarea>
</div>
