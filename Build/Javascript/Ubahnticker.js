//jQuery is required to run this code
$(document).ready(function () {

    /**
     * MVG Ticker
     * Gets MVG Data
     */
    function getMvgReport() {
        var feedback = $.ajax({
            type: "GET",
            url: "src/UbahnTicker.php",
            async: true,
            cache: false,
            success: function (data) {
                if (data.length >= 1) {
                    var counter = 1;
                    var returnHTML = '';

                    for (var i = 0; i < data.length; i++) {
                        var returnHTML = returnHTML + '<a href="#" class="list-group-item inneritem" data-toggle="collapse" data-target="#panel0' + counter + '">'
                            + '<h4 class="list-group-item-heading">' + feedback.responseJSON[i]['title'] + '</h4 class="list-group-item-heading">'
                            + '<p class="list-group-item-text  collapse" id="panel0' + counter + '">' + feedback.responseJSON[i]['description'].replace(/<\/?[^>]+(>|$)/g, "") + '<br />'
                            + '<sub>' + feedback.responseJSON[i]['pubdate'] + '</p>'
                            + '</a>';
                        counter++;
                    }

                } else {
                    // keine meldungen
                    var time = new Date(), h = time.getHours(), m = time.getMinutes();
                    var returnHTML = '<a href="#" class="list-group-item inneritem" data-toggle="collapse" data-target="#panel0' + counter + '">'
                        + '<h4 class="list-group-item-heading">Aktuell liegen uns keine Meldungen vor.</h4 class="list-group-item-heading">'
                        + '</a>';
                }
                $('#ubahnliveticker').html(returnHTML);
            }
        });

    }

    getMvgReport();
    /**
     * Interval for requesting new data
     */
    setInterval(function () {
        getMvgReport();
    }, 1000 * 60 * 15);
});


