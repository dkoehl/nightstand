<?php

namespace Pixelmatic\Ticker;

/**
 * Class MvgTicker
 *
 * @package Pixelmatic\Ticker
 */
class MvgTicker
{
    const UBAHNDATAURL = 'https://ticker.mvg.de/';


    /**
     * Gets XML Data from external API
     * @return array
     */
    public static function getUbahnData()
    {
        $mvgreportpage = file_get_contents(self::UBAHNDATAURL);
        $mvgXmlDoc = new \SimpleXMLElement($mvgreportpage);
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
    public static function trimBodytext($bodytext)
    {
        $newBodyText = explode('Für Detailinformationen folgen Sie bitte dem Link.', $bodytext);
        return strip_tags(trim($newBodyText[0]));
    }

    /**
     * Separates the Tracks for further stuff
     *
     * @param $trackString
     * @return array
     */
    public static function getTracks($trackString)
    {
        preg_match('/(Linien)(.*?)(:)/', $trackString, $matches);
        if (!empty($matches[2])) {
            return trim($matches[2]);
        }
    }
}

header('Content-Type: application/json; charset=utf-8');
$mvgTickerData = new mvgTicker();
echo $mvgTickerData::getUbahnData();
