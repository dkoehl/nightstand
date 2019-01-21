<?php /** @noinspection PhpCSValidationInspection */

namespace Pixelmatic;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Psr\Http\Message\StreamInterface;

require '../vendor/autoload.php';

/**
 * Class WeatherTicker
 * This class gets the actual weather from https://openweathermap.org/current#data
 *
 * @package Pixelmatic\Weather
 * @author  Dennis Köhl <koehl@pm-newmedia.com>
 */
class WeatherTicker
{
    /**
     * @var string
     */
    private static $apikey = '8c144c679527ee3fa414a592246222ec';
    /**
     * @var string
     */
    private static $cityid = '6556307';

    /**
     * @return false|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function getWeatherData()
    {
        $responseData = self::requestApi();
        return json_encode(self::sanitizeWeatherResponse($responseData), JSON_UNESCAPED_UNICODE);
    }

    /**
     * @return \Psr\Http\Message\StreamInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function requestApi(): StreamInterface
    {
        $client = new Client();
        $response = '';
        try {
            $response = $client->request('GET',
                'https://api.openweathermap.org/data/2.5/weather?id=' . self::$cityid . '&appid=' . self::$apikey . '&lang=de&units=metric');
        } catch (ClientException $e) {
            echo $e->getMessage();
        }
        return $response->getBody();
    }

    /**
     * @param $responseJsonData
     * @return array
     */
    private static function sanitizeWeatherResponse($responseJsonData):array
    {
        $response = json_decode($responseJsonData);
        return [
            'Wetter' => $response->weather[0]->description,
            'WetterIcon' => @$response->weather[0]->icon,
            'Mood' => @$response->weather[1]->description,
            'MoodIcon' => @$response->weather[1]->icon,
            'Temperatur' => $response->main->temp,
            'Druck' => $response->main->pressure,
            'Feuchtigkeit' => $response->main->humidity,
            'min-Temp' => $response->main->temp_min,
            'max-Temp' => $response->main->temp_max,
            'Sonnenaufgang' => date('H:i', $response->sys->sunrise),
            'Sonnenuntergang' => date('H:i', $response->sys->sunset),
            'City' => $response->name,
        ];
    }
}

header('Content-Type: application/json; charset=utf-8');
$weatherData = new WeatherTicker();
echo $weatherData::getWeatherData();
