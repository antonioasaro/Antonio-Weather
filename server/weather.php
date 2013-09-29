<?PHP

if (!isset($_GET['lat'])) die();
if (!isset($_GET['lon'])) die();
if (!isset($_GET['unt'])) die();

$lat = $_GET['lat'] / 10000;
$lon = $_GET['lon'] / 10000;
$unt = $_GET['unt'];

//// for Toronto ////
// $lat = "43.7000";
// $lon = "-79.4000";
// $unt = "metric";

$json  = curl_get('http://api.openweathermap.org/data/2.5/weather?lat='.$lat.'&lon='.$lon.'&units='.$unt);

$weather = process_weather($json);
print json_encode($weather);

function curl_get($url){
    if (!function_exists('curl_init')){
        die('Sorry cURL is not installed!');
    }
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_REFERER, $url);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/1.0");
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}

function process_weather($json_in) {
    $json_output = json_decode(utf8_decode($json_in));
    if (!$json_output) die(); 

    $weather     = $json_output->weather;
    $temperature = $json_output->main->temp;
    $icon        = $weather[0]->icon;

    $result    = array();
    $result[1] = $icon;
    $result[2] = array('I', round($temperature, 0));
    return $result;
}

//// 01d.png	 01n.png	 sky is clear
//// 02d.png	 02n.png	 few clouds
//// 03d.png	 03n.png	 scattered clouds
//// 04d.png	 04n.png	 broken clouds
//// 09d.png	 09n.png	 shower rain
//// 10d.png	 10n.png	 Rain
//// 11d.png	 11n.png	 Thunderstorm
//// 13d.png	 13n.png	 snow
//// 50d.png	 50n.png	 mist

?>
