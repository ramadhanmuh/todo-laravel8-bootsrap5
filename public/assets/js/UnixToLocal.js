setTimeout(function() {
    $('.unix-value').each(function (key, value) {
        var unix_timestamp = parseInt($('.unix-value').eq(key).attr('data-unix'));
    
        if (!Number.isInteger(unix_timestamp)) {
            return;
        }
    
        // Create a new JavaScript Date object based on the timestamp
        // multiplied by 1000 so that the argument is in milliseconds, not seconds.
        var date = new Date(unix_timestamp * 1000);
        
        var dayMonthYear = date.toLocaleDateString("id-ID").split('/');

        if (dayMonthYear[1].length < 2) {
            dayMonthYear[1] = '0' + dayMonthYear[1];
        }

        if (dayMonthYear[0].length < 2) {
            dayMonthYear[0] = '0' + dayMonthYear[0];
        }

        var yearMonthDay = dayMonthYear[2] + '-' + dayMonthYear[1] + '-' + dayMonthYear[0];
    
        var time = date.toLocaleTimeString("id-ID").replace(/\./g, ':');
    
        $(this).text(yearMonthDay + ' ' + time);
    });
}, 100);