//jQuery is required to run this code
$(document).ready(function () {

    /**
     * button for refreshing displayed data
     */
    $('#refreshButton').click(function () {
        location.reload();
    });
    /**
     * Sets latest requestTime
     */
    function setTime() {
        var time = new Date(), h = time.getHours(), m = time.getMinutes();
        $('div.refreshCounter').html(time);
    }
});
