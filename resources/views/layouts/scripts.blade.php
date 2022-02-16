<script src="{{ asset('js/vendor.js') }}"></script>
<script src="{{ asset('js/manifest.js') }}"></script>
<script src="{{ asset('js/app.js') }}"></script>
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
                $('#' + id).html(data)
            }
        });
    }
</script>
<script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
<script src="https://canvasjs.com/assets/script/jquery.canvasjs.min.js"></script>
