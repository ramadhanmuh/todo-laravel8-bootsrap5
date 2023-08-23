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