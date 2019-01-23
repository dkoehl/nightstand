<?php

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
    protected static $api_key = '';
    /**
     * @var string
     */
    protected static $city_id = '';

    /**
     * @var string
     */
    protected static $api_url = '';


    /**
     * WeatherTicker constructor.
     */
    public function __construct()
    {
        self::$api_url = 'https://api.openweathermap.org/data/2.5/weather?id=';
        self::$api_url .= self::$city_id;
        self::$api_url .= '&appid=';
        self::$api_url .= self::$api_key;
        self::$api_url .= '&lang=de&units=metric';
    }

    /**
     * @return StreamInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected static function requestData(): StreamInterface
    {
        $response = '';
        $client = new Client();
        try {
            $response = $client->request('GET', self::$api_url);
        } catch (ClientException $e) {
            echo $e->getMessage() . PHP_EOL;
        }
        return $response->getBody();
    }

    /**
     * @return false|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function getWeatherData(): string
    {
        $responseBody = self::requestData();
        return json_encode(self::sanitizeWeatherResponse($responseBody), JSON_UNESCAPED_UNICODE);
    }

    /**
     * @param $responseJsonData
     * @return array
     */
    private static function sanitizeWeatherResponse($responseBody): array
    {
        $response = json_decode($responseBody);
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
