//jQuery is required to run this code
$(document).ready(function () {

    /**
     * Initial clock
     */
    function alarmclock_date() {
        var now = new Date(),
            date = now.getDate(),
            dayCount = now.getDay(),
            weekday = ['Sonntag', 'Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag'],
            monthCount = now.getMonth(),
            month = ['Januar', 'Februar', 'März', 'April', 'Mai', 'Juni', 'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember'],
            year = now.getFullYear(),
            text;
        text = weekday[dayCount] + ' ' + date + '.' + month[monthCount] + ' ' + year;

        $('#datum').html(text);
    }
    /**
     * Set time
     */
    function alarmclock_time() {
        var now = new Date(),
            hours = now.getHours(),
            minutes = now.getMinutes(),
            seconds = now.getSeconds(),
            text;

        m = (minutes < 10 ? '0' : '') + minutes;
        s = (seconds < 10 ? '0' : '') + seconds;
        $('#uhrzeit').html(hours + ':' + m + ':' + s);
        if (hours == '0' && minutes == '0') {
            alarmclock_date();
        }
    }
    alarmclock_date();
    alarmclock_time();
    /**
     * Set timeinterval for refreshing the time
     * -> set for every second (1000ms=1sec)
     */
    setInterval(function () {
        alarmclock_time();
    }, 1000);

});
