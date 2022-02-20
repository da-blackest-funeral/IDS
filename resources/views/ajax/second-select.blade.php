<div id="select-wrapper">
    <select name="{{ $name }}"
            id="{{ $name }}"
            onchange="getConfiguration('{{ $link }}', '{{ $name }}', '{{ $name }}')"
            class="form-control">
        <option>Выберите</option>
        @forelse($data as $item)
            <option value="{{ $item->id }}">{{ $item->name }}</option>
        @empty
        @endforelse
    </select>
    <script>
        function getConfiguration(url, id, selectorId = false) {
            let data = {
                categoryId: $('#categories').find('option:selected').val(),
            }
            if (selectorId) {
                data.additional = $('#' + selectorId).find('option:selected').val();
            }
            $.ajax({
                type: 'GET',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: url,
                data: data,
                success: function (data) {
                    console.log(data)
                    $('#' + id).append(data)
                }
            });
        }
    </script>
</div>
