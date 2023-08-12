setTimeout(function() {
    $('#submitButton').removeAttr('disabled');
}, 100);

$('#resetPasswordForm').submit(function (event) {
    event.preventDefault();

    $('#validationErrorMessageList').html('');

    $('#validationErrorMessageColumn').addClass('d-none');

    $('#successAlertForm').addClass('d-none');

    $('#loader').removeClass('d-none');

    var data = {
        password: $('input[name="password"]').val(),
        password_confirmation: $('input[name="password_confirmation"]').val()
    };

    $.ajax({
        url: window.location.href + window.location.search,
        type: 'POST',
        data: JSON.stringify(data),
        contentType: 'application/json',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function () {
            $('#successAlertForm').html('Berhasil mengubah kata sandi. Kamu akan dialihkan ke halaman Login.');
            $('#successAlertForm').removeClass('d-none');
            
            $('#loader').addClass('d-none');

            // Kosongkan formulir
            $('input[name="password"]').val('');
            $('input[name="password_confirmation"]').val('');

            setTimeout(function () {
                var validationErrorMessageColumn = document.getElementById('successAlertForm');
                var topPos = validationErrorMessageColumn.offsetTop - 50;
    
                $('html').animate({
                    scrollTop: topPos
                });

                setTimeout(function () {
                    window.location.href = $('meta[name="base-url"]').attr('content') + '/login';    
                }, 2000);
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
                $('#validationErrorMessageList').html('<li>Gagal melakukan perubahan kata sandi.</li>');
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