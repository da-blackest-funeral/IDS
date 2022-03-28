@section('success')
    @if(session()->has('success'))
        <div class="cards-success m-xxl-3 pr-3 pt-3 pb-3 alert-success rounded w-100 h-100" id="success"
             onclick="$(this).parent().hide(500);">
            @foreach(session()->pull('success') as $message)
                <div class="pb-2 w-50" style="margin: 0 auto">
                    <span class="font-weight-bold">{{ $message }}</span>
                </div>
            @endforeach
        </div>
    @endif
@show
