<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\AqiWorldLocations;
use App\Models\States;
use App\Models\Cities;
use App\Models\StateAqi;

class StoreStateData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'aqi:store-state-data';

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
        // $currentDate = date('Y/n/j');
        $currentDate = date('Y/n/j', strtotime('-1 day'));

        // Get states from countries 33 and 34
        $states = States::whereIn('country_id', [233])->select('id','country_id','name', 'iso2')->get();

        foreach ($states as $state) {

            // Make request to API for State
            $response = Http::get("https://airnowgovapi.com/andata/States/". $state->name ."/". $currentDate . ".json");
            //  Alabama/2024/3/13.json
            // dd("https://airnowgovapi.com/andata/States/". $state->name ."/". $currentDate . ".json");

            // dd($response['status'] == 404);

            if (isset($response['status']) && $response['status'] == 404) {

                $this->info('Record Not Found : ' . $state->iso2);
                continue;
                // dd($response['fileWrittenDateTime']);
            }

            $aqiData = $response;
            // ->json();

            if(!empty($aqiData)) {

                $dateString = $aqiData['fileWrittenDateTime'];
                $dateTime = \DateTime::createFromFormat("Ymd\THis\Z", $dateString);
                $date = $dateTime->format("Y-m-d");
                $time = $dateTime->format("H:i:s");

                // dd($formattedDateTime);
                // $date = date('Y-m-d', $dateTime);
                // $time = date('H:i:s', $dateTime);

                $citiesData = $aqiData['reportingAreas'];

                // Process each AQI data and store in the database
                foreach ($citiesData as $data) {

                    $thisCity = key($data);

                    $exactMatchCity = Cities::where('name', $thisCity)->first();

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
                            similar_text($dbCity->name, $thisCity, $similarity);

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

                    foreach ($data as $cityKey => $measurements) {
                        $pm25 = null;
                        $pm10 = null;
                        $ozone = null;

                        foreach ($measurements as $parameter => $value) {
                            if($parameter == 'pm25') {
                                $pm25 = $value;
                            }
                            if($parameter == 'pm10') {
                                $pm10 = $value;
                            }
                            if($parameter == 'ozone') {
                                $ozone = $value;
                            }
                        }

                        $stateAqiData[] = [
                            'date' => $date,
                            'time' => $time,
                            // 'aqi_in' => $data['aqi_in'] ?? null,
                            'aqi' => $data['aqi'] ?? null,
                            'pm25' => $pm25,
                            // 'pm25_status' => $data['parameter'] == 'PM2.5' ? $data['category'] : null,
                            // 'pm1' => ($data['parameter'] == 'PM1' && isset($data['aqi'])) ? $data['aqi'] : null,
                            // 'pm1_status' => $data['parameter'] == 'PM1' ? $data['category'] : null,
                            'pm10' => $pm10,
                            // 'pm10_status' => $data['parameter'] == 'pm10' ? $data['category'] : null,
                            'o3' => $ozone,
                            // 'o3_status' => $data['parameter'] == 'OZONE' ? $data['category'] : null,
                            'location_name' => $data['reportingArea'] ?? null,
                            'station_name' => $data['station_name'] ?? null,
                            'city_name' => $city->name?? null,
                            'city_id' => $city->id?? null,
                            'state_name' => $city->state_code .' - '. $state->name ?? null,
                            'state_id' => $state->id,
                            'country_name' => $city->country_code ?? null,
                            'country_id' => $state->country_id,
                            'timezone_name' => $data['timezone'] ?? null,
                            'source' => $data['source'] ?? 'Air Now Govt.',
                            'source_url' => 'https://airnowgovapi.com/andata/States/',
                            'status' => $data['status'] ?? 0,
                            'is_include' => $data['is_include'] ?? 0,
                            'latitude' => $city->latitude,
                            'longitude' => $city->longitude,
                            'tmp_status' => $data['tmp_status'] ?? 0,
                            // 'time_string' => $data['time_string'],
                            // 'last_updated' => $data['last_updated'],
                            // 'utc_datetime' => $data['utc_datetime'] ?? now(),
                        ];

                        // dd($stateAqiData);
                    }
                }

                // dd($stateAqiData[0]);
                AqiWorldLocations::upsert(
                    $stateAqiData,
                    ['state_id', 'city_id', 'date', 'time'],
                    [
                        'date', 'time',  /*'dev_type', 'function_name', 'is_india', 'uid', 'aqi_in',*/ 'aqi', 'pm25',
                        /*'pm25_status', 'pm1', 'pm1_status',*/ 'pm10', /*'pm10_status', 't', 't_status', 'h', 'h_status',
                        'co', 'co_status', 'dew', 'dew_status', 'no2', 'no2_status',*/ 'o3', /*'o3_status', 'p',
                        'p_status', 'so2', 'so2_status', 'r', 'r_status', 'w', 'w_status', 'wd', 'wd_status',
                        'noise', 'noise_status', 'wg', 'wg_status', 'cloudiness', 'cloudiness_status', 'real_time', 'weather_data', 'forecast',
                        'orignal_realtime', 'orignal_forecast',*/ 'location_name', 'station_name', /*'address', 'address_json',*/ 'city_name', 'city_id', 'state_name',
                        'state_id', /*'country_name',*/ 'country_id', /*'elevation',*/ 'timezone_name', 'source', 'source_url', 'status', 'is_include',
                        'latitude', 'longitude', 'tmp_status', // 'time_string', // 'last_updated', // 'utc_datetime'
                    ]
                );

                $this->info('Done for state : ' . $state->iso2);
            }

        }

        $this->info('AQI data fetched and stored successfully.');
    }
}
