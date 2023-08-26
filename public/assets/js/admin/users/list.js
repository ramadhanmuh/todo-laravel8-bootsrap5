$('.unix-value-input').each(function (key, value) {
    var dataValue = $(this).attr('data-value');

    if (dataValue.length < 1) {
        return;
    }

    var dataValueInteger = parseInt(dataValue); 

    if (isNaN(dataValueInteger)) {
        var unix = 0;
    } else {
        var unix = dataValueInteger;
    }

    // Create a new JavaScript Date object based on the timestamp
    // multiplied by 1000 so that the argument is in milliseconds, not seconds.
    var date = new Date(unix * 1000);
    
    var dayMonthYear = date.toLocaleDateString("id-ID").split('/');

    var year = dayMonthYear[2];
    var month = dayMonthYear[1].length < 2 ? '0' + dayMonthYear[1] : dayMonthYear[1];
    var day = dayMonthYear[0].length < 2 ? '0' + dayMonthYear[0] : dayMonthYear[0];

    $(this).val(year + '-' + month + '-' + day);
});

$('.unix-value-input').change(function () {
	var date = $(this).val();

    var idAttr = $(this).attr('id');

	if (date.length < 1) {
        $('input[name="' + idAttr + '"]').val('');
		return;
	}

	var dateTime = date;

    if (idAttr === 'start_date_created') {
        dateTime += ' 00:00:00';
    } else {
        dateTime += ' 23:59:59';
    }

	var newDate = new Date(dateTime);

    var value = Math.floor(newDate.getTime() / 1000);

    $('input[name="' + idAttr + '"]').val(value);
});