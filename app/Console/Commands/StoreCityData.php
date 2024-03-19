<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\AqiWorldLocations;
use App\Models\States;
use App\Models\Cities;
use App\Models\StateAqi;

class StoreCityData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'aqi:store-city-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $currentDate = date('Y/n/j');
        // Get states from countries 33 and 34
        $cities = Cities::whereIn('country_id', [233])->where('id', 122010)->where('state_id', 1456)
            ->select('id', 'name', 'state_id', 'state_code', 'country_id', 'latitude', 'longitude')->get();

        foreach ($cities as $city) {

            // Make request to API for State
            $response = Http::get("https://airnowgovapi.com/weather/get?latitude=".$city->latitude."&longitude=".$city->longitude."&stateCode=".$city->state_code."&maxDistance=50");

            if (empty($response)) {
                $this->info('Record Not Found : ' . $city->name);
                continue;
            }

            $aqiData = $response;

            if(!empty($aqiData)) {

                $date = date('Y-m-d');

                $cityAqiData[] = [
                    'date' => $date,
                    't' => $aqiData['temperature'],
                    'h' => $aqiData['humidity'],
                    'w' => $aqiData['windSpeed'],
                    'wd' => $aqiData['windDirection'],
                    'cloudiness' => $aqiData['cloudiness'],
                    'cloudiness_status' => $aqiData['description'],
                    'city_name' => $city->name ?? null,
                    'city_id' => $city->id ?? null,
                    // 'state_name' => $city->state_code ?? null,
                    'state_id' => $city->state_id,
                    // 'country_name' => $city->country_code ?? null,
                    // 'country_id' => $state->country_id,
                    // 'timezone_name' => $data['timezone'] ?? null,
                    'source' => $data['source'] ?? 'Air Now Govt.',
                    'source_url' => 'https://airnowgovapi.com',
                    // 'status' => $data['status'] ?? 0,
                    // 'is_include' => $data['is_include'] ?? 0,
                    'latitude' => $city->latitude,
                    'longitude' => $city->longitude,
                    // 'tmp_status' => $data['tmp_status'] ?? 0,
                    // 'time_string' => $data['time_string'],
                    // 'last_updated' => $data['last_updated'],
                    // 'utc_datetime' => $data['utc_datetime'] ?? now(),
                ];

                AqiWorldLocations::upsert(
                    $cityAqiData,
                    ['state_id', 'city_id', 'date'/*, 'time'*/],
                    [
                        't', 'h', 'w', 'wd', 'cloudiness', 'cloudiness_status', 'city_name', 'city_id', 'state_id',
                        'source', 'source_url', 'latitude', 'longitude'
                    ]
                );

                $this->info('Done for state : ' . $city->name);
            }
        }

    }
}
