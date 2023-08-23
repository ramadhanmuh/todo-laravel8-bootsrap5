var loginSuccessURL = '';

setTimeout(function() {
    loginSuccessURL += $('meta[name="base-url"]').attr('content');

    if (loginSuccessURL.substring(loginSuccessURL.length - 1) === '/') {
        loginSuccessURL += 'admin/dashboard';
    } else {
        loginSuccessURL += '/admin/dashboard';
    }

    $('#submitButton').removeAttr('disabled');
}, 100);

$('#loginForm').submit(function (event) {
    event.preventDefault();

    $('#validationErrorMessageList').html('');

    $('#validationErrorMessageColumn').addClass('d-none');

    $('#successAlertForm').addClass('d-none');

    $('#loader').removeClass('d-none');

    var data = {
        identity: $('input[name="identity"]').val(),
        password: $('input[name="password"]').val(),
        remember_me: $('input[name="remember_me"]').is(':checked') ? $('input[name="remember_me"]').val() : ''
    };

    $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        data: JSON.stringify(data),
        contentType: 'application/json',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (data, textStatus, jqXHR) {
            $('#successAlertForm').html('Berhasil melakukan login. Silahkan tunggu beberapa saat lagi.');
            $('#successAlertForm').removeClass('d-none');
            
            $('#loader').addClass('d-none');

            // Kosongkan formulir
            $('input[name="password"]').val('');
            $('input[name="identity"]').val('');
            $('input[name="remember_me"]').prop('checked', false);

            setTimeout(function () {
                var validationErrorMessageColumn = document.getElementById('successAlertForm');
                var topPos = validationErrorMessageColumn.offsetTop - 50;
    
                $('html').animate({
                    scrollTop: topPos
                });

                setTimeout(function () {
                    window.location.href = loginSuccessURL;    
                }, 1000);
            }, 200);

        },
        error: function (jqXHR) {
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

            if (jqXHR.status === 419) {
                $('#validationErrorMessageList').html('<li>Halaman kadaluarsa.</li>');
                location.reload();
            }

            if (jqXHR.status !== 422 && jqXHR.status !== 429 && jqXHR.status !== 419) {
                $('#validationErrorMessageList').html('<li>Gagal melakukan login.</li>');
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