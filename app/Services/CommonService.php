<?php
namespace App\Services;

class CommonService
{
    public function isJSON($string)
    {
        return is_string($string) && is_array(json_decode($string, true)) ? true : false;
    }


    public function findLatitudeAndLongitude($city)
    {
        $apiKey  ='AIzaSyCLfVtYfTOVAcjsMpVDVltEu7SJIP007Uw';
        $address = urlencode($city);
        $url     = "https://maps.googleapis.com/maps/api/geocode/json?address=".$address."&key=".$apiKey;
        $resp    = json_decode(file_get_contents($url), true);

        // Latitude and Longitude (PHP 7 syntax)
        $lat    = $resp['results'][0]['geometry']['location']['lat'] ?? '';
        $long   = $resp['results'][0]['geometry']['location']['lng'] ?? '';

        return  [$lat,$long];
    }
}
