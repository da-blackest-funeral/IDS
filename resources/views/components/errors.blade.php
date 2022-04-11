@section('errors')
    @if($errors->any())
        <div class="cards-errors m-xxl-3 pr-3 pt-3 pb-3 alert-danger rounded w-100 h-100" id="error"
             onclick="$(this).parent().hide(500);">
            @foreach($errors->all() as $error)
                <div class="pb-2 w-50" style="margin: 0 auto">
                    <span class="font-weight-bold">{{ $error }}</span>
                </div>
            @endforeach
        </div>
    @endif
@show

@if(session()->has('warnings'))
    <div class="m-xxl-3 pr-3 pt-3 pb-3">
        @foreach(session()->pull('warnings') as $text)
            @include('components.alert-danger', compact('text'))
        @endforeach
    </div>
@endif
