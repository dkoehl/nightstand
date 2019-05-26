//jQuery is required to run this code
$(document).ready(function () {
    /**
     * Salutes by daytime
     */
    function backgroundVideoSelectionByTime() {
        var windowWidth = $(window).width();
        var jetzt = new Date(),
            std = jetzt.getHours();
        var showVideo = true;
        if (std >= 6 && std <= 11) {
            var backgroundMedia = ['NYC-Traffic', 'Love-Coding', 'Training', 'Stone-Falls'];
        } else if (std >= 12 && std <= 14) {
            var backgroundMedia = ['Sky-High', 'Agua-natural', 'Coverr-beach2', 'Coverr-fish', 'Coverr-market-mcu', 'Flow']
        } else if (std >= 15 && std <= 19) {
            var backgroundMedia = ['Open-Fire', 'Dancing-Bulbs', 'Mystic-Beach', 'BnW']
        } else if (std >= 20 && std <= 23) {
            var backgroundMedia = ['Blurry-Lights', 'NYC-Blurred-Traffic', 'Love-Coding', 'Winter-Grass']
        } else {
            var backgroundMedia = ['Sky-High', 'Agua-natural', 'Coverr-beach2', 'Coverr-fish', 'Coverr-market-mcu', 'Flow'];
            showVideo = false;
        }
        var randomMediaItem = backgroundMedia[Math.floor(Math.random() * backgroundMedia.length)];

        // shows video
        if (showVideo === true){
            // Return an fullscreen video background
            $("#bgVideo").fadeOut("slow", function () {
                $('#bgVideo')
                    .replaceWith(
                        '<video preload="auto" autoplay="true" loop="loop" muted="muted" volume="0" autobuffer="1" class="fillWidth" id="bgVideo">' +
                        '<source src="./assets/videos/' + randomMediaItem + '/' + randomMediaItem + '.mp4" type="video/mp4"/>' +
                        '<source src="./assets/videos/' + randomMediaItem + '/' + randomMediaItem + '.webm" type="video/webm"/>' +
                        '</video>'
                    )
                    .attr(
                        'poster', './assets/videos/' + randomMediaItem + '/' + randomMediaItem + '.jpg'
                    );
            });
            var vid = document.getElementById("bgVideo");
            vid.playbackRate = 0.5
        }
        if (showVideo === false || windowWidth < 750){
            // Shows background image
            $('body').css('background-image', 'url(assets/videos/'+randomMediaItem+'/'+randomMediaItem+'.jpg)');
            $('body').css('background-size', 'cover');
            $('body').css('background-position', 'left');
            $('#bgVideo').remove();
        }
    }

    backgroundVideoSelectionByTime();
    /**
     * Interval for requesting new data
     */
    setInterval(function () {
        backgroundVideoSelectionByTime();
    }, 1000 * 60 * 60);
});
