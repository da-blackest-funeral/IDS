<div class="alert-info rounded border border-info w-50 p-3 position-relative">
    <i class="fa-solid fa-circle-info" style="font-size: 18px;"></i>
    <span style="font-weight: bold; margin-left: 5px;">Примечание</span>
    <div class="mt-1">
        @yield('info')
    </div>
    @if(!isset($cantClose))
        @include('components.close')
    @endif
</div>
