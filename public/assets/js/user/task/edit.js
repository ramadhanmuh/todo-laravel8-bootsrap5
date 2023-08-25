function convertInputDateTimeToUNIX(dateTime) {
	var newDate = new Date(dateTime);

	var unixTimestamp = Math.floor(newDate.getTime() / 1000);

	return unixTimestamp;
}

ClassicEditor
    .create( document.getElementById( 'description' ), {
		// Editor configuration.
	} )
	.then( editor => {
		window.editor = editor;
	} );

$('.unix-date').each(function (key, value) {
    var dataValue = $('.unix-date').eq(key).attr('data-value');

    if (/^[0-9]*$/.test(dataValue)) {
        dataValue = parseInt(dataValue);

        if (!Number.isInteger(dataValue)) {
            return;
        }

        // Create a new JavaScript Date object based on the timestamp
        // multiplied by 1000 so that the argument is in milliseconds, not seconds.
        var date = new Date(dataValue * 1000);
        
        var dayMonthYear = date.toLocaleDateString("id-ID").replace(/\//g, '-');

        var dayMonthYearSplit = dayMonthYear.split('-');

        var year = dayMonthYearSplit[2];

        var month = dayMonthYearSplit[1].length < 2 ? '0' + dayMonthYearSplit[1] : dayMonthYearSplit[1];

        var day = dayMonthYearSplit[0].length < 2 ? '0' + dayMonthYearSplit[0] : dayMonthYearSplit[0];

        var yearMonthDay = year + '-' + month + '-' + day;

        $(this).val(yearMonthDay);
    } else {
        $(this).val(dataValue);
    }
});

$('.unix-time').each(function (key, value) {
    var dataValue = $('.unix-time').eq(key).attr('data-value');

    if (/^[0-9]*$/.test(dataValue)) {
        var dataValue = parseInt($('.unix-time').eq(key).attr('data-value'));
    
        // Create a new JavaScript Date object based on the timestamp
        // multiplied by 1000 so that the argument is in milliseconds, not seconds.
        var date = new Date(dataValue * 1000);
    
        var time = date.toLocaleTimeString('id-ID').replace(/\./g, ':').split(':');
    
        $(this).val(time[0] + ':' + time[1]);
    } else {
        $(this).val(dataValue);
    }
});

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