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

        $('div.datum').html(text);
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
        m = (minutes < 10 ? '0' : '' ) + minutes;
        s = (seconds < 10 ? '0' : '' ) + seconds;
        $('div.uhrzeit').html(hours + ':' + m + ':' + s);
    }

    /**
     * Salutes by daytime
     */
    function alarmclock_salute() {
        var jetzt = new Date(),
            std = jetzt.getHours();

        if (std >= 8 && std < 12) {
            //text = 'Guten Morgen!';
            var movies = ['NYC-Traffic', 'Love-Coding', 'Training', 'Stone-Falls']
        } else if (std >= 12 && std < 18) {
            //text = 'Guten Tag!';
            var movies = ['Sky-High', 'Agua-natural', 'Coverr-beach2', 'Coverr-fish', 'Coverr-market-mcu']
        } else if (std >= 18 && std <= 21) {
            //text = 'Guten Abend!';
            var movies = ['Open-Fire', 'Dancing-Bulbs', 'Mystic-Beach', 'BnW']
        } else if (std >= 21 && std < 8) {
            //text = 'Zeit, ins Bett zu gehen!';
            var movies = ['Blurry-Lights', 'NYC-Blurred-Traffic', 'Love-Coding']
        }else{
            console.log('error - saluteFunction no valid time')
            $('body').css('background-image', 'url(images/newBackground.jpg)');
            $('body').css('background-size', 'cover');
            $('#bgVideo').remove();
        }
        return movies;
        //$('div.anrede').html(text);
    }

    /**
     * Requesting Weatherdata form Yahoo API
     */
    function getWeatherdata() {
        var feedback = $.ajax({
            type: "GET",
            url: "functions/weatherticker.php",
            async: false,
            cache: false,
            dataType: 'json'
        });
        var returnHTML = '<div class="col-xs-6 col-sm-6 col-lg-6">'
            + '<i class="icon-' + feedback.responseJSON['icon'] + '"></i>'
            + '</div>'
            + '<div class="col-xs-6 col-sm-6 col-lg-6">'
            + '<span class="temp">' + feedback.responseJSON['temperatur'] + ' &deg;C </span>'
            + '<span class="currently">' + feedback.responseJSON['currently'] + '</span>'
            + '</div>';
        $('div.forcast').replaceWith('<div class="forcast">' + returnHTML + '</div>');
    }

    /**
     * Requesting Webcam Image
     */
    function getWebcamImage() {
        var timeStamp = Math.floor(Date.now());
        $('#webcamimage_east').attr('src', 'http://webcam.nausch.org/control/cam1.jpg?' + timeStamp);
        $('#webcamimage_west').attr('src', 'http://webcam.nausch.org/control/cam2.jpg?' + timeStamp);
        $('#webcamimage_north').attr('src', 'https://www.foto-webcam.eu/webcam/muenchen/current/720.jpg?' + timeStamp);
        $('#webcamimage_south').attr('src', 'https://www.foto-webcam.eu/webcam/freimann/current/720.jpg?' + timeStamp);
        setTime();
    }

    /**
     *
     * Initital requesting new SBahn Reports
     */
    function getSbahnReport() {
        var feedback = $.ajax({
            type: "GET",
            url: "functions/sbahnticker.php",
            async: false,
            cache: false,
            dataType: 'json'
        });
        var counter = 1;

        if (feedback.responseJSON.length > 0) {
            returnHTML = '';
            for (var i = 0; i < feedback.responseJSON.length; i++) {
                var returnHTML = returnHTML+'<a href="#" class="list-group-item inneritem" data-toggle="collapse" data-target="#panel0' + counter + '">'
                    + '<h4 class="list-group-item-heading">' + feedback.responseJSON[i]['headline'] + '</h4 class="list-group-item-heading">'
                    + '<p class="list-group-item-text  collapse" id="panel0' + counter + '">' + feedback.responseJSON[i]['description'] + '<br />'
                    + '<sub>' + feedback.responseJSON[i]['tracks'] + '</p>'
                    + '</a>';
                counter++;
            }
            $('#sbahnliveticker').replaceWith('<div id="sbahnliveticker">' + returnHTML + '</div>');
        } else {
            // keine meldungen
            var time = new Date(), h = time.getHours(), m = time.getMinutes();
            var returnEmptyHTML = '<a href="#" class="list-group-item inneritem">'
                + '<h4 class="list-group-item-heading">' + feedback.responseJSON[0]['headline'] + '</h4 class="list-group-item-heading">'
                + '<small>' + time + '</small></a>';
            $('#sbahnliveticker').replaceWith('<div id="sbahnliveticker">' + returnEmptyHTML + '</div>');

        }
    }

    /**
     * MVG Ticker
     * Gets MVG Data
     */
    function getMvgReport() {
        var feedback = $.ajax({
            type: "GET",
            url: "functions/mvgticker.php",
            async: false,
            cache: false
        });
        if (feedback.responseJSON.length > 1) {


            var arr = feedback.responseJSON;
            var counter = 1;
            var returnHTML = '';
            for (var i = 0; i < feedback.responseJSON.length; i++) {

                var returnHTML = returnHTML+'<a href="#" class="list-group-item inneritem" data-toggle="collapse" data-target="#panel1' + counter + '">'
                    + '<h4 class="list-group-item-heading">' + feedback.responseJSON[i]['title'] + '</h4 class="list-group-item-heading">'
                    + '<p class="list-group-item-text  collapse" id="panel1' + counter + '">' + feedback.responseJSON[i]['description'].replace(/<\/?[^>]+(>|$)/g, "") + '<br />'
                    + '<sub>' + feedback.responseJSON[i]['pubdate'] + '</p>'
                    + '</a>';
                counter++;
            }
            $('#ubahnliveticker').replaceWith('<div id="ubahnliveticker">' + returnHTML + '</div>');

        } else {
            // keine meldungen
            var time = new Date(), h = time.getHours(), m = time.getMinutes();
            var returnEmptyHTML = '<a href="#" class="list-group-item inneritem">'
                + '<p">Aktuell liegen uns keine Meldungen vor.</p>'
                + '<small>' + time + '</small></a>';
            $('#ubahnliveticker').replaceWith('<div id="ubahnliveticker">' + returnEmptyHTML + '</div>');
        }
    }

    /**
     * Interval for Requesting new SBahn Reports
     */
    setInterval(function () {
        alarmclock_date();
        alarmclock_salute();
        getSbahnReport();
        getMvgReport();
        getWebcamImage();
        getWeatherdata();
        randomBackground();
    }, 1000 * 60 * 20);

    /**
     * Set timeinterval for refreshing the time
     * -> set for every second (1000ms=1sec)
     */
    setInterval(function () {
        alarmclock_time();
    }, 1000);

    /**
     * Sets a random background video/image
     */
    function randomBackground() {
        var videos = ['Agua-natural', 'Coverr-beach2', 'Coverr-fish', 'Coverr-market-mcu', 'Dancing-Bulbs', 'Flow', 'NYC-Blurred-Traffic', 'NYC-Traffic', 'Sky-High'];
        var videos = alarmclock_salute();
        var videoitem = videos[Math.floor(Math.random() * videos.length)];
        var windowWidth = $(window).width();
        if (windowWidth > 750) {
            /**
             * Return an fullscreen video background
             */
            $("#bgVideo").fadeOut("slow", function () {
                $('#bgVideo').replaceWith('<video preload="auto" autoplay="true" loop="loop" muted="muted" volume="0" autobuffer="1" class="fillWidth" id="bgVideo"><source src="videos/' + videoitem + '/' + videoitem + '.mp4" type="video/mp4"/><source src="videos/' + videoitem + '/' + videoitem + '.webm" type="video/webm"/></video>');
                $('#bgVideo').attr('poster', 'videos/' + videoitem + '/' + videoitem + '.jpg');
                scaleVideoContainer();
            });
            // Speed of background video
            var vid = document.getElementById("bgVideo");
            vid.playbackRate = 0.5
        } else {
            /**
             * Return just an background-image on mobile devices
             */
            //$('body').css('background-image', 'url(videos/' + videoitem + '/' + videoitem + '.jpg)');
            //$('#bgVideo').remove();
            var feedback = $.ajax({
                type: "GET",
                url: "functions/backgroundimage.php",
                async: false,
                cache: false
            });

            $('body').css('background-image', 'url(images/newBackground.jpg)');
            $('body').css('background-size', 'cover');
            $('body').css('background-position', 'left');
            $('#bgVideo').remove();
        }
    }

    /**
     * Sets latest requestTime
     */
    function setTime() {
        var time = new Date(), h = time.getHours(), m = time.getMinutes();
        $('div.refreshCounter').html(time);
    }

    /**
     * Video Background stuff
     */
    function scaleVideoContainer() {
        //$('#bgVideo').css('width', '110%');
    }

    $('#refreshButton').click(function () {
        location.reload();
    });

    $('#launchweather').click(function () {
        var timeStamp = Math.floor(Date.now());
        $('#weatherModalTemperatur').attr('src', 'http://wetter.nausch.org/tempday.png?' + timeStamp);
        $('#weatherModalBarometer').attr('src', 'http://wetter.nausch.org/baromday.png?' + timeStamp);
        $('#weatherModalRadiation').attr('src', 'http://wetter.nausch.org/radiationDay.png?' + timeStamp);
        $('#weatherModalUV').attr('src', 'http://wetter.nausch.org/UVDay.png?' + timeStamp);
    });

    alarmclock_date();
    alarmclock_salute();
    getSbahnReport();
    getMvgReport();
    getWebcamImage();
    getWeatherdata();
    randomBackground();
});
