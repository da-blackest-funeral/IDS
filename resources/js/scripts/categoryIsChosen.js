$('#categories').change(function () {
    let id = $(this).find("option:selected").val();
    $.ajax({
        type: 'GET',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: '/ajax/get-items',
        data: {
            categoryId: id
        },
        success: function (data) {
            console.log(data);
            $('#third').hide();
            $('#fourth').hide();
            $('#additional').hide();
            $('#items').html(data);
        }
    })
});
