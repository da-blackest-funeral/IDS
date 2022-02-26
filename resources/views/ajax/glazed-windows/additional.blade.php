@for($i = 1; $i <= $camerasCount; $i++)
    <div class="row mt-2">
        @if($i == 1)
            <div class="col-10 col-md-3">
                <div class="mt-2 h-75">
                    <label for="glass-width-{{ $i }}">{{ $i }}-стекло</label>
                    <select name="glass-width-{{ $i }}" id="glass-width-{{ $i }}" class="form-control">
                        <option value="0">Ширина стекла</option>
                        @foreach($glassWidth as $width)
                            <option value="{{ $width->id }}">{{ $width->name }}</option>
                        @endforeach
                    </select>
                    @foreach($additionalForGlass['selects'] as $select)
                        <div class="mt-1">
                            <select class="form-control" name="{{ $select->name . "-$i" }}">
                                @foreach($additionalForGlass['options'] as $additional)
                                    @if($select->name == $additional->name)
                                        <option value="{{ $additional->price }}">{{ $additional->value }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
        <div class="col-10 col-md-3 mt-1">
            <div class="mt-2 h-75">
                <label for="cameras-width-{{ $i }}">{{ $i }}-я камера</label>
                <select name="cameras-width-{{ $i }}" id="cameras-width-{{ $i }}" class="form-control">
                    <option value="0">Ширина камеры</option>
                    @foreach($camerasWidth as $width)
                        <option value="{{ $width->id }}">{{ $width->width }} мм.</option>
                    @endforeach
                </select>
{{--                @dump(compact('additionalForCameras'))--}}
                @foreach($additionalForCameras['selects'] as $select)
                    <div class="mt-1">
                        <select class="form-control" name="{{ $select->name . "-$i"}}">
                            @foreach($additionalForCameras['options'] as $additional)
                                @if($select->name == $additional->name)
                                    <option value="{{ $additional->price }}">{{ $additional->value }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="col-10 col-md-3">
            <div class="mt-2 h-75">
                <label for="glass-width-{{ $i + 1 }}">{{ $i + 1 }}-стекло</label>
                <select name="glass-width-{{ $i + 1 }}" id="glass-width-{{ $i + 1 }}" class="form-control">
                    <option value="0">Ширина стекла</option>
                    @foreach($glassWidth as $width)
                        <option value="{{ $width->id }}">{{ $width->name }}</option>
                    @endforeach
                </select>
                @foreach($additionalForGlass['selects'] as $select)
                    <div class="mt-1">
                        <select class="form-control" name="{{ $select->name . "-" . $i + 1 }}">
                            @foreach($additionalForGlass['options'] as $additional)
                                @if($select->name == $additional->name)
                                    <option value="{{ $additional->price }}">{{ $additional->value }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endfor
<div class="row mb-4">
    @include('components.calculations.glazed-window-installation')
    @include('components.calculations.coefficient-difficult')
    @include('components.calculations.additional-mounting-tools')
</div>
<div class="row mt-1">
    @include('components.calculations.takeaway')
    @include('components.calculations.fast-creating')
</div>
@include('components.calculations.comment')
@include('components.calculations.submit')
