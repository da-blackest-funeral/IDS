<div class="position-absolute top-0 end-0" style="cursor: pointer;margin-top: -3px;">
    @if(!isset($closeText))
        <i class="fa fa-times" style="font-size: 17px" aria-hidden="true"
           onclick="$( this ).parent().parent().hide(400)"></i>
    @else
        <a href="#" class="btn link-info" onclick="$( this ).parent().parent().hide(400); $('#{{ $showId ?? 'show' }}').show(400)">{{ $closeText }}</a>
    @endif
</div>
