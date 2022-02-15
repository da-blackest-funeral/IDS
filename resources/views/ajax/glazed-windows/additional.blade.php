@for($i = 1; $i <= $camerasCount; $i++)
    @if($i == 1)
        <p>{{ $i }}-стекло</p>
        <select name="glass-width-{{ $i }}" id="glass-width-{{ $i }}">
            @foreach($glassWidth as $width)
                <option value="{{ $width->id }}">{{ $width->name }}</option>
            @endforeach
        </select>
    @endif

    <p>{{ $i }}-я камера</p>
    <select name="cameras-width-{{ $i }}" id="cameras-width-{{ $i }}">
        @foreach($camerasWidth as $width)
            <option value="{{ $width->id }}">{{ $width->width }} мм.</option>
        @endforeach
    </select>

    <p>{{ $i + 1 }}-стекло</p>
    <select name="glass-width-{{ $i + 1 }}" id="glass-width-{{ $i + 1}}">
        @foreach($glassWidth as $width)
            <option value="{{ $width->id }}">{{ $width->name }}</option>
        @endforeach
    </select>
@endfor
