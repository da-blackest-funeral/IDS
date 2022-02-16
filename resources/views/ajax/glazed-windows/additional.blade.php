@for($i = 1; $i <= $camerasCount; $i++)
    @if($i == 1)
        <div>
            <p>{{ $i }}-стекло</p>
            <select name="glass-width-{{ $i }}" id="glass-width-{{ $i }}" class="form-control w-25">
                <option value="0">Ширина стекла</option>
                @foreach($glassWidth as $width)
                    <option value="{{ $width->id }}">{{ $width->name }}</option>
                @endforeach
            </select>
        </div>
    @endif
    <div>
        <p>{{ $i }}-я камера</p>
        <select name="cameras-width-{{ $i }}" id="cameras-width-{{ $i }}" class="form-control w-25">
            <option value="0">Ширина камеры</option>
            @foreach($camerasWidth as $width)
                <option value="{{ $width->id }}">{{ $width->width }} мм.</option>
            @endforeach
        </select>
    </div>

    <div>
        <p>{{ $i + 1 }}-стекло</p>
        <select name="glass-width-{{ $i + 1 }}" id="glass-width-{{ $i + 1}}" class="form-control w-25">
            <option value="0">Ширина стекла</option>
            @foreach($glassWidth as $width)
                <option value="{{ $width->id }}">{{ $width->name }}</option>
            @endforeach
        </select>
    </div>
@endfor

