<?php

namespace Pixelmatic\Sbahnticker;

require '../vendor/autoload.php';

/**
 * Class SbahnTicker
 *
 * @package Pixelmatic\Sbahnticker
 */
class SbahnTicker
{
    public $rawSbahnHtml = '';
    const DATATICKER = 'https://img.srv2.de/customer/sbahnMuenchen/newsticker/newsticker.html';

    /**
     * @return string with json data
     */
    public static function getSbahnData()
    {
        $rawSbahnHtml = self::getSbahnDataFromWebsite();
        $sbahnDataArray = self::makeHtmlDataToJson($rawSbahnHtml);
        return json_encode($sbahnDataArray, JSON_UNESCAPED_UNICODE);
    }

    /**
     * Gets data from external website
     *
     * @return String with html structure
     */
    public static function getSbahnDataFromWebsite()
    {
        $htmlData = file_get_contents(self::DATATICKER);
        preg_match_all('/(<body)+(.*?)(<\/body>)/is', $htmlData, $matches);
        return $matches[0][0];
    }

    /**
     * @param $rawSbahnHtml String with data
     * @return array with data
     */
    public static function makeHtmlDataToJson($rawSbahnHtml)
    {
        $sbahn = new \DOMDocument();
        libxml_use_internal_errors(true);
        $sbahn->loadHTML(mb_convert_encoding($rawSbahnHtml, 'HTML-ENTITIES', 'UTF-8'));
        $sbahnXpath = new \DOMXPath($sbahn);

        $notificationHeadlines = [];
        foreach ($sbahn->getElementsByTagName('h1') as $headline) {
            $notificationHeadlines[] = $headline->nodeValue;
        }
        // if no notifications are available
        if (empty($notificationHeadlines)) {
            return [
                'Title' => 'Keine Meldungen vorhanden'
            ];
        }

        $notifications = [];
        foreach ($sbahnXpath->query('//div[@class="notification"]') as $notification) {
            $notifications[] = $notification->nodeValue;
        }

        $notificationTracks = [];
        foreach ($sbahnXpath->query('//div[@class="tracks"]') as $track) {
            $notificationTracks[] = $track->nodeValue;
        }
        $notificationsArray = [];
        for ($i = 0; $i < count($notificationTracks); $i++) {
            $notificationsArray[md5(self::sanitizeValues($notificationTracks[$i]))][] = [
                'Title' => self::sanitizeValues($notificationHeadlines[$i]),
                'Headline' => @trim(explode('Aktualisierung', self::sanitizeValues($notificationHeadlines[$i]))[1]),
                'Track' => self::sanitizeValues($notificationTracks[$i]),
                'Notification' => explode('Meldung:', self::sanitizeValues($notifications[$i]))[1]
            ];
        }
        return $notificationsArray;
    }

    /**
     * Cleans unneeded chars
     *
     * @param $rawSting String
     * @return string
     */
    public static function sanitizeValues($rawSting)
    {
        return trim(
            str_replace(
                [PHP_EOL, '  ', 'S '],
                ['', '', 'S'],
                $rawSting
            )
        );
    }
}

header('Content-Type: application/json; charset=utf-8');
$sbahnTickerData = new SbahnTicker();
echo $sbahnTickerData::getSbahnData();
