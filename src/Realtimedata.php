<?php
/**
 * Created by PhpStorm.
 * User: dkoehl
 * Date: 11.01.19
 * Time: 07:51
 */

namespace Pixelmatic;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

require '../vendor/autoload.php';

/**
 * Class RealTimeData
 * @package Pixelmatic
 */
class RealTimeData
{
    protected static $realtimedataUrls = [
        'http://efa.mvv-muenchen.de/xhr_departures?locationServerActive=1&stateless=1&type_dm=any&name_dm=1002126&useAllStops=1&useRealtime=1&limit=5&mode=direct&zope_command=enquiry%3Adepartures&compact=1&includedMeans=1&inclMOT_3=1&inclMOT_4=1&inclMOT_5=1&inclMOT_6=1&inclMOT_7=1&inclMOT_9=1&inclMOT_10=1&coordOutputFormat=MRCV&_=1547040115710',
        'http://efa.mvv-muenchen.de/xhr_departures?locationServerActive=1&stateless=1&type_dm=any&name_dm=de%3A09162%3A1250&useAllStops=1&useRealtime=1&limit=5&mode=direct&zope_command=enquiry%3Adepartures&compact=1&includedMeans=1&inclMOT_2=1&coordOutputFormat=MRCV&_=1547189583059',
        'http://efa.mvv-muenchen.de/xhr_departures?locationServerActive=1&stateless=1&type_dm=any&name_dm=de%3A09162%3A800&useAllStops=1&useRealtime=1&limit=5&mode=direct&zope_command=enquiry%3Adepartures&compact=1&includedMeans=1&inclMOT_1=1&coordOutputFormat=MRCV&_=1547189583060'
    ];

    /**
     * @return string
     */
    public static function getRealTimeData(): string
    {
        $realTimeData = '';
        foreach (self::$realtimedataUrls as $requestURL) {
            $realTimeData .= self::requestDataFromSource($requestURL);
        }
        return $realTimeData;
    }

    private static function requestDataFromSource(string $requestURL)
    {
        $client = new Client();
        try {
            $response = $client->request('GET', $requestURL);
        } catch (ClientException $e) {
            echo $e->getMessage() . PHP_EOL;
            return 'ERROR, Check Links und Request URLs';
        }
        if ($response->getStatusCode() === 200) {
            return self::showOnlyTableData($response->getBody());
        }
    }

    /**
     * replacement pattern for table html
     * @param $rawResponseData
     * @return mixed
     */
    public static function showOnlyTableData(string $rawResponseData): string
    {
        preg_match_all('/(<table)+(.*?)(<\/table>)/is', $rawResponseData, $matches);
        if (isset($matches[0][0])) {
            $tableData = '<div class="table-responsive">' . $matches[0][0] . '</div>';
            $tableData = preg_replace('/<img[^>]+\>/i', '', $tableData);
            // replacement pattern for frontend styling
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
}

$traindata = new RealTimeData();
echo $traindata::getRealTimeData();
