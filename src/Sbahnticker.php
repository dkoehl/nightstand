<?php

namespace Pixelmatic;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

require '../vendor/autoload.php';

/**
 * Class SbahnTicker
 *
 * @package Pixelmatic\Sbahnticker
 */
class SbahnTicker
{
    protected static $serviceUrl = 'https://img.srv2.de/customer/sbahnMuenchen/newsticker/newsticker.html';

    /**
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function getSbahnData():string
    {
        $rawSbahnHtml = self::parseSbahnWebsite();
        $sbahnDataArray = self::makeHtmlDataToJson($rawSbahnHtml);
        return json_encode($sbahnDataArray, JSON_UNESCAPED_UNICODE);
    }

    /**
     *  Gets data from external website
     *
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function parseSbahnWebsite():string
    {
        $client = new Client();
        try {
            $response = $client->request('GET', self::$serviceUrl);
        } catch (ClientException $e) {
            echo $e->getMessage() . PHP_EOL;
            return 'ERROR, Check Links und Request URLs';
        }
        if ($response->getStatusCode() === 200) {
            preg_match_all('/(<body)+(.*?)(<\/body>)/is', $response->getBody(), $matches);
            return $matches[0][0];
        }
    }

    /**
     * @param string $rawSbahnHtml
     * @return array
     */
    public static function makeHtmlDataToJson(string $rawSbahnHtml):array
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
        // get all notifications no matter archived or not
        $notifications = [];
        foreach ($sbahnXpath->query('//div[@class="notification"]') as $notification) {
            $notifications[] = $notification->nodeValue;
        }
        // get all tracks no matter archived or not
        $notificationTracks = [];
        foreach ($sbahnXpath->query('//div[@class="tracks"]') as $track) {
            $notificationTracks[] = $track->nodeValue;
        }
        $notificationsArray = [];
        $notifcicationCounts = count($notificationTracks);
        for ($i = 0; $i < $notifcicationCounts; $i++) {
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
    public static function sanitizeValues(string $rawSting):string
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
