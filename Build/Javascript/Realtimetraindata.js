//jQuery is required to run this code
$(document).ready(function () {
    /**
     * launch modals with weather data
     */
    $('#launchweather').click(function () {
        $.ajax({
            type: "GET",
            url: "src/RealTimeData.php",
            async: true,
            cache: false,
            success: function (data) {
                $('#realtimedata').html(data);
                var time = new Date(), h = time.getHours(), m = time.getMinutes();
                $('div.refreshCounter').html(time);
            }
        });
    });
});
