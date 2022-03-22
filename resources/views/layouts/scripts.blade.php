<script src="{{ mix('js/app.js') }}"></script>
<script>
    function getConfiguration(url, id, selectorIds = null, element = null) {
        let elementToLoad = $('#' + id);
        let data = {
            categoryId: $('#categories').find('option:selected').val(),
        }

        if (selectorIds) {
            data.additional = $('#' + selectorIds).find('option:selected').val();
        }
        if (element) {
            data.nextAdditional = $('#' + element).find('option:selected').val();
        }

        $.ajax({
            type: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: url,
            data: data,
            success: function (data) {
                $('#additional').hide();
                elementToLoad.html(data)
                elementToLoad.show();
            }
        });
    }
</script>
