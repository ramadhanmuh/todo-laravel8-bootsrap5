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

function getData(url, date, callback) {
    $.ajax({
        url: url,
        data: {
            date: date
        },
        contentType: 'application/json',
        success: function (data) {
            callback(data);
        },
        error: function () {
            callback(false);
        }
    });
}

function buildFirstRow(totalUsersURL, totalAdministratorsURL, totalOwnersURL, totalTasksURL, todayDate) {
    getData(totalUsersURL, todayDate, function (result) {
        if (result) {
            $('#totalUsers').text(priceFormat(result.total));
        } else {
            $('#totalUsers').text('Gagal');
            $('#totalUsers').addClass('text-danger');
        }

        getData(totalAdministratorsURL, todayDate, function (result) {
            if (result) {
                $('#totalAdministrators').text(priceFormat(result.total));
            } else {
                $('#totalAdministrators').text('Gagal');
                $('#totalAdministrators').addClass('text-danger');
            } 
            
            getData(totalOwnersURL, todayDate, function (result) {
                if (result) {
                    $('#totalOwners').text(priceFormat(result.total));
                } else {
                    $('#totalOwners').text('Gagal');
                    $('#totalOwners').addClass('text-danger');
                }
        
                getData(totalTasksURL, todayDate, function (result) {
                    if (result) {
                        $('#totalTasks').text(priceFormat(result.total));
                    } else {
                        $('#totalTasks').text('Gagal');
                        $('#totalTasks').addClass('text-danger');
                    }
                });
            });
        });    
    });
}

function buildSecondRow(totalTasksTodayURL, totalTasksThisMonthURL, totalTasksThisYearURL, todayDate) {
    getData(totalTasksTodayURL, todayDate, function (result) {
        if (result) {
            $('#totalTasksToday').text(priceFormat(result.total));
        } else {
            $('#totalTasksToday').text('Gagal');
            $('#totalTasksToday').addClass('text-danger');
        }

        getData(totalTasksThisMonthURL, todayDate, function (result) {
            console.log(result)
            if (result) {
                $('#totalTasksThisMonth').text(priceFormat(result.total));
            } else {
                $('#totalTasksThisMonth').text('Gagal');
                $('#totalTasksThisMonth').addClass('text-danger');
            } 
            
            getData(totalTasksThisYearURL, todayDate, function (result) {
                if (result) {
                    $('#totalTasksThisYear').text(priceFormat(result.total));
                } else {
                    $('#totalTasksThisYear').text('Gagal');
                    $('#totalTasksThisYear').addClass('text-danger');
                }
            });
        });    
    });
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

    if (baseURL.substring(baseURL.length - 1) !== '/') {
        baseURL += '/';
    }

    var totalTasksTodayURL = baseURL + 'owner/dashboard/total-tasks-today';
    var totalTasksThisMonthURL = baseURL + 'owner/dashboard/total-tasks-this-month';
    var totalTasksThisYearURL = baseURL + 'owner/dashboard/total-tasks-this-year';
    var totalUsersURL = baseURL + 'owner/dashboard/total-users';
    var totalAdministratorsURL = baseURL + 'owner/dashboard/total-administrators';
    var totalOwnersURL = baseURL + 'owner/dashboard/total-owners';
    var totalTasksURL = baseURL + 'owner/dashboard/total-tasks';

    buildFirstRow(totalUsersURL, totalAdministratorsURL, totalOwnersURL, totalTasksURL, todayDate);

    buildSecondRow(totalTasksTodayURL, totalTasksThisMonthURL, totalTasksThisYearURL, todayDate);
}, 50);
