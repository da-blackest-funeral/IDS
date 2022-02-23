<script src="{{ mix('js/app.js') }}"></script>
<script>
    function getConfiguration(url, id, selectorId = false) {
        let elementToLoad = $('#' + id);
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
                console.log(id);
                elementToLoad.html(data)
                elementToLoad.show();
            }
        });
    }
</script>
