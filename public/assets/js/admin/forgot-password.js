setTimeout(function() {
    $('#submitButton').removeAttr('disabled');
}, 100);

$('#forgotPasswordForm').submit(function (event) {
    event.preventDefault();

    $('#validationErrorMessageList').html('');

    $('#validationErrorMessageColumn').addClass('d-none');

    $('#successAlertForm').addClass('d-none');

    $('#loader').removeClass('d-none');

    var data = {
        email: $('input[name="email"]').val()
    };

    $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        data: JSON.stringify(data),
        contentType: 'application/json',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function () {
            $('#successAlertForm').html('Berhasil mengirim tautan untuk atur ulang kata sandi. Silahkan periksa email kamu.');
            $('#successAlertForm').removeClass('d-none');
            
            $('#loader').addClass('d-none');

            // Kosongkan formulir
            $('input[name="email"]').val('');

            setTimeout(function () {
                var validationErrorMessageColumn = document.getElementById('successAlertForm');
                var topPos = validationErrorMessageColumn.offsetTop - 50;
    
                $('html').animate({
                    scrollTop: topPos
                });
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
                $('#validationErrorMessageList').html('<li>Gagal melakukan pengiriman tautan.</li>');
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