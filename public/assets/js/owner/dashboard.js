if ($(window).width() < 768) {
    $('canvas').css('height', '100px');
} else {
    $('canvas').attr('height', '100');
}

var timezone = '',
    totalTasksPerHour = false,
    totalTasksDaily = false;

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
            date: date,
            timezone: timezone
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

function createTasksPerHourChart(labels, data) {
    if (totalTasksPerHour) {
        totalTasksPerHour.destroy();
    }

    totalTasksPerHour = new Chart(document.getElementById('totalTasksPerHour'), {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Number of tasks',
                data: data,
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}

function createTasksDailyChart(labels, data) {
    if (totalTasksDaily) {
        totalTasksDaily.destroy();
    }

    totalTasksDaily = new Chart(document.getElementById('totalTasksDaily'), {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Number of tasks',
                data: data,
                borderWidth: 1
            }]
        },
        options: {
            locale: 'id-ID',
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}

function getDataTasksPerHour(totalTasksPerHourURL, date, callback) {
    getData(totalTasksPerHourURL, date, function (result) {
        var labels = [],
            data = [];

        if (result) {
            result.forEach(function(value, index, array) {
                if ($(window).width() < 768) {
                    labels.push(value.startTime);
                } else {
                    labels.push(value.startTime + '-' + value.endTime);
                }

                data.push(value.total);
            });
        } else {
            for (var index = 0; index < 4; index++) {
                labels[index] = 'Gagal';
                data[index] = 0;                
            }
        }

        callback(labels, data);
    });
}

function getDataTasksDaily(url, date, callback) {
    getData(url, date, function (result) {
        var data = [],
            labels = [];

        if (result) {
            labels.push('Senin');
            labels.push('Selasa');
            labels.push('Rabu');
            labels.push('Kamis');
            labels.push('Jumat');
            labels.push('Sabtu');
            labels.push('Minggu');

            result.forEach(function(value, index, array) {
                data.push(value);
            });
        } else {
            for (var index = 0; index < 7; index++) {
                labels.push('Gagal');
                data.push(0);               
            }
        }

        callback(labels, data);
    });
}

function buildTaskChart(totalTasksPerHourURL, totalTasksDailyURL, date) {
    getDataTasksPerHour(totalTasksPerHourURL, date, function (labels, data) {
        createTasksPerHourChart(labels, data);

        getDataTasksDaily(totalTasksDailyURL, date, function (labels, data) {
            createTasksDailyChart(labels, data);
        });
    });
}

createTasksPerHourChart(['', '', '', ''], [0, 0, 0, 0]);
createTasksDailyChart(['', '', '', '', '', '', ''], [0, 0, 0, 0, 0, 0, 0]);

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

    $('.date-input').val(todayDate);

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
    var totalTasksPerHourURL = baseURL + 'owner/dashboard/total-tasks-per-hour';
    var totalTasksDailyURL = baseURL + 'owner/dashboard/total-daily-tasks';

    getData('http://ip-api.com/json/', '', function (result) {
        timezone = result.timezone;

        buildFirstRow(totalUsersURL, totalAdministratorsURL, totalOwnersURL, totalTasksURL, todayDate);

        buildSecondRow(totalTasksTodayURL, totalTasksThisMonthURL, totalTasksThisYearURL, todayDate);

        buildTaskChart(totalTasksPerHourURL, totalTasksDailyURL, todayDate);
    });

    $('#totalTasksPerHourForm').submit(function (event) {
        event.preventDefault();

        getDataTasksPerHour(totalTasksPerHourURL, $('#totalTasksPerHourDate').val(), function (labels, data) {
            createTasksPerHourChart(labels, data);
        });
    });

    $('#totalTasksDailyForm').submit(function (event) {
        event.preventDefault();

        getDataTasksDaily(totalTasksDailyURL, $('#totalTasksDailyDate').val(), function (labels, data) {
            createTasksDailyChart(labels, data);
        });
    });
}, 100);
