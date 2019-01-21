<?php

namespace Pixelmatic;

require '../vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Symfony\Component\Debug\Debug;

/**
 * Class MvgTicker
 *
 * @package Pixelmatic\Ticker
 */
class UbahnTicker
{
    /**
     * @var string
     */
    protected static $tickerUrl = 'https://ticker.mvg.de/';

    /**
     * Gets XML Data from external API
     *
     * @return false|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function getUbahnData(): string
    {
        $client = new Client();
        try {
            $response = $client->request('GET', self::$tickerUrl);
        } catch (ClientException $e) {
            echo $e->getMessage() . PHP_EOL;
            return 'ERROR, Check Links und Request URLs';
        }
        $mvgXmlDoc = new \SimpleXMLElement($response->getBody());
        $mvgDataArray = [];
        foreach ($mvgXmlDoc->channel->item as $item) {
            $mvgDataArray[] = [
                'title' => strip_tags(trim($item->title)),
                'description' => self::trimBodytext($item->description),
                'pubdate' => strip_tags(trim($item->pubDate)),
                'tracks' => self::getTracks(trim($item->title))
            ];
        }
        return json_encode($mvgDataArray, JSON_UNESCAPED_UNICODE);
    }

    /**
     * Cleans bodytext from unneeded chars
     * @param $bodytext
     * @return string
     */
    public static function trimBodytext(string $bodytext): string
    {
        $newBodyText = explode('Für Detailinformationen folgen Sie bitte dem Link.', $bodytext);
        return strip_tags(trim($newBodyText[0]));
    }

    /**
     * Separates the Tracks for further stuff
     *
     * @param string $trackString
     * @return string
     */
    public static function getTracks(string $trackString): string
    {
        preg_match('/(Linien)(.*?)(:)/', $trackString, $matches);
        if (!empty($matches[2])) {
            return trim($matches[2]);
        }
        return '';
    }
}

header('Content-Type: application/json; charset=utf-8');
$mvgTickerData = new UbahnTicker();
echo $mvgTickerData::getUbahnData();
