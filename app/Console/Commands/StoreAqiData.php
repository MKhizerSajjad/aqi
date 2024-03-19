<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\AqiWorldLocations;
use App\Models\States;
use App\Models\Cities;
use App\Models\StateAqi;

class StoreAqiData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'aqi:all-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generic data with functionality of update not duplicate record';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Get states from countries 33 and 34
        $states = States::whereIn('country_id', [233])->select('id','country_id','name', 'iso2')->get();

        foreach ($states as $state) {

            // Make request to API for iso2
            $response = Http::get('https://airnowgovapi.com/reportingarea/get_state', [
                'state_code' => $state->iso2,
            ]);

            // $reqUrl = $url."&leagueid=" . $systemId;
            // $this->info($reqUrl);
            // $matches = $this->callAPI('GET', $reqUrl, [
            //     "Content-Type: application/json"
            // ]);

            $aqiData = $response->json();

            if(!empty($aqiData)) {

                // Process each AQI data and store in the database
                foreach ($aqiData as $data) {

                    $exactMatchCity = Cities::where('name', $data['reportingArea'])->first();

                    if ($exactMatchCity) {
                        $city = $exactMatchCity;
                    } else {

                        $matchingCities = Cities::whereIn('country_id', [233])
                                        ->where('state_id', $state->id)
                                        ->get(['id', 'name']);

                        $maxSimilarity = 0;
                        $matchedCity = null;

                        // Iterate through all cities to find a similar match
                        foreach ($matchingCities as $dbCity) {

                            // Calculate similarity between city names
                            similar_text($dbCity->name, $data['reportingArea'], $similarity);

                            // Update the matched city if similarity is higher than the current maximum
                            if ($similarity > $maxSimilarity) {
                                $maxSimilarity = $similarity;
                                $matchedCity = $dbCity;
                            }
                        }

                        // If a match with similarity above the threshold is found, use that city
                        if ($maxSimilarity > 75) { // Adjust the threshold as needed
                            $city = $matchedCity;
                        } else {
                            continue;
                            // If no match found above the threshold, create a new city
                            // $city = Cities::create([
                            //     'name' => $data['reportingArea'],
                            //     'latitude' => $data['latitude'],
                            //     'longitude' => $data['longitude'],
                            // ]);
                        }

                    }

                    // $stateAqiData[] = [
                    //     'state_id' => $state->id,
                    //     'city_id' => $city->id,
                    //     'date' => date('Y-m-d', strtotime($data['validDate'])),
                    //     'time' => $data['time'],
                    //     'timezone' => $data['timezone'],
                    //     'dev_type' => $data['dataType'],
                    //     'reporting_area' => $data['reportingArea'],
                    //     'longitude' => $data['longitude'],
                    //     'parameter' => $data['parameter'],
                    //     'aqi' => $data['aqi'] ?? 0,
                    //     'category' => $data['category'],
                    //     'created_at' => now(),
                    //     'updated_at' => now(),
                    // ];

                    // AqiWorldLocations::create([
                    //     'date' => date('Y-m-d', strtotime($data['validDate'])),
                    //     'time' => date('H:i:s', $data['time']) ,
                    //     'dev_type' => $data['dataType'] ?? null,
                    //     'function_name' => $data['function_name'] ?? null,
                    //     'is_india' => $data['is_india'] ?? null,
                    //     'uid' => $data['uid'] ?? null]);

                    $stateAqiData[] = [
                        'date' => date('Y-m-d', strtotime($data['validDate'])),
                        'time' => $data['time'],
                        'dev_type' => $data['dataType'] ?? null,
                        // 'function_name' => $data['function_name'] ?? null,
                        // 'is_india' => $data['is_india'] ?? 0,
                        // 'uid' => $data['uid'] ?? null,
                        // 'aqi_in' => $data['aqi_in'] ?? null,
                        'aqi' => $data['aqi'] ?? null,
                        'pm25' => ($data['parameter'] == 'PM2.5' && isset($data['aqi'])) ? $data['aqi'] : null,
                        'pm25_status' => $data['parameter'] == 'PM2.5' ? $data['category'] : null,
                        'pm1' => ($data['parameter'] == 'PM1' && isset($data['aqi'])) ? $data['aqi'] : null,
                        'pm1_status' => $data['parameter'] == 'PM1' ? $data['category'] : null,
                        'pm10' => ($data['parameter'] && isset($data['aqi'])) == 'pm10' ? $data['aqi'] : null,
                        'pm10_status' => $data['parameter'] == 'pm10' ? $data['category'] : null,
                        't' => $data['t'] ?? null,
                        't_status' => $data['t'] ?? null,
                        'h' => $data['h'] ?? null,
                        'h_status' => $data['h'] ?? null,
                        'co' => $data['co'] ?? null,
                        'co_status' => $data['co'] ?? null,
                        'dew' => $data['dew'] ?? null,
                        'dew_status' => $data['dew'] ?? null,
                        'no2' => $data['no2'] ?? null,
                        'no2_status' => $data['no2'] ?? null,
                        'o3' => ($data['parameter'] == 'OZONE' && isset($data['aqi'])) ? $data['aqi'] : null,
                        'o3_status' => $data['parameter'] == 'OZONE' ? $data['category'] : null,
                        // 'p' => $data['p'] ?? null,
                        // 'p_status' => $data['p'] ?? null,
                        // 'so2' => $data['so2'] ?? null,
                        // 'so2_status' => $data['so2'] ?? null,
                        // 'r' => $data['r'] ?? null,
                        // 'r_status' => $data['r'] ?? null,
                        // 'w' => $data['w'] ?? null,
                        // 'w_status' => $data['w'] ?? null,
                        // 'wd' => $data['wd'] ?? null,
                        // 'wd_status' => $data['wd'] ?? null,
                        // 'noise' => $data['noise'] ?? null,
                        // 'noise_status' => $data['noise'] ?? null,
                        // 'wg' => $data['wg'] ?? null,
                        // 'wg_status' => $data['wg'] ?? null,
                        // 'cloudiness' => $data['cloudiness'] ?? null,
                        // 'cloudiness_status' => $data['cloudiness'] ?? null,
                        // 'real_time' => $data['real_time'] ?? null,
                        // 'weather_data' => $data['weather_data'] ?? null,
                        // 'forecast' => $data['forecast'] ?? null,
                        // 'orignal_realtime' => $data['orignal_realtime'] ?? null,
                        // 'orignal_forecast' => $data['orignal_forecast'] ?? null,
                        'location_name' => $data['reportingArea'] ?? null,
                        'station_name' => $data['station_name'] ?? null,
                        // 'address' => $data['address'] ?? null,
                        // 'address_json' => $data['address_json'] ?? null,
                        // 'city_name' => $data['city_name'] ?? null,
                        'city_id' => $city->id?? null,
                        'state_name' => $data['state_name'] ?? null,
                        'state_id' => $state->id,
                        // 'country_name' => $data['country_name'] ?? null,
                        'country_id' => $state->country_id,
                        'elevation' => $data['elevation'] ?? null,
                        'timezone_name' => $data['timezone'] ?? null,
                        'source' => $data['source'] ?? 'Air Now Govt.',
                        'source_url' => 'https://airnowgovapi.com',
                        'status' => $data['status'] ?? 0,
                        'is_include' => $data['is_include'] ?? 0,
                        'latitude' => $data['latitude'],
                        'longitude' => $data['longitude'],
                        'tmp_status' => $data['tmp_status'] ?? 0,
                        // 'time_string' => $data['time_string'],
                        // 'last_updated' => $data['last_updated'],
                        // 'utc_datetime' => $data['utc_datetime'] ?? now(),
                    ];

                    // dd($stateAqiData);
                }

                AqiWorldLocations::upsert(
                    $stateAqiData,
                    ['state_id', 'city_id', 'date', 'time'],
                    [
                        'date', 'time', 'dev_type', /*'function_name', 'is_india', 'uid', 'aqi_in',*/ 'aqi', 'pm25',
                        'pm25_status', 'pm1', 'pm1_status', 'pm10', 'pm10_status', 't', 't_status', 'h', 'h_status',
                        'co', 'co_status', 'dew', 'dew_status', 'no2', 'no2_status', 'o3', 'o3_status', 'p',
                        /*'p_status', 'so2', 'so2_status', 'r', 'r_status', 'w', 'w_status', 'wd', 'wd_status',
                        'noise', 'noise_status', 'wg', 'wg_status', 'cloudiness', 'cloudiness_status', 'real_time', 'weather_data', 'forecast',
                        'orignal_realtime', 'orignal_forecast',*/ 'location_name', 'station_name', /*'address', 'address_json', 'city_name',*/ 'city_id', 'state_name',
                        'state_id', /*'country_name',*/ 'country_id', 'elevation', 'timezone_name', 'source', 'source_url', 'status', 'is_include',
                        'latitude', 'longitude', 'tmp_status', // 'time_string', // 'last_updated', // 'utc_datetime'
                    ]
                );

                $this->info('Done for state : ' . $state->iso2);
            }

        }

        $this->info('AQI data fetched and stored successfully.');
    }
}
