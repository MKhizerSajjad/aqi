<?php
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('aqi_world_locations', function (Blueprint $table) {
            $table->id();
            $table->date('date')->nullable();
            $table->time('time')->nullable();
            $table->integer('dev_type')->nullable()->default(0);
            $table->string('function_name')->nullable();
            $table->integer('is_india')->default(0);
            $table->string('uid')->nullable();
            $table->string('aqi_in')->nullable();
            $table->string('aqi')->nullable();
            $table->string('pm25')->nullable(); // PM2.5
            $table->string('pm25_status')->nullable(); // PM2.5
            $table->string('pm1')->nullable();
            $table->string('pm1_status')->nullable();
            $table->string('pm10')->nullable(); // PM10
            $table->string('pm10_status')->nullable(); // PM10
            $table->string('t')->nullable();  // temperature
            $table->string('t_status')->nullable();  // temperature
            $table->string('h')->nullable();  // humidity
            $table->string('h_status')->nullable();  // humidity
            $table->string('co')->nullable(); // Carbon monoxide
            $table->string('co_status')->nullable(); // Carbon monoxide
            $table->string('dew')->nullable(); // water vapours in air
            $table->string('dew_status')->nullable(); // water vapours in air
            $table->string('no2')->nullable();
            $table->string('no2_status')->nullable();
            $table->string('o3')->nullable(); // OZONE
            $table->string('o3_status')->nullable(); // OZONE
            $table->string('p')->nullable(); // pressure
            $table->string('p_status')->nullable(); // pressure
            $table->string('so2')->nullable();
            $table->string('so2_status')->nullable();
            $table->string('r')->nullable(); // rainfall
            $table->string('r_status')->nullable(); // rainfall
            $table->string('w')->nullable(); // Wind speed
            $table->string('w_status')->nullable(); // Wind speed
            $table->string('wd')->nullable(); // wind direction
            $table->string('wd_status')->nullable(); // wind direction
            $table->string('noise')->nullable();
            $table->string('noise_status')->nullable();
            $table->string('wg')->nullable(); // Wind gust
            $table->string('wg_status')->nullable(); // Wind gust
            $table->longText('cloudiness')->nullable();
            $table->longText('cloudiness_status')->nullable();
            $table->longText('real_time')->nullable();
            $table->longText('weather_data')->nullable();
            $table->longText('forecast')->nullable();
            $table->text('orignal_realtime')->nullable();
            $table->text('orignal_forecast')->nullable();
            $table->string('location_name')->nullable();
            $table->string('station_name')->nullable();
            $table->string('address')->nullable();
            $table->string('address_json')->nullable();
            $table->string('city_name')->nullable();
            $table->string('city_id')->nullable();
            $table->string('state_name')->nullable();
            $table->string('state_id')->nullable();
            $table->string('country_name')->default('India');
            $table->string('country_id')->nullable();
            $table->string('elevation')->nullable();
            $table->string('timezone_name')->nullable();
            $table->string('source')->nullable();
            $table->string('source_url')->nullable();
            $table->integer('status')->default(0);
            $table->integer('is_include')->default(0);
            $table->double('latitude', 8, 4)->nullable();
            $table->double('longitude', 8, 4)->nullable();
            $table->integer('tmp_status')->default(0);
            $table->string('time_string')->nullable();
            $table->string('last_updated')->nullable();
            $table->timestamp('utc_datetime')->default(DB::raw('CURRENT_TIMESTAMP'));
            // $table->dateTime('created_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
            // $table->timestamps();
            $table->timestamps();

            // Add a composite unique constraint
            $table->unique(['state_id', 'city_id', 'date', 'time']);

            // visibility
            // temperatureFeelsLike
            // temperatureMinimum
            // temperatureMaximum
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aqi_world_locations');
    }
};
