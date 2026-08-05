<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EpicorOEHDR;
use App\Models\TimeOffRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $userid = Auth::id();
        $today = today();

        $calendarevents = TimeOffRequest::where('user_id', $userid)->where('start', '>=', $today)->count();


        $lat = Auth::user()->location->lat;
        $lon = Auth::user()->location->lon;

    if(empty($lat)) {
        $lat = 33.4251;
    }

    if(empty($lon)) {
        $lon = -94.0477;
    }
        
    

    $nwsHeader = ['User-Agent' => 'MyWeatherApp (mbartlett@industrialmill.com)'];

    $pointRes = Http::withHeaders($nwsHeader)->get("https://api.weather.gov/points/{$lat},{$lon}");
    
    if ($pointRes->failed()) {
        return view('weather', ['temperature' => null]);
    }

    $stationsUrl = $pointRes->json('properties.observationStations');
    
    $stationsRes = Http::withHeaders($nwsHeader)->get($stationsUrl);
    $stationId = $stationsRes->json('features.0.properties.stationIdentifier');

    $obsRes = Http::withHeaders($nwsHeader)->get("https://api.weather.gov/stations/{$stationId}/observations/latest");

    $tempC = $obsRes->json('properties.temperature.value');
     
    $temperature = !is_null($tempC) ? round(($tempC * 9/5) + 32, 0) : null;
    $condition = $obsRes->json('properties.textDescription');
    $humidity = $obsRes->json('properties.relativeHumidity.value');
    $icon = $obsRes->json('properties.icon');
    $city = Auth::user()->location->city;


        return view('dashboard', compact('temperature', 'condition', 'icon', 'humidity', 'city', 'calendarevents'));
    }

    public function test()
    {
        $test = EpicorOEHDR::all();

        dd($test);
    }
}
