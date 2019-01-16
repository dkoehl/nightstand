//jQuery is required to run this code
$(document).ready(function () {

    /**
     * Salutes by daytime
     */
    function backgroundVideoSelectionByTime() {


        var windowWidth = $(window).width();
        if (windowWidth < 750) {
            $.ajax({
                type: "GET",
                url: "src/MobileBackgroundImage.php",
                async: true,
                cache: false,
                success: function (data) {
                    $('body').css('background-image', 'url(assets/images/newBackground.jpg)');
                    $('body').css('background-size', 'cover');
                    $('body').css('background-position', 'left');
                    $('#bgVideo').remove();
                }
            });
        } else {
            var jetzt = new Date(),
                std = jetzt.getHours();

            if (std >= 6 && std <= 11) {
                var movies = ['NYC-Traffic', 'Love-Coding', 'Training', 'Stone-Falls']
            } else if (std >= 12 && std <= 14) {
                var movies = ['Sky-High', 'Agua-natural', 'Coverr-beach2', 'Coverr-fish', 'Coverr-market-mcu', 'Flow']
            } else if (std >= 15 && std <= 19) {
                var movies = ['Open-Fire', 'Dancing-Bulbs', 'Mystic-Beach', 'BnW']
            } else if (std >= 20 && std <= 23) {
                var movies = ['Blurry-Lights', 'NYC-Blurred-Traffic', 'Love-Coding', 'Winter-Grass']
            }else  {
                $('body').css('background-image', 'url(assets/images/newBackground.jpg)');
                $('body').css('background-size', 'cover');
                $('body').css('background-position', 'left');
                $('#bgVideo').remove();
            }
            if (movies){
                var videoitem = movies[Math.floor(Math.random() * movies.length)];
                // Return an fullscreen video background
                $("#bgVideo").fadeOut("slow", function () {
                    $('#bgVideo').replaceWith('<video preload="auto" autoplay="true" loop="loop" muted="muted" volume="0" autobuffer="1" class="fillWidth" id="bgVideo"><source src="./assets/videos/' + videoitem + '/' + videoitem + '.mp4" type="video/mp4"/><source src="./assets/videos/' + videoitem + '/' + videoitem + '.webm" type="video/webm"/></video>');
                    $('#bgVideo').attr('poster', './assets/videos/' + videoitem + '/' + videoitem + '.jpg');
                });
                var vid = document.getElementById("bgVideo");
                vid.playbackRate = 0.5
            }
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
