<?php

namespace App\Service;

use Geocoder\Provider\Provider;

class GeocoderService
{
    private $googleMapsGeocoder;

    public function __construct(Provider $googleMapsGeocoder)
    {
        $this->googleMapsGeocoder = $googleMapsGeocoder;
    }

    public function getCoordinates($location){
        return $this->googleMapsGeocoder->geocodeQuery($location);
    }
}