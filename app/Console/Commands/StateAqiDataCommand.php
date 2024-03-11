<?php

namespace App\Console\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\States;
use App\Models\Cities;
use App\Models\StateAqi;

class StateAqiDataCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'aqi:fetch-and-store';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetches and stores AQI data for states';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Get states from countries 33 and 34
        $states = States::whereIn('country_id', [233])->select('id','country_id','name', 'iso2')->get();

        foreach ($states as $state) {

            $this->info('Done for state : ' . $state->iso2);
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

                    $stateAqiData[] = [
                        'state_id' => $state->id,
                        'city_id' => $city->id,
                        'valid_date' => date('Y-m-d', strtotime($data['validDate'])),
                        'time' => $data['time'],
                        'timezone' => $data['timezone'],
                        'data_type' => $data['dataType'],
                        'reporting_area' => $data['reportingArea'],
                        'latitude' => $data['latitude'],
                        'longitude' => $data['longitude'],
                        'parameter' => $data['parameter'],
                        'aqi' => $data['aqi'] ?? 0,
                        'category' => $data['category'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                StateAqi::upsert($stateAqiData, ['state_id', 'city_id', 'valid_date', 'time', 'parameter'], [
                    'timezone',
                    'data_type',
                    'reporting_area',
                    'latitude',
                    'longitude',
                    'aqi',
                    'category',
                    'created_at',
                    'updated_at',
                ]);

            }

        }

        $this->info('AQI data fetched and stored successfully.');
    }
}
