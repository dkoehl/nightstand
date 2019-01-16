//jQuery is required to run this code
$(document).ready(function () {
    /**
     * Requesting Weatherdata form Yahoo API
     */
    function getWeatherdata() {
        var feedback = $.ajax({
            type: "GET",
            url: "src/Weatherticker.php",
            async: true,
            cache: false,
            dataType: 'json',
            success: function (data) {
                var returnHTML = '<div class="col-4">'
                    + '<div id="weathericon"><center><img src="https://openweathermap.org/img/w/' + feedback.responseJSON['WetterIcon'] + '.png" class="img-responsive"></center></div>'
                    + '</div>'
                    + '<div class="col-8" id="weathertext">'
                    + '<div id="temp">' + Math.round(feedback.responseJSON['Temperatur']) + ' &deg;C </div>'
                    + '<div id="currently">' + feedback.responseJSON['Wetter'] + '</div>'
                    + '</div>';
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
