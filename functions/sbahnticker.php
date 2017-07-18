<?php

class sbahnTicker {

    /**
     * @var array
     */
    var $sbahnTickerArray = [];
    /**
     * @var string
     */
    var $sbahnreportpage = '';
    /**
     * @var string
     */
    var $sbahnDoc = '';
    /**
     * @var string
     */
    var $reportHeadlines = '';
    /**
     * @var string
     */
    var $reportContents = '';
    /**
     * @var string
     */
    var $reportTracks = '';

    /**
     * @return array
     */
    function init() {
        $sbahnreportpage = file_get_contents('https://img.srv2.de/customer/sbahnMuenchen/newsticker/newsticker.html');
//        $sbahnreportpage = file_get_contents('/Applications/MAMP/htdocs/nightstand/ticker.html?'.time());





        $sbahnDoc = new DOMDocument();
        libxml_use_internal_errors(true);
        $sbahnDoc->loadHTML(trim($sbahnreportpage));

//        hiddenPage


        /**
         * Makes Array out of headlines
         */
        $reportHeadlines = $sbahnDoc->getElementsByTagName('h1');
        foreach ($reportHeadlines as $item) {

            $headlineArray[] = $item->nodeValue;
        }
        /**
         * Makes Array out of textcontent
         */
        $reportContents = $sbahnDoc->getElementsByTagName('p');
        foreach ($reportContents as $oDomNode) {
            $contentArray[] = explode("\r\r\r", $oDomNode->nodeValue);
        }

        /**
         * gets tracks
         */
        $sbahnXpath = new DOMXPath($sbahnDoc);
        $reportTracks = $sbahnXpath->query('//*[@class="tracks"]');
        foreach ($reportTracks as $item) {
            $trackArray[] = trim($item->nodeValue);
        }
        /**
         * Builds final array
         */
        for ($i = 0; $i < count($contentArray); $i++) {
            if(!empty($headlineArray[$i])){
                $sbahnTickerArray[] = [
                    'headline' => trim($headlineArray[$i]),
                    'description' => trim($contentArray[$i][0]),
                    'tracks' => strip_tags($trackArray[$i])
                ];
            }else{
                if(!empty($contentArray[$i][0])){
                    $sbahnTickerArray[] = [
                        'headline' => $contentArray[$i][0]
                    ];
                }

            }
        }
        $sbahnTickerArray = $sbahnTickerArray;
        return json_encode($sbahnTickerArray, JSON_UNESCAPED_UNICODE);
    }
}

header('Content-Type: application/json');
$sbahnTickerData = new sbahnTicker();
echo $sbahnTickerData->init();