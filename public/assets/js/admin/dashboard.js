function priceFormat(angka){
    var number_string = angka.toString().replace(/[^,\d]/g, '').toString(),
        split = number_string.split(','),
        sisa = split[0].length % 3,
        format = split[0].substr(0, sisa),
        ribuan = split[0].substr(sisa).match(/\d{3}/gi);

    // tambahkan titik jika yang di input sudah menjadi angka ribuan
    if(ribuan){
        separator = sisa ? '.' : '';
        format += separator + ribuan.join('.');
    }

    format = split[1] != undefined ? format + ',' + split[1] : format;

    return format;
}

setTimeout(function() {
    var todayDate = new Date().toLocaleDateString('id-ID');

    todayDateSplited = todayDate.split('/', 3);

    if (todayDateSplited[1].length < 2) {
        todayDateSplited[1] = '0' + todayDateSplited[1]
    }

    if (todayDateSplited[0].length < 2) {
        todayDateSplited[0] = '0' + todayDateSplited[1]
    }
    
    todayDate = todayDateSplited[2] + '-' + todayDateSplited[1] + '-' + todayDateSplited[0];

    var baseURL = $('meta[name="base-url"]').attr('content');
    
    var totalTasksDailyURL = '';
    
    var totalTasksMonthlyURL = '';
    
    if (baseURL.substring(baseURL.length - 1) === '/') {
        totalTasksDailyURL += baseURL + 'admin/dashboard/total-tasks-daily';
        totalTasksMonthlyURL += baseURL + 'admin/dashboard/total-tasks-monthly';
    } else {
        totalTasksDailyURL += baseURL + '/admin/dashboard/total-tasks-daily';
        totalTasksMonthlyURL += baseURL + '/admin/dashboard/total-tasks-monthly';
    }
    
    $.ajax({
        url: totalTasksDailyURL,
        data: JSON.stringify({date: todayDate}),
        contentType: 'application/json',
        success: function (data) {
            $('#dailyTotalTasks').text(priceFormat(data.total));

            $.ajax({
                url: totalTasksMonthlyURL,
                data: JSON.stringify({date: todayDate}),
                contentType: 'application/json',
                success: function (data) {
                    $('#monthlyTotalTasks').text(priceFormat(data.total));
                }
            });
        }
    });

}, 50);
