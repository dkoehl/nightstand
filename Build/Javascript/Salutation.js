//jQuery is required to run this code
$(document).ready(function () {
    function salutation() {
        var jetzt = new Date(),
            std = jetzt.getHours();
        if (std >= 6 && std <= 11) {
            var text = 'Guten Morgen!';
            // var quotefile = 'Build/Javascript/quotes_japanese.txt';
        } else if (std >= 12 && std <= 18) {
            var text = 'Guten Tag!';
            // var quotefile = 'Build/Javascript/quotes_funny.txt';
        } else if (std > 18 && std <= 21) {
            var text = 'Guten Abend!';
            // var quotefile = 'Build/Javascript/quotes.txt';
        } else if (std >= 21 && std <= 23) {
            var text = 'Zeit, ins Bett zu gehen!';
            // var quotefile = 'Build/Javascript/quotes_teacher.txt';
        } else {
            var text = 'Sleepmode';
        }
        var lines = [''];

        $.get('assets/Quotes/quotes.txt', function (data) {
            lines = data.split("\n");
            var randomValue = Math.floor(Math.random() * lines.length);
            quote = lines[randomValue];
            $('#salutation').html(text + '<p>' + quote + '<p><br>');
        }, 'text');
    }

    salutation();

    setInterval(function () {
        salutation();
    }, 1000 * 60 * 5);
});
