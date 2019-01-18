<?php
/**
 * Created by PhpStorm.
 * User: dkoehl
 * Date: 15.01.19
 * Time: 12:55
 */

namespace Pixelmatic;

/**
 * Class MobileBackgroundImage
 * Gets mobile background image from external source
 * @package Pixelmatic
 */
class MobileBackgroundImage
{
    public static $mobileBackgroundImage;
    public static $localMobileBackgroundImageWithPath;

    public function __construct()
    {
        self::$localMobileBackgroundImageWithPath = str_replace('/src', '',
                __DIR__) . '/assets/images/newBackground.jpg';

        self::removeOldMobileBackgroundImage();
        self::getMobileBackgroundImage();
        self::saveMobileBackgroundImage();
    }

    /**
     * Removes old mobile background image
     */
    public static function removeOldMobileBackgroundImage()
    {
        if (is_file(self::$localMobileBackgroundImageWithPath)) {
            unlink(self::$localMobileBackgroundImageWithPath);
        }
    }

    /**
     *  Requests background image form external source
     */
    public static function getMobileBackgroundImage()
    {
        echo 'get Image';
        $timeStamp = time();
        $newTimeStamp = mktime(date('H', $timeStamp), date('i', $timeStamp), date('s', $timeStamp),
            date('d', $timeStamp), date('m', $timeStamp), date('Y', $timeStamp));
        self::$mobileBackgroundImage = file_get_contents('http://www5.wetterspiegel.de/ramos/remote/wettericon.php?breite=48.167&laenge=11.717&time=' . $newTimeStamp . '&nh=3&nm=2&nt=0&ww=2&ff=13&ff_schwelle=30&x=604&y=453');

    }

    /**
     * @return bool|int
     */
    public static function saveMobileBackgroundImage()
    {
        if (!empty(self::$mobileBackgroundImage)) {
            return @file_put_contents(self::$localMobileBackgroundImageWithPath, self::$mobileBackgroundImage);
        }
    }


}

new MobileBackgroundImage();
