<?php
/**
 * Created by PhpStorm.
 * User: dkoehl
 * Date: 11.01.19
 * Time: 07:51
 */

namespace Pixelmatic;

use GuzzleHttp\Client;

require '../vendor/autoload.php';

/**
 * Class Realtimedata
 * @package Pixelmatic
 */
class Realtimedata
{
    const REALTIMEDATALINKS = [
        'http://efa.mvv-muenchen.de/xhr_departures?locationServerActive=1&stateless=1&type_dm=any&name_dm=1002126&useAllStops=1&useRealtime=1&limit=5&mode=direct&zope_command=enquiry%3Adepartures&compact=1&includedMeans=1&inclMOT_3=1&inclMOT_4=1&inclMOT_5=1&inclMOT_6=1&inclMOT_7=1&inclMOT_9=1&inclMOT_10=1&coordOutputFormat=MRCV&_=1547040115710',
        'http://efa.mvv-muenchen.de/xhr_departures?locationServerActive=1&stateless=1&type_dm=any&name_dm=de%3A09162%3A1250&useAllStops=1&useRealtime=1&limit=5&mode=direct&zope_command=enquiry%3Adepartures&compact=1&includedMeans=1&inclMOT_2=1&coordOutputFormat=MRCV&_=1547189583059',
        'http://efa.mvv-muenchen.de/xhr_departures?locationServerActive=1&stateless=1&type_dm=any&name_dm=de%3A09162%3A800&useAllStops=1&useRealtime=1&limit=5&mode=direct&zope_command=enquiry%3Adepartures&compact=1&includedMeans=1&inclMOT_1=1&coordOutputFormat=MRCV&_=1547189583060'
    ];

    /**
     * Realtimedata constructor.
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    function __construct()
    {
        foreach (self::REALTIMEDATALINKS as $link) {
            $rawRealtimeData = self::getRealtimeData($link);
            echo self::getOnlyTableData($rawRealtimeData);
        }
    }

    /**
     * @param $requestURL
     * @return \Psr\Http\Message\StreamInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function getRealtimeData($requestURL)
    {
        $client = new Client();
        $response = $client->request('GET', $requestURL);
        if ($response->getStatusCode() == 200) {
            return $response->getBody();
        }
    }

    /**
     * @param $rawResponseData
     * @return mixed
     */
    public static function getOnlyTableData($rawResponseData)
    {
        preg_match_all('/(<table)+(.*?)(<\/table>)/is', $rawResponseData, $matches);
        $tableData = '<div class="table-responsive">' . $matches[0][0] . '</div>';
        $tableData = preg_replace('/<img[^>]+\>/i', '', $tableData);
        return str_replace(
            [
                'style="clear:both;"',
                'visuallyhidden',
                'Live',
                'Abfahrt',
            ],
            [
                'class="table"',
                'thead-light',
                'Plan',
                'Live',
            ],
            $tableData
        );
    }
}

new Realtimedata();
