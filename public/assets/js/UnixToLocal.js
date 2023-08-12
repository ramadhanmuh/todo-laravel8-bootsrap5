$('.unix-value').each(function (key, value) {
    var unix_timestamp = parseInt($('.unix-value').eq(key).attr('data-unix'));

    if (!Number.isInteger(unix_timestamp)) {
        return;
    }

    // Create a new JavaScript Date object based on the timestamp
    // multiplied by 1000 so that the argument is in milliseconds, not seconds.
    var date = new Date(unix_timestamp * 1000);
    
    var yearMonthDay = date.toLocaleDateString("id-ID").replace(/\//g, '-');

    var time = date.toLocaleTimeString("id-ID").replace(/\./g, ':');

    $(this).text(yearMonthDay + ' ' + time);
});