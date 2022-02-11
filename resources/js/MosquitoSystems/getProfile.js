$('#tissues').change(function () {
    let id = $(this).find("option:selected").val();
    $.ajax({
        type: 'GET',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: '/ajax/mosquito-systems/profile',
        data: {
            tissueId: id
        },
        success: function (data) {
            console.log(data)
            $('#load').html(data)
        }
    });
});
