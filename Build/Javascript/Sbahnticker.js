//jQuery is required to run this code
$(document).ready(function () {

    /**
     *
     * Initital requesting new SBahn Reports
     */
    function getSbahnReport() {
        var feedback = $.ajax({
            type: "GET",
            url: "src/Sbahnticker.php",
            async: true,
            cache: false,
            dataType: 'json',
            success: function (data) {
                var counter = 1;
                returnHTML = '';

                var time = new Date(), h = time.getHours(), m = time.getMinutes();
                if (data['Title'] == 'Keine Meldungen vorhanden') {
                    // no feed data
                    returnHTML = '<a href="#" class="list-group-item inneritem" data-toggle="collapse" data-target="#panel1' + counter + '">'
                        + '<h4 class="list-group-item-heading">Aktuell liegen uns keine Meldungen vor.</h4 class="list-group-item-heading">'
                        + '</a>';
                } else {
                    $.each(data, function (key, value) {
                        returnHTML = returnHTML + '<a href="#" class="list-group-item inneritem" data-toggle="collapse" data-target="#panel1' + counter + '">'
                            + '<sub>' + value[0]['Track'] + '</sub>'
                            + '<h4 class="list-group-item-heading">' + value[0]['Title'] + '</h4 class="list-group-item-heading">'
                            + '<p class="list-group-item-text  collapse" id="panel1' + counter + '">' + value[0]['Notification'] + '<br />'
                            + '</p>'
                            + '</a>';

                        counter++;
                    });
                }
                $('#sbahnliveticker').html(returnHTML);
            }
        });

    }
    getSbahnReport();
    /**
     * Interval for requesting new data
     */
    setInterval(function () {
        getSbahnReport();
    }, 1000 * 60 * 15);
});


