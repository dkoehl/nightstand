<?php

namespace Pixelmatic;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Psr\Http\Message\StreamInterface;

require '../vendor/autoload.php';

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
    protected static $api_url = 'https://ticker.mvg.de/';


    /**
     * @return StreamInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected static function requestData()
    {
        $client = new Client();
        $response = '';
        try {
            $response = $client->request('GET', self::$api_url);
        } catch (ClientException $e) {
            echo $e->getMessage() . PHP_EOL;
        }
        return $response->getBody();
    }

    /**
     * @param $responseBody
     *
     * @return array
     */
    protected static function parseHtmlToXml($responseBody)
    {
        $mvgXmlDoc = new \SimpleXMLElement($responseBody);
        $arrayItems = [];
        foreach ($mvgXmlDoc->channel->item as $item) {
            $arrayItems[] = [
                'title' => strip_tags(trim($item->title)),
                'description' => self::trimBodytext($item->description),
                'pubdate' => strip_tags(trim($item->pubDate)),
                'tracks' => self::getTracks(trim($item->title))
            ];
        }
        return $arrayItems;
    }

    /**
     * Gets XML Data from external API
     *
     * @return false|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function getUbahnData()
    {
        $responseBody = self::requestData();
        return json_encode(self::parseHtmlToXml($responseBody), JSON_UNESCAPED_UNICODE);
    }

    /**
     * Cleans bodytext from unneeded chars
     *
     * @param string $bodytext
     * @return string
     */
    public static function trimBodytext($bodytext)
    {
        $newBodyText = explode('Für Detailinformationen folgen Sie bitte dem Link.', $bodytext);
        return strip_tags(trim($newBodyText[0]));
    }

    /**
     * separates the Tracks for further stuff
     *
     * @param string $trackString
     * @return string
     */
    public static function getTracks($trackString)
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
