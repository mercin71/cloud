<?php

$time_zone = getTimeZoneFromIpAddress(); //zapisanie pobranych danych z funkcji do zmiennych
$ip = get_client_ip();

echo 'Twoja strefa czasowa to: '.$time_zone; // wyświetlanie danych
echo '<br />';
echo 'Twoje IP: '.$ip;
echo '<br />';
$dt = new DateTime("now", new DateTimeZone($time_zone)); //wyświetlanie godziny i daty na podstawie datazone
echo $dt->format("Y-m-d H:i:s");





$logFileName = 'MYLOG.log'; // zapisywanie logów
$logContent = "Marcin Poprawa".PHP_EOL;
$date = new DateTime();
$date = $date->format("y:m:d h:i:s");
if ($handle = fopen($logFileName, 'a'))
{
  fwrite($handle, $date);
  fwrite($handle, PHP_EOL);
  fwrite($handle, $logContent);
  fwrite($handle, PHP_EOL);
  fwrite($handle, $cmdWindows);
  fwrite($handle, PHP_EOL);
  fwrite($handle, $params);
  fwrite($handle, PHP_EOL);
 }
 fclose($handle);




function getTimeZoneFromIpAddress(){ //pobieranie time zone poprzez ip strony geoplugin
    $clientsIpAddress = get_client_ip();
    $clientInformation = unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip='.$clientsIpAddress));
    $clientsLatitude = $clientInformation['geoplugin_latitude'];
    $clientsLongitude = $clientInformation['geoplugin_longitude'];
    $clientsCountryCode = $clientInformation['geoplugin_countryCode'];
    $timeZone = get_nearest_timezone($clientsLatitude, $clientsLongitude, $clientsCountryCode) ;
    return $timeZone;
}

function get_client_ip() { //pobranie adresu ip uzytkownika
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
        $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}

function get_nearest_timezone($cur_lat, $cur_long, $country_code = '') { // podzielnie danych pobranych z api
    $timezone_ids = ($country_code) ? DateTimeZone::listIdentifiers(DateTimeZone::PER_COUNTRY, $country_code)
        : DateTimeZone::listIdentifiers();

    if($timezone_ids && is_array($timezone_ids) && isset($timezone_ids[0])) {

        $time_zone = '';
        $tz_distance = 0;

        
        if (count($timezone_ids) == 1) {
            $time_zone = $timezone_ids[0];
        } else {

            foreach($timezone_ids as $timezone_id) {
                $timezone = new DateTimeZone($timezone_id);
                $location = $timezone->getLocation();
                $tz_lat   = $location['latitude'];
                $tz_long  = $location['longitude'];

                $theta    = $cur_long - $tz_long;
                $distance = (sin(deg2rad($cur_lat)) * sin(deg2rad($tz_lat)))
                    + (cos(deg2rad($cur_lat)) * cos(deg2rad($tz_lat)) * cos(deg2rad($theta)));
                $distance = acos($distance);
                $distance = abs(rad2deg($distance));
                // echo '<br />'.$timezone_id.' '.$distance;

                if (!$time_zone || $tz_distance > $distance) {
                    $time_zone   = $timezone_id;
                    $tz_distance = $distance;
                }

            }
        }
        return  $time_zone;
    }
    return 'unknown';
}


?>
