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
    protected static $mobileBackgroundImage;
    protected static $localMobileBackgroundImageWithPath;

    public function __construct()
    {
        self::$localMobileBackgroundImageWithPath = str_replace(
            '/src',
            '',
            __DIR__
        ) . '/assets/images/newBackground.jpg';
    }


    public static function getMobileBackgroundImage(): void
    {
        self::removeOldMobileBackgroundImage();
        self::getMobileBackgroundImageFromSource();
        self::saveMobileBackgroundImage();
    }

    /**
     * Removes old mobile background image
     */
    public static function removeOldMobileBackgroundImage(): void
    {
        if (is_file(self::$localMobileBackgroundImageWithPath)) {
            unlink(self::$localMobileBackgroundImageWithPath);
        }
    }

    /**
     *  Requests background image form external source
     */
    public static function getMobileBackgroundImageFromSource(): void
    {
        $timeStamp = time();
        $newTimeStamp = mktime(
            // hours
            date(
                'H',
                $timeStamp
            ),
            // minutes
            date(
                'i',
                $timeStamp
            ),
            // seconds
            date(
                's',
                $timeStamp
            ),
            // day
            date(
                'd',
                $timeStamp
            ),
            // month
            date(
                'm',
                $timeStamp
            ),
            // year
            date(
                'Y',
                $timeStamp
            )
        );
        $backgroundImageUrl = 'http://www5.wetterspiegel.de/';
        $backgroundImageUrl .= 'ramos/remote/wettericon.php?breite=48.167&laenge=11.717&time=';
        $backgroundImageUrl .= $newTimeStamp;
        $backgroundImageUrl .= '&nh=3&nm=2&nt=0&ww=2&ff=13&ff_schwelle=30&x=604&y=453';

        self::$mobileBackgroundImage = file_get_contents($backgroundImageUrl);
    }

    /**
     * @return bool
     */
    public static function saveMobileBackgroundImage(): bool
    {
        if (!empty(self::$mobileBackgroundImage)) {
            @file_put_contents(self::$localMobileBackgroundImageWithPath, self::$mobileBackgroundImage);
            return true;
        }
        return false;
    }
}

$image = new MobileBackgroundImage();
$image::getMobileBackgroundImage();
