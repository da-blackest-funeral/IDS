<script src="{{ mix('js/app.js') }}"></script>
<script>

    $('input.form-control').on("invalid", function (event) {
        $(event.target).css('box-shadow', '2px 2px 10px 7px rgba(255, 100, 100, 0.2)');
        $(event.target).css('border', '4px solid lightpink');
        $(event.target).css('background-color', 'rgba(255, 100, 100, 0.15)');

        $(event.target).change(function () {
            $(event.target).css('box-shadow', 'none')
            $(event.target).css('border', '4px solid lightblue');
            $(event.target).css('background-color', 'rgba(100, 100, 255, 0.15)');
        });
    });

    function toggleDeliveryOptions() {
        let noDelivery = document.getElementById('no_delivery');
        let noMeasuring = document.getElementById('no_measuring');

        if (noDelivery.checked && noMeasuring.checked) {
            $('#delivery-options').hide(400)
        }
    }

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
                if (id !== 'additional') {
                    $('#additional').hide(350);
                } else {
                    $('#additional').hide()
                }
                elementToLoad.html(data)
                elementToLoad.show(350)
            }
        });
    }

    function addBracing() {
        $.ajax({
            type: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/bracing',
            success: function (data) {
                $('#bracing').append(data);
            },
        });
    }

</script>
