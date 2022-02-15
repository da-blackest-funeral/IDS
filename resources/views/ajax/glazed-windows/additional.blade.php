@for($i = 1; $i <= $camerasCount; $i++)
    @if($i == 1)
        <p>{{ $i }}-стекло</p>
        <select name="glass-width-{{ $i }}" id="glass-width-{{ $i }}">
            <option value="0">Ширина стекла</option>
            @foreach($glassWidth as $width)
                <option value="{{ $width->id }}">{{ $width->name }}</option>
            @endforeach
        </select>
    @endif

    <p>{{ $i }}-я камера</p>
    <select name="cameras-width-{{ $i }}" id="cameras-width-{{ $i }}">
        <option value="0">Ширина камеры</option>
        @foreach($camerasWidth as $width)
            <option value="{{ $width->id }}">{{ $width->width }} мм.</option>
        @endforeach
    </select>

    <p>{{ $i + 1 }}-стекло</p>
    <select name="glass-width-{{ $i + 1 }}" id="glass-width-{{ $i + 1}}">
        <option value="0">Ширина стекла</option>
        @foreach($glassWidth as $width)
            <option value="{{ $width->id }}">{{ $width->name }}</option>
        @endforeach
    </select>
@endfor
