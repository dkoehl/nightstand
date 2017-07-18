<?php


class mvgTicker{

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

    /**
     * @return string JSON
     */
    function init(){
        $mvgreportpage = file_get_contents('https://ticker.mvg.de/');
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