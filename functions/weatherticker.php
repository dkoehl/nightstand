<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

class weatherTicker {

    #https://blog.kulturbanause.de/2016/10/das-aktuelle-wetter-mit-hilfe-der-yahoo-weather-api-anzeigen/
    const LOCATION_ID       = 635669;
    const WEATHER_APIURL    = "http://query.yahooapis.com/v1/public/yql";


    /**
     * @return string
     */
    function init() {
        $yql_query_url = self::WEATHER_APIURL . "?q=" . urlencode("select * from weather.forecast where woeid=" . self::LOCATION_ID) . "&format=json&u=c";
        $requestDataJSON = @file_get_contents($yql_query_url);
        $requestDataObject = json_decode($requestDataJSON);
        if(empty($requestDataJSON)) {
            return json_encode('Wetter konnte nicht geladen werden', JSON_UNESCAPED_UNICODE);
        }
        $today = $requestDataObject->query->results->channel->item->condition;
        $weatherDataArray = [
            'icon' => $today->code,
            'temperatur'    => $this->mathFahrenheitToCelsius($today->temp),
            'currently'     => $this->translateWeatherValues($today->text)
        ];
        return json_encode($weatherDataArray, JSON_UNESCAPED_UNICODE);
    }

    /**
     * Temperature calculator
     * @param $i
     *
     * @return float
     */
    static function mathFahrenheitToCelsius($i)
    {
        return round(($i - 32) / 1.8, 1);
    }

    /**
     * Translation from english to german
     * @param String $data
     *
     * @return String mixed
     */
    static function translateWeatherValues($data)
    {
        $weatherDataArray = [
            'AM Clouds/PM Sun'          => 'vormittags bewölkt/nachmittags sonnig',
            'AM Drizzle'                => 'vormittags Nieselregen',
            'AM Drizzle/Wind'           => 'vorm. Nieselregen/Wind',
            'AM Fog/PM Clouds'          => 'vormittags Nebel/nachmittags bewölkt',
            'AM Fog/PM Sun'             => 'vormittags Nebel, nachmittags sonnig',
            'AM Ice'                    => 'vorm. Eis',
            'AM Light Rain'             => 'vormittags leichter Regen',
            'AM Light Rain/Wind'        => 'vorm. leichter Regen/Wind',
            'AM Light Snow'             => 'vormittags leichter Schneefall',
            'Breezy'                    => 'böhig windig',
            'AM Rain'                   => 'vormittags Regen',
            'AM Rain/Snow Showers'      => 'vorm. Regen-/Schneeschauer',
            'AM Rain/Snow'              => 'vormittags Regen/Schnee',
            'AM Rain/Snow/Wind'         => 'vorm. Regen/Schnee/Wind',
            'AM Rain/Wind'              => 'vorm. Regen/Wind',
            'AM Showers'                => 'vormittags Schauer',
            'AM Showers/Wind'           => 'vormittags Schauer/Wind',
            'AM Snow Showers'           => 'vormittags Schneeschauer',
            'AM Snow'                   => 'vormittags Schnee',
            'AM Thundershowers'         => 'vorm. Gewitterschauer',
            'Blowing Snow'              => 'Schneetreiben',
            'Clear'                     => 'Klar',
            'Clear/Windy'               => 'Klar/Windig',
            'Clouds Early/Clearing Late'    => 'früh Wolken/später klar',
            'Cloudy'                    => 'Bewölkt',
            'Cloudy/Wind'               => 'Bewölkt/Wind',
            'Cloudy/Windy'              => 'Wolkig/Windig',
            'Drifting Snow'             => 'Schneetreiben',
            'Drifting Snow/Windy'       => 'Schneetreiben/Windig',
            'Drizzle Early'             => 'früh Nieselregen',
            'Drizzle Late'              => 'später Nieselregen',
            'Drizzle'                   => 'Nieselregen',
            'Drizzle/Fog'               => 'Nieselregen/Nebel',
            'Drizzle/Wind'              => 'Nieselregen/Wind',
            'Drizzle/Windy'             => 'Nieselregen/Windig',
            'Fair'                      => 'Heiter',
            'Fair/Windy'                => 'Heiter/Windig',
            'Few Showers'               => 'vereinzelte Schauer',
            'Few Showers/Wind'          => 'vereinzelte Schauer/Wind',
            'Few Snow Showers'          => 'vereinzelt Schneeschauer',
            'Fog Early/Clouds Late'     => 'früh Nebel, später Wolken',
            'Fog Late'                  => 'später neblig',
            'Fog'                       => 'Nebel',
            'Fog/Windy'                 => 'Nebel/Windig',
            'Foggy'                     => 'neblig',
            'Freezing Drizzle'          => 'gefrierender Nieselregen',
            'Freezing Drizzle/Windy'    => 'gefrierender Nieselregen/Windig',
            'Freezing Rain'             => 'gefrierender Regen',
            'Haze'                      => 'Dunst',
            'Heavy Drizzle'             => 'starker Nieselregen',
            'Heavy Rain Shower'         => 'Starker Regenschauer',
            'Heavy Rain'                => 'Starker Regen',
            'Heavy Rain/Wind'           => 'starker Regen/Wind',
            'Heavy Rain/Windy'          => 'Starker Regen/Windig',
            'Heavy Snow Shower'         => 'Starker Schneeschauer',
            'Heavy Snow'                => 'Starker Schneefall',
            'Heavy Snow/Wind'           => 'Starker Schneefall/Wind',
            'Heavy Thunderstorm'        => 'Schweres Gewitter',
            'Heavy Thunderstorm/Windy'  => 'Schweres Gewitter/Windig',
            'Ice Crystals'              => 'Eiskristalle',
            'Ice Late'                  => 'später Eis',
            'Isolated T-storms'         => 'Vereinzelte Gewitter',
            'Isolated Thunderstorms'    => 'Vereinzelte Gewitter',
            'Light Drizzle'             => 'Leichter Nieselregen',
            'Light Freezing Drizzle'    => 'Leichter gefrierender Nieselregen',
            'Light Freezing Rain'       => 'Leichter gefrierender Regen',
            'Light Freezing Rain/Fog'   => 'Leichter gefrierender Regen/Nebel',
            'Light Rain Early'          => 'anfangs leichter Regen',
            'Light Rain'                => 'Leichter Regen',
            'Light Rain Late'           => 'später leichter Regen',
            'Light Rain Shower'         => 'Leichter Regenschauer',
            'Light Rain Shower/Fog'     => 'Leichter Regenschauer/Nebel',
            'Light Rain Shower/Windy'   => 'Leichter Regenschauer/windig',
            'Light Rain with Thunder'   => 'Leichter Regen mit Gewitter',
            'Light Rain/Fog'            => 'Leichter Regen/Nebel',
            'Light Rain/Freezing Rain'  => 'Leichter Regen/Gefrierender Regen',
            'Light Rain/Wind Early'     => 'früh leichter Regen/Wind',
            'Light Rain/Wind Late'      => 'später leichter Regen/Wind',
            'Light Rain/Wind'           => 'leichter Regen/Wind',
            'Light Rain/Windy'          => 'Leichter Regen/Windig',
            'Light Sleet'               => 'Leichter Schneeregen',
            'Light Snow Early'          => 'früher leichter Schneefall',
            'Light Snow Grains'         => 'Leichter Schneegriesel',
            'Light Snow Late'           => 'später leichter Schneefall',
            'Light Snow Shower'         => 'Leichter Schneeschauer',
            'Light Snow Shower/Fog'     => 'Leichter Schneeschauer/Nebel',
            'Light Snow with Thunder'   => 'Leichter Schneefall mit Gewitter',
            'Light Snow'                => 'Leichter Schneefall',
            'Light Snow/Fog'            => 'Leichter Schneefall/Nebel',
            'Light Snow/Freezing Rain'  => 'Leichter Schneefall/Gefrierender Regen',
            'Light Snow/Wind'           => 'Leichter Schneefall/Wind',
            'Light Snow/Windy'          => 'Leichter Schneeschauer/Windig',
            'Light Snow/Windy/Fog'      => 'Leichter Schneefall/Windig/Nebel',
            'Mist'                      => 'Nebel',
            'Mostly Clear'              => 'überwiegend Klar',
            'Mostly Cloudy'             => 'Überwiegend bewölkt',
            'Mostly Cloudy/Wind'        => 'meist bewölkt/Wind',
            'Mostly Sunny'              => 'Überwiegend sonnig',
            'Partial Fog'               => 'teilweise Nebel',
            'Partly Cloudy'             => 'Teilweise bewölkt',
            'Partly Cloudy/Wind'        => 'teilweise bewölkt/Wind',
            'Patches of Fog'            => 'Nebelfelder',
            'Patches of Fog/Windy'      => 'Nebelfelder/Windig',
            'PM Drizzle'                => 'nachm. Nieselregen',
            'PM Fog'                    => 'nachmittags Nebel',
            'PM Light Snow'             => 'nachmittags leichter Schneefall',
            'PM Light Rain'             => 'nachmittags leichter Regen',
            'PM Light Rain/Wind'        => 'nachm. leichter Regen/Wind',
            'PM Light Snow/Wind'        => 'nachm. leichter Schneefall/Wind',
            'PM Rain'                   => 'nachmittags Regen',
            'PM Rain/Snow Showers'      => 'nachmittags Regen/Schneeschauer',
            'PM Rain/Snow'              => 'nachmittags Regen/Schnee',
            'PM Rain/Wind'              => 'nachm. Regen/Wind',
            'PM Showers'                => 'nachmittags Schauer',
            'PM Showers/Wind'           => 'nachmittags Schauer/Wind',
            'PM Snow Showers'           => 'nachmittags Schneeschauer',
            'PM Snow Showers/Wind'      => 'nachm. Schneeschauer/Wind',
            'PM Snow'                   => 'nachm. Schnee',
            'PM T-storms'               => 'nachmittags Gewitter',
            'PM Thundershowers'         => 'nachmittags Gewitterschauer',
            'PM Thunderstorms'          => 'nachm. Gewitter',
            'Rain and Snow'             => 'Schneeregen',
            'Rain and Snow/Windy'       => 'Regen und Schnee/Windig',
            'Rain/Snow Showers/Wind'    => 'Regen/Schneeschauer/Wind',
            'Rain Early'                => 'früh Regen',
            'Rain Late'                 => 'später Regen',
            'Rain Shower'               => 'Regenschauer',
            'Rain Shower/Windy'         => 'Regenschauer/Windig',
            'Rain to Snow'              => 'Regen, in Schnee übergehend',
            'Rain'                      => 'Regen',
            'Rain/Snow Early'           => 'früh Regen/Schnee',
            'Rain/Snow Late'            => 'später Regen/Schnee',
            'Rain/Snow Showers Early'   => 'früh Regen-/Schneeschauer',
            'Rain/Snow Showers Late'    => 'später Regen-/Schneeschnauer',
            'Rain/Snow Showers'         => 'Regen/Schneeschauer',
            'Rain/Snow'                 => 'Regen/Schnee',
            'Rain/Snow/Wind'            => 'Regen/Schnee/Wind',
            'Rain/Thunder'              => 'Regen/Gewitter',
            'Rain/Wind Early'           => 'früh Regen/Wind',
            'Rain/Wind Late'            => 'später Regen/Wind',
            'Rain/Wind'                 => 'Regen/Wind',
            'Rain/Windy'                => 'Regen/Windig',
            'Scattered Showers'         => 'vereinzelte Schauer',
            'Scattered Showers/Wind'    => 'vereinzelte Schauer/Wind',
            'Scattered Snow Showers'    => 'vereinzelte Schneeschauer',
            'Scattered Snow Showers/Wind'   => 'vereinzelte Schneeschauer/Wind',
            'Scattered T-storms'        => 'vereinzelte Gewitter',
            'Scattered Thunderstorms'   => 'vereinzelte Gewitter',
            'Shallow Fog'               => 'flacher Nebel',
            'Showers'                   => 'Schauer',
            'Showers Early'             => 'früh Schauer',
            'Showers Late'              => 'später Schauer',
            'Showers in the Vicinity'   => 'Regenfälle in der Nähe',
            'Showers/Wind'              => 'Schauer/Wind',
            'Sleet and Freezing Rain'   => 'Schneeregen und gefrierender Regen',
            'Sleet/Windy'               => 'Schneeregen/Windig',
            'Snow Grains'               => 'Schneegriesel',
            'Snow Late'                 => 'später Schnee',
            'Snow Shower'               => 'Schneeschauer',
            'Snow Showers Early'        => 'früh Schneeschauer',
            'Snow Showers Late'         => 'später Schneeschauer',
            'Snow Showers'              => 'Schneeschauer',
            'Snow Showers/Wind'         => 'Schneeschauer/Wind',
            'Snow to Rain'              => 'Schneeregen',
            'Snow'                      => 'Schneefall',
            'Snow/Wind'                 => 'Schneefall/Wind',
            'Snow/Windy'                => 'Schnee/Windig',
            'Squalls'                   => 'Böen',
            'Sunny'                     => 'Sonnig',
            'Sunny/Wind'                => 'Sonnig/Wind',
            'Sunny/Windy'               => 'Sonnig/Windig',
            'T-showers'                 => 'Gewitterschauer',
            'Thunder in the Vicinity'   => 'Gewitter in der Umgebung',
            'Thunder'                   => 'Gewitter',
            'Thundershowers Early'      => 'früh Gewitterschauer',
            'Thundershowers'            => 'Gewitterschauer',
            'Thunderstorm'              => 'Gewitter',
            'Thunderstorm/Windy'        => 'Gewitter/Windig',
            'Thunderstorms Early'       => 'früh Gewitter',
            'Thunderstorms Late'        => 'später Gewitter',
            'Thunderstorms'             => 'Gewitter',
            'Unknown Precipitation'     => 'Niederschlag',
            'Unknown'                   => 'unbekannt',
            'Wintry Mix'                => 'Winterlicher Mix',
        ];
        if(empty($weatherDataArray[$data])) {
            return $data;
        }
        return $weatherDataArray[$data];
    }
}

header('Content-Type: application/json');
$weatherData = new weatherTicker();
echo $weatherData->init();
