function setID() {
    $('input[name="id"]').val('xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
        var r = Math.random() * 16 | 0, 
            v = c == 'x' ? r : (r & 0x3 | 0x8);
        return v.toString(16);
    }));
}

setTimeout(function() {
    setID();

    $('#submitButton').removeAttr('disabled');
}, 100);

$('#loginForm').submit(function (event) {
    event.preventDefault();

    $('#validationErrorMessageList').html('');

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
            $('#successAlertForm').html('Berhasil melakukan pendaftaran. Silahkan periksa email kamu untuk mengaktifkan akun.');
            $('#successAlertForm').removeClass('d-none');
            
            $('#loader').addClass('d-none');

            setID();

            // Kosongkan formulir
            $('input[name="name"]').val('');
            $('input[name="username"]').val('');
            $('input[name="email"]').val('');
            $('input[name="password"]').val('');
            $('input[name="password_confirmation"]').val('');

            setTimeout(function () {
                var validationErrorMessageColumn = document.getElementById('successAlertForm');
                var topPos = validationErrorMessageColumn.offsetTop - 50;
    
                $('html').animate({
                    scrollTop: topPos
                });
            }, 200);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            // Validation error
            if (jqXHR.status === 422) {
                var errorHTML = '';

                for (error in jqXHR.responseJSON.errors) {
                    errorHTML += '<li>' + jqXHR.responseJSON.errors[error][0] + '</li>';
                }

                $('#validationErrorMessageList').html(errorHTML);
            }

            // Too Many Request
            if (jqXHR.status === 429) {
                $('#validationErrorMessageList').html('<li>Terlalu banyak melakukan permintaan. Silahkan tunggu beberapa saat lagi.</li>');
            }

            if (jqXHR.status !== 422 && jqXHR.status !== 429) {
                $('#validationErrorMessageList').html('<li>Gagal melakukan pendaftaran.</li>');
            }

            $('#validationErrorMessageColumn').removeClass('d-none');

            $('#loader').addClass('d-none');

            setTimeout(function () {
                var validationErrorMessageColumn = document.getElementById('validationErrorMessageColumn');
                var topPos = validationErrorMessageColumn.offsetTop - 50;
    
                $('html').animate({
                    scrollTop: topPos
                });
            }, 200);
        }
    });
});