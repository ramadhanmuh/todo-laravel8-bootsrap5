$('input[name="id"]').val('xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
    var r = Math.random() * 16 | 0, 
        v = c == 'x' ? r : (r & 0x3 | 0x8);
    return v.toString(16);
}));

$('#submitButton').removeAttr('disabled');

$('#registerForm').submit(function (event) {
    event.preventDefault();

    $('#validationErrorMessageColumn').addClass('d-none');

    $('#loader').removeClass('d-none');

    var data = {
        id: $('input[name="id"]').val(),
        name: $('input[name="name"]').val(),
        username: $('input[name="username"]').val(),
        email: $('input[name="email"]').val(),
        password: $('input[name="password"]').val(),
        password_confirmation: $('input[name="password_confirmation"]').val(),
    };

    $.ajax({
        url: $('meta[name="base-url"]').attr('content') + '/register',
        type: 'POST',
        data: JSON.stringify(data),
        contentType: 'application/json',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (data, textStatus, jqXHR) {
            console.log(data);
            $('#loader').addClass('d-none');
        },
        error: function (jqXHR, textStatus, errorThrown) {
            // Validation error
            if (jqXHR.status === 422) {
                var errorHTML = '';

                for (error in jqXHR.responseJSON.errors) {
                    errorHTML += '<li>' + jqXHR.responseJSON.errors[error][0] + '</li>';
                }

                $('#validationErrorMessageList').html(errorHTML);

                $('#validationErrorMessageColumn').removeClass('d-none');

                var validationErrorMessageColumn = document.getElementById('validationErrorMessageColumn');
                var topPos = validationErrorMessageColumn.offsetTop;

                $('html').animate({
                    scrollTop: topPos
                });
            }

            $('#loader').addClass('d-none');
        }
    });
});