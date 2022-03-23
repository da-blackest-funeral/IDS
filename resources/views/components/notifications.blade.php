<div id="notifications">
    @if(session()->has('notifications'))
        <div class="cards-notification m-xxl-3 pr-3 pt-3 pb-3 alert-info rounded w-100 h-100" id="notification">
            @foreach(session()->pull('notification') as $message)
                <div class="pb-2 w-50" style="margin: 0 auto">
                    <span class="font-weight-bold">{{ $message }}</span>
                </div>
            @endforeach
        </div>
    @endif
</div>
