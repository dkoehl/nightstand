<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

class backgroundImage {


    /**
     * saves mood image from wetterspiegel.de
     */
    function init() {
        unlink('../images/newBackground.jpg');
        date_default_timezone_set('UTC');
        $timeStamp = time();
        $newTimeStamp = mktime(date('H',$timeStamp), date('i',$timeStamp), date('s',$timeStamp), date('d',$timeStamp), date('m',$timeStamp), date('Y', $timeStamp));
        $imageStream = file_get_contents('http://www5.wetterspiegel.de/ramos/remote/wettericon.php?breite=48.167&laenge=11.717&time=' . $newTimeStamp . '&nh=3&nm=2&nt=0&ww=2&ff=13&ff_schwelle=30&x=604&y=453');
        file_put_contents('../images/newBackground.jpg', $imageStream);
    }


}


$backgroundImages = new backgroundImage();
$backgroundImages->init();