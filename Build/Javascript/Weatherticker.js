//jQuery is required to run this code
$(document).ready(function () {
    /**
     * Requesting Weatherdata form Yahoo API
     */
    function getWeatherdata() {
        var feedback = $.ajax({
            type: "GET",
            url: "src/WeatherTicker.php",
            async: true,
            cache: false,
            dataType: 'json',
            success: function (data) {
                var returnHTML = '<div class="col-12" id="temp"><img src="https://openweathermap.org/img/w/' + feedback.responseJSON['WetterIcon'] + '.png" class="img-responsive" id="weathericon" /> ' + Math.round(feedback.responseJSON['Temperatur']) + ' &deg;C </div>'
                    + '<div class="col-12" id="currently">' + feedback.responseJSON['Wetter'] + '</div>';
                $('#forcast').html(returnHTML);
            }
        });
    }
    getWeatherdata();
    /**
     * Interval for requesting new data
     */
    setInterval(function () {
        getWeatherdata();
    }, 1000 * 60 * 30);
});
