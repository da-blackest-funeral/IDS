<form action="{{ $action }}" method="post" id="delete">
    @method('delete')
    @csrf
    @include('components.submit-confirm-button')
</form>
