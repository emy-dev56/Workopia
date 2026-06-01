<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GeoLocationController extends Controller
{
    //@desc fetch geolocation map
    //@route /geolocation

    public function geolocation(Request $request): array{
        $address = $request->input('address');
        $access_token = env('GOOGLE_MAP_API_KEY');
        $url = "https://maps.googleapis.com/maps/api/geocode/json?address=".$address;
       $response = Http::get($url, [
           'key' => $access_token
       ]);

       return $response->json();
    }
}
