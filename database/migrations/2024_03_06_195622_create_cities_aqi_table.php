<?php

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
        Schema::create('cities_aqi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('state_id')->constrained()->onDelete('cascade');
            $table->foreignId('city_id')->constrained()->onDelete('cascade');
            $table->float('pm25');
            $table->float('pm10');
            $table->float('ozone');
            $table->timestamps();
        });


        // OR

        Schema::create('air_quality_reports', function (Blueprint $table) {
            $table->id();
            $table->string('state');
            $table->dateTime('fileWrittenDateTime');
            $table->timestamps();
        });

        Schema::create('reporting_areas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('air_quality_report_id')->constrained()->onDelete('cascade');
            $table->string('area_name');
            $table->float('pm25');
            $table->float('pm10');
            $table->float('ozone');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cities_aqi');

        // OR


        Schema::dropIfExists('reporting_areas');
        Schema::dropIfExists('air_quality_reports');
    }
};
