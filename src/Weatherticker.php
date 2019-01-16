<?php /** @noinspection PhpCSValidationInspection */

namespace Pixelmatic\Weather;

use GuzzleHttp\Exception\ClientException;

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
    private static $apikey = '';
    private static $cityid = '';
    /**
     * @return false|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function getWeatherData()
    {
        $responseData = self::getDataFromApi();
        return json_encode(self::sanitizeWeatherResponse($responseData), JSON_UNESCAPED_UNICODE);
    }

    /**
     * @return \Psr\Http\Message\StreamInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function getDataFromApi()
    {
        $client = new \GuzzleHttp\Client();
        try {
            $response = $client->request('GET',
                'https://api.openweathermap.org/data/2.5/weather?id='.self::$cityid.'&appid='.self::$apikey.'&lang=de&units=metric');
        } catch (ClientException $e) {
            echo $e->getCode();

        }
        return $response->getBody();
    }

    /**
     * @param $responseJsonData
     * @return array
     */
    private static function sanitizeWeatherResponse($responseJsonData)
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
