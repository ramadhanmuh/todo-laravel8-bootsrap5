$('#submitButton').removeAttr('disabled');

$('#registerForm').submit(function (event) {
    event.preventDefault();
    $('#loader').removeClass('d-none');

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
});