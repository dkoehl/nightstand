//jQuery is required to run this code
$(document).ready(function () {

    $('#launchwebcamimages').click(function () {
        var timeStamp = Math.floor(Date.now());
        $('#webcamimage_east').attr('src', 'https://webcam.nausch.org/control/cam1.jpg?' + timeStamp);
        $('#webcamimage_west').attr('src', 'https://webcam.nausch.org/control/cam2.jpg?' + timeStamp);
        $('#webcamimage_north').attr('src', 'https://www.foto-webcam.eu/webcam/muenchen/current/720.jpg?' + timeStamp);
        $('#webcamimage_south').attr('src', 'https://www.foto-webcam.eu/webcam/freimann/current/720.jpg?' + timeStamp);
        var time = new Date(), h = time.getHours(), m = time.getMinutes();
        $('div.refreshCounter').html(time);
    });
});
