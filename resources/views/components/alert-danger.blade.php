@section('errors')
    @if($errors->any())
        <div class="m-xxl-3 pr-3 pt-3 pb-3 alert-danger border border-danger rounded w-100 h-100" id="error">
            @foreach($errors->all() as $error)
                <div class="pb-2 w-50" style="margin: 0 auto">
                    <span class="font-weight-bold">{{ $error }}</span>
                </div>
            @endforeach
        </div>
    @endif
@show
