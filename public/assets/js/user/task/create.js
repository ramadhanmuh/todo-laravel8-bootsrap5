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

	var newDate = new Date(dateTime);

	var UTCString = newDate.toUTCString();

	var unix = new Date(UTCString).getTime() / 1000;

	// console.log(newDate.toISOString())

	// var utcDate = new Date(
    //     Date.UTC(
    //       newDate.getUTCFullYear(),
    //       newDate.getUTCMonth(),
    //       newDate.getUTCDate(),
    //       newDate.getUTCHours(),
    //       newDate.getUTCMinutes(),
    //       newDate.getUTCSeconds()
    //     )
	// );

	console.log(unix);
	console.log(new Date(unix * 1000).toLocaleDateString('id-ID'));
	console.log(new Date(unix * 1000).getHours());

	// newDate = newDate.getUTCSeconds();

	// $('input[name="start_time"]').val(new Date(dateTime).getTime());
	
	// console.log(newDate);
	// console.log(new Date(newDate).toLocaleDateString('id-ID'));

});