function convertInputDateTimeToUNIX(dateTime) {
	var newDate = new Date(dateTime);

	var unixTimestamp = Math.floor(newDate.getTime() / 1000);

	return unixTimestamp;
}

$('input[name="id"]').val('xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
    var r = Math.random() * 16 | 0, 
        v = c == 'x' ? r : (r & 0x3 | 0x8);
    return v.toString(16);
}));

ClassicEditor
    .create( document.getElementById( 'description' ), {
		// Editor configuration.
	} )
	.then( editor => {
		window.editor = editor;
	} );

$('#start_date').change(function () {
	var date = $(this).val(),
		time = $('#start_time').val();

	if (date.length === 0) {
		$('input[name="start_time"]').val('');
		return;
	}

	if (time.length === 0) {
		time += '00:00:00';
	}

	var dateTime = date + ' ' + time;

	var unixTimestamp = convertInputDateTimeToUNIX(dateTime);

	$('input[name="start_time"]').val(unixTimestamp);
});

$('#start_time').change(function () {
	var time = $(this).val(),
		date = $('#start_date').val();

	if (time.length === 0 || date.length === 0) {
		$('input[name="start_time"]').val('');
		return;
	}

	var dateTime = date + ' ' + time + ':00';

	var unixTimestamp = convertInputDateTimeToUNIX(dateTime);

	$('input[name="start_time"]').val(unixTimestamp);
});

$('#end_date').change(function () {
	var date = $(this).val(),
		time = $('#end_time').val();

	if (date.length === 0) {
		$('input[name="end_time"]').val('');
		return;
	}

	if (time.length === 0) {
		time += '00:00:00';
	}

	var dateTime = date + ' ' + time;

	var unixTimestamp = convertInputDateTimeToUNIX(dateTime);

	$('input[name="end_time"]').val(unixTimestamp);
});

$('#end_time').change(function () {
	var time = $(this).val(),
		date = $('#end_date').val();

	if (time.length === 0 || date.length === 0) {
		$('input[name="end_time"]').val('');
		return;
	}

	var dateTime = date + ' ' + time + ':00';

	var unixTimestamp = convertInputDateTimeToUNIX(dateTime);

	$('input[name="end_time"]').val(unixTimestamp);
});