@for($i = 1; $i <= $camerasCount; $i++)
    @if($i == 1)
        <div class="col-10 col-md-3 mt-1">
            <label for="glass-width-{{ $i }}">{{ $i }}-стекло</label>
            <select name="glass-width-{{ $i }}" id="glass-width-{{ $i }}" class="form-control">
                <option value="0">Ширина стекла</option>
                @foreach($glassWidth as $width)
                    <option value="{{ $width->id }}">{{ $width->name }}</option>
                @endforeach
            </select>
        </div>
    @endif
    <div class="col-10 col-md-3 mt-1">
        <label for="cameras-width-{{ $i }}">{{ $i }}-я камера</label>
        <select name="cameras-width-{{ $i }}" id="cameras-width-{{ $i }}" class="form-control">
            <option value="0">Ширина камеры</option>
            @foreach($camerasWidth as $width)
                <option value="{{ $width->id }}">{{ $width->width }} мм.</option>
            @endforeach
        </select>
    </div>

    <div class="col-10 col-md-3 mt-1">
        <label for="glass-width-{{ $i + 1}}">{{ $i + 1 }}-стекло</label>
            <select name="glass-width-{{ $i + 1 }}" id="glass-width-{{ $i + 1}}" class="form-control">
            <option value="0">Ширина стекла</option>
            @foreach($glassWidth as $width)
                <option value="{{ $width->id }}">{{ $width->name }}</option>
            @endforeach
        </select>
    </div>
@endfor

