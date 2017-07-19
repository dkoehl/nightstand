<?php

/**
 * Class mvgTicker
 */
class mvgTicker
{
    /**
     * @var string
     */
    var $mvgreportpage = '';
    /**
     * @var string
     */
    var $mvgXmlDoc = '';
    /**
     * @var array
     */
    var $mvgDataArray = [];
    /**
     * @var string
     */
    var $mvgTickerData = '';

    const DATATICKER = 'https://ticker.mvg.de/';

    /**
     * Gets XML Data from external API
     * @return string JSON
     */
    function init()
    {
        $mvgreportpage = file_get_contents(self::DATATICKER);
        $mvgXmlDoc = new SimpleXMLElement($mvgreportpage);

        foreach ($mvgXmlDoc->channel->item as $item) {
            $mvgDataArray[] = [
                'title' => trim($item->title),
                'description' => trim($item->description),
                'pubdate' => strip_tags(trim($item->pubDate))
            ];

        }
        return json_encode($mvgDataArray, JSON_UNESCAPED_UNICODE);
    }
}
header('Content-Type: application/json');
$mvgTickerData = new mvgTicker();
echo $mvgTickerData->init();
