<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

class weatherTicker {

    #https://blog.kulturbanause.de/2016/10/das-aktuelle-wetter-mit-hilfe-der-yahoo-weather-api-anzeigen/
    var $locationId = 635669;
    var $weatherApiUrl = "http://query.yahooapis.com/v1/public/yql";


    /**
     * @return string
     */
    function init() {
        $yql_query_url = $this->weatherApiUrl . "?q=" . urlencode("select * from weather.forecast where woeid=" . $this->locationId) . "&format=json&u=c";
        $requestDataJSON = file_get_contents($yql_query_url);
        $requestDataObject = json_decode($requestDataJSON);
        if(!empty($requestDataJSON)) {
            $today = $requestDataObject->query->results->channel->item->condition;
            $weatherDataArray = [
                'icon' => $today->code,
                'temperatur' => self::mathFahrenheitToCelsius($today->temp),
                'currently' => self::translateWeatherValues($today->text)
            ];
        }else{
            echo "Wetter konnte nicht geladen werden";
        }
        return json_encode($weatherDataArray, JSON_UNESCAPED_UNICODE);
    }


    /**
     * Umrechnen der Temperatur von Fahrenheit in Celsius
     *
     * @param $i
     *
     * @return float
     */
    static function mathFahrenheitToCelsius($i) {
        return round(($i - 32) / 1.8, 1);
    }


    /**
     * Übersetzen der englischen Begriffe ins Deutsche
     *
     * @param $data
     *
     * @return string
     */
    static function translateWeatherValues($data) {
        if($data == 'AM Clouds/PM Sun') $data = 'vormittags bewölkt/nachmittags sonnig';
        elseif($data == 'AM Drizzle') $data = 'vormittags Nieselregen';
        elseif($data == 'AM Drizzle/Wind') $data = 'vorm. Nieselregen/Wind';
        elseif($data == 'AM Fog/PM Clouds') $data = 'vormittags Nebel/nachmittags bewölkt';
        elseif($data == 'AM Fog/PM Sun') $data = 'vormittags Nebel, nachmittags sonnig';
        elseif($data == 'AM Ice') $data = 'vorm. Eis';
        elseif($data == 'AM Light Rain') $data = 'vormittags leichter Regen';
        elseif($data == 'AM Light Rain/Wind') $data = 'vorm. leichter Regen/Wind';
        elseif($data == 'AM Light Snow') $data = 'vormittags leichter Schneefall';
        elseif($data == 'Breezy') $data = 'böhig windig';
        elseif($data == 'AM Rain') $data = 'vormittags Regen';
        elseif($data == 'AM Rain/Snow Showers') $data = 'vorm. Regen-/Schneeschauer';
        elseif($data == 'AM Rain/Snow') $data = 'vormittags Regen/Schnee';
        elseif($data == 'AM Rain/Snow/Wind') $data = 'vorm. Regen/Schnee/Wind';
        elseif($data == 'AM Rain/Wind') $data = 'vorm. Regen/Wind';
        elseif($data == 'AM Showers') $data = 'vormittags Schauer';
        elseif($data == 'AM Showers/Wind') $data = 'vormittags Schauer/Wind';
        elseif($data == 'AM Snow Showers') $data = 'vormittags Schneeschauer';
        elseif($data == 'AM Snow') $data = 'vormittags Schnee';
        elseif($data == 'AM Thundershowers') $data = 'vorm. Gewitterschauer';
        elseif($data == 'Blowing Snow') $data = 'Schneetreiben';
        elseif($data == 'Clear') $data = 'Klar';
        elseif($data == 'Clear/Windy') $data = 'Klar/Windig';
        elseif($data == 'Clouds Early/Clearing Late') $data = 'früh Wolken/später klar';
        elseif($data == 'Cloudy') $data = 'Bewölkt';
        elseif($data == 'Cloudy/Wind') $data = 'Bewölkt/Wind';
        elseif($data == 'Cloudy/Windy') $data = 'Wolkig/Windig';
        elseif($data == 'Drifting Snow') $data = 'Schneetreiben';
        elseif($data == 'Drifting Snow/Windy') $data = 'Schneetreiben/Windig';
        elseif($data == 'Drizzle Early') $data = 'früh Nieselregen';
        elseif($data == 'Drizzle Late') $data = 'später Nieselregen';
        elseif($data == 'Drizzle') $data = 'Nieselregen';
        elseif($data == 'Drizzle/Fog') $data = 'Nieselregen/Nebel';
        elseif($data == 'Drizzle/Wind') $data = 'Nieselregen/Wind';
        elseif($data == 'Drizzle/Windy') $data = 'Nieselregen/Windig';
        elseif($data == 'Fair') $data = 'Heiter';
        elseif($data == 'Fair/Windy') $data = 'Heiter/Windig';
        elseif($data == 'Few Showers') $data = 'vereinzelte Schauer';
        elseif($data == 'Few Showers/Wind') $data = 'vereinzelte Schauer/Wind';
        elseif($data == 'Few Snow Showers') $data = 'vereinzelt Schneeschauer';
        elseif($data == 'Fog Early/Clouds Late') $data = 'früh Nebel, später Wolken';
        elseif($data == 'Fog Late') $data = 'später neblig';
        elseif($data == 'Fog') $data = 'Nebel';
        elseif($data == 'Fog/Windy') $data = 'Nebel/Windig';
        elseif($data == 'Foggy') $data = 'neblig';
        elseif($data == 'Freezing Drizzle') $data = 'gefrierender Nieselregen';
        elseif($data == 'Freezing Drizzle/Windy') $data = 'gefrierender Nieselregen/Windig';
        elseif($data == 'Freezing Rain') $data = 'gefrierender Regen';
        elseif($data == 'Haze') $data = 'Dunst';
        elseif($data == 'Heavy Drizzle') $data = 'starker Nieselregen';
        elseif($data == 'Heavy Rain Shower') $data = 'Starker Regenschauer';
        elseif($data == 'Heavy Rain') $data = 'Starker Regen';
        elseif($data == 'Heavy Rain/Wind') $data = 'starker Regen/Wind';
        elseif($data == 'Heavy Rain/Windy') $data = 'Starker Regen/Windig';
        elseif($data == 'Heavy Snow Shower') $data = 'Starker Schneeschauer';
        elseif($data == 'Heavy Snow') $data = 'Starker Schneefall';
        elseif($data == 'Heavy Snow/Wind') $data = 'Starker Schneefall/Wind';
        elseif($data == 'Heavy Thunderstorm') $data = 'Schweres Gewitter';
        elseif($data == 'Heavy Thunderstorm/Windy') $data = 'Schweres Gewitter/Windig';
        elseif($data == 'Ice Crystals') $data = 'Eiskristalle';
        elseif($data == 'Ice Late') $data = 'später Eis';
        elseif($data == 'Isolated T-storms') $data = 'Vereinzelte Gewitter';
        elseif($data == 'Isolated Thunderstorms') $data = 'Vereinzelte Gewitter';
        elseif($data == 'Light Drizzle') $data = 'Leichter Nieselregen';
        elseif($data == 'Light Freezing Drizzle') $data = 'Leichter gefrierender Nieselregen';
        elseif($data == 'Light Freezing Rain') $data = 'Leichter gefrierender Regen';
        elseif($data == 'Light Freezing Rain/Fog') $data = 'Leichter gefrierender Regen/Nebel';
        elseif($data == 'Light Rain Early') $data = 'anfangs leichter Regen';
        elseif($data == 'Light Rain') $data = 'Leichter Regen';
        elseif($data == 'Light Rain Late') $data = 'später leichter Regen';
        elseif($data == 'Light Rain Shower') $data = 'Leichter Regenschauer';
        elseif($data == 'Light Rain Shower/Fog') $data = 'Leichter Regenschauer/Nebel';
        elseif($data == 'Light Rain Shower/Windy') $data = 'Leichter Regenschauer/windig';
        elseif($data == 'Light Rain with Thunder') $data = 'Leichter Regen mit Gewitter';
        elseif($data == 'Light Rain/Fog') $data = 'Leichter Regen/Nebel';
        elseif($data == 'Light Rain/Freezing Rain') $data = 'Leichter Regen/Gefrierender Regen';
        elseif($data == 'Light Rain/Wind Early') $data = 'früh leichter Regen/Wind';
        elseif($data == 'Light Rain/Wind Late') $data = 'später leichter Regen/Wind';
        elseif($data == 'Light Rain/Wind') $data = 'leichter Regen/Wind';
        elseif($data == 'Light Rain/Windy') $data = 'Leichter Regen/Windig';
        elseif($data == 'Light Sleet') $data = 'Leichter Schneeregen';
        elseif($data == 'Light Snow Early') $data = 'früher leichter Schneefall';
        elseif($data == 'Light Snow Grains') $data = 'Leichter Schneegriesel';
        elseif($data == 'Light Snow Late') $data = 'später leichter Schneefall';
        elseif($data == 'Light Snow Shower') $data = 'Leichter Schneeschauer';
        elseif($data == 'Light Snow Shower/Fog') $data = 'Leichter Schneeschauer/Nebel';
        elseif($data == 'Light Snow with Thunder') $data = 'Leichter Schneefall mit Gewitter';
        elseif($data == 'Light Snow') $data = 'Leichter Schneefall';
        elseif($data == 'Light Snow/Fog') $data = 'Leichter Schneefall/Nebel';
        elseif($data == 'Light Snow/Freezing Rain') $data = 'Leichter Schneefall/Gefrierender Regen';
        elseif($data == 'Light Snow/Wind') $data = 'Leichter Schneefall/Wind';
        elseif($data == 'Light Snow/Windy') $data = 'Leichter Schneeschauer/Windig';
        elseif($data == 'Light Snow/Windy/Fog') $data = 'Leichter Schneefall/Windig/Nebel';
        elseif($data == 'Mist') $data = 'Nebel';
        elseif($data == 'Mostly Clear') $data = 'überwiegend Klar';
        elseif($data == 'Mostly Cloudy') $data = 'Überwiegend bewölkt';
        elseif($data == 'Mostly Cloudy/Wind') $data = 'meist bewölkt/Wind';
        elseif($data == 'Mostly Sunny') $data = 'Überwiegend sonnig';
        elseif($data == 'Partial Fog') $data = 'teilweise Nebel';
        elseif($data == 'Partly Cloudy') $data = 'Teilweise bewölkt';
        elseif($data == 'Partly Cloudy/Wind') $data = 'teilweise bewölkt/Wind';
        elseif($data == 'Patches of Fog') $data = 'Nebelfelder';
        elseif($data == 'Patches of Fog/Windy') $data = 'Nebelfelder/Windig';
        elseif($data == 'PM Drizzle') $data = 'nachm. Nieselregen';
        elseif($data == 'PM Fog') $data = 'nachmittags Nebel';
        elseif($data == 'PM Light Snow') $data = 'nachmittags leichter Schneefall';
        elseif($data == 'PM Light Rain') $data = 'nachmittags leichter Regen';
        elseif($data == 'PM Light Rain/Wind') $data = 'nachm. leichter Regen/Wind';
        elseif($data == 'PM Light Snow/Wind') $data = 'nachm. leichter Schneefall/Wind';
        elseif($data == 'PM Rain') $data = 'nachmittags Regen';
        elseif($data == 'PM Rain/Snow Showers') $data = 'nachmittags Regen/Schneeschauer';
        elseif($data == 'PM Rain/Snow') $data = 'nachmittags Regen/Schnee';
        elseif($data == 'PM Rain/Wind') $data = 'nachm. Regen/Wind';
        elseif($data == 'PM Showers') $data = 'nachmittags Schauer';
        elseif($data == 'PM Showers/Wind') $data = 'nachmittags Schauer/Wind';
        elseif($data == 'PM Snow Showers') $data = 'nachmittags Schneeschauer';
        elseif($data == 'PM Snow Showers/Wind') $data = 'nachm. Schneeschauer/Wind';
        elseif($data == 'PM Snow') $data = 'nachm. Schnee';
        elseif($data == 'PM T-storms') $data = 'nachmittags Gewitter';
        elseif($data == 'PM Thundershowers') $data = 'nachmittags Gewitterschauer';
        elseif($data == 'PM Thunderstorms') $data = 'nachm. Gewitter';
        elseif($data == 'Rain and Snow') $data = 'Schneeregen';
        elseif($data == 'Rain and Snow/Windy') $data = 'Regen und Schnee/Windig';
        elseif($data == 'Rain/Snow Showers/Wind') $data = 'Regen/Schneeschauer/Wind';
        elseif($data == 'Rain Early') $data = 'früh Regen';
        elseif($data == 'Rain Late') $data = 'später Regen';
        elseif($data == 'Rain Shower') $data = 'Regenschauer';
        elseif($data == 'Rain Shower/Windy') $data = 'Regenschauer/Windig';
        elseif($data == 'Rain to Snow') $data = 'Regen, in Schnee übergehend';
        elseif($data == 'Rain') $data = 'Regen';
        elseif($data == 'Rain/Snow Early') $data = 'früh Regen/Schnee';
        elseif($data == 'Rain/Snow Late') $data = 'später Regen/Schnee';
        elseif($data == 'Rain/Snow Showers Early') $data = 'früh Regen-/Schneeschauer';
        elseif($data == 'Rain/Snow Showers Late') $data = 'später Regen-/Schneeschnauer';
        elseif($data == 'Rain/Snow Showers') $data = 'Regen/Schneeschauer';
        elseif($data == 'Rain/Snow') $data = 'Regen/Schnee';
        elseif($data == 'Rain/Snow/Wind') $data = 'Regen/Schnee/Wind';
        elseif($data == 'Rain/Thunder') $data = 'Regen/Gewitter';
        elseif($data == 'Rain/Wind Early') $data = 'früh Regen/Wind';
        elseif($data == 'Rain/Wind Late') $data = 'später Regen/Wind';
        elseif($data == 'Rain/Wind') $data = 'Regen/Wind';
        elseif($data == 'Rain/Windy') $data = 'Regen/Windig';
        elseif($data == 'Scattered Showers') $data = 'vereinzelte Schauer';
        elseif($data == 'Scattered Showers/Wind') $data = 'vereinzelte Schauer/Wind';
        elseif($data == 'Scattered Snow Showers') $data = 'vereinzelte Schneeschauer';
        elseif($data == 'Scattered Snow Showers/Wind') $data = 'vereinzelte Schneeschauer/Wind';
        elseif($data == 'Scattered T-storms') $data = 'vereinzelte Gewitter';
        elseif($data == 'Scattered Thunderstorms') $data = 'vereinzelte Gewitter';
        elseif($data == 'Shallow Fog') $data = 'flacher Nebel';
        elseif($data == 'Showers') $data = 'Schauer';
        elseif($data == 'Showers Early') $data = 'früh Schauer';
        elseif($data == 'Showers Late') $data = 'später Schauer';
        elseif($data == 'Showers in the Vicinity') $data = 'Regenfälle in der Nähe';
        elseif($data == 'Showers/Wind') $data = 'Schauer/Wind';
        elseif($data == 'Sleet and Freezing Rain') $data = 'Schneeregen und gefrierender Regen';
        elseif($data == 'Sleet/Windy') $data = 'Schneeregen/Windig';
        elseif($data == 'Snow Grains') $data = 'Schneegriesel';
        elseif($data == 'Snow Late') $data = 'später Schnee';
        elseif($data == 'Snow Shower') $data = 'Schneeschauer';
        elseif($data == 'Snow Showers Early') $data = 'früh Schneeschauer';
        elseif($data == 'Snow Showers Late') $data = 'später Schneeschauer';
        elseif($data == 'Snow Showers') $data = 'Schneeschauer';
        elseif($data == 'Snow Showers/Wind') $data = 'Schneeschauer/Wind';
        elseif($data == 'Snow to Rain') $data = 'Schneeregen';
        elseif($data == 'Snow') $data = 'Schneefall';
        elseif($data == 'Snow/Wind') $data = 'Schneefall/Wind';
        elseif($data == 'Snow/Windy') $data = 'Schnee/Windig';
        elseif($data == 'Squalls') $data = 'Böen';
        elseif($data == 'Sunny') $data = 'Sonnig';
        elseif($data == 'Sunny/Wind') $data = 'Sonnig/Wind';
        elseif($data == 'Sunny/Windy') $data = 'Sonnig/Windig';
        elseif($data == 'T-showers') $data = 'Gewitterschauer';
        elseif($data == 'Thunder in the Vicinity') $data = 'Gewitter in der Umgebung';
        elseif($data == 'Thunder') $data = 'Gewitter';
        elseif($data == 'Thundershowers Early') $data = 'früh Gewitterschauer';
        elseif($data == 'Thundershowers') $data = 'Gewitterschauer';
        elseif($data == 'Thunderstorm') $data = 'Gewitter';
        elseif($data == 'Thunderstorm/Windy') $data = 'Gewitter/Windig';
        elseif($data == 'Thunderstorms Early') $data = 'früh Gewitter';
        elseif($data == 'Thunderstorms Late') $data = 'später Gewitter';
        elseif($data == 'Thunderstorms') $data = 'Gewitter';
        elseif($data == 'Unknown Precipitation') $data = 'Niederschlag';
        elseif($data == 'Unknown') $data = 'unbekannt';
        elseif($data == 'Wintry Mix') $data = 'Winterlicher Mix';
        else $data = $data;
        return $data;
    }

}

header('Content-Type: application/json');
$weatherData = new weatherTicker();
echo $weatherData->init();