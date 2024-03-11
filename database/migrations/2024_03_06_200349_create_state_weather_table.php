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
        Schema::create('state_weather', function (Blueprint $table) {
            $table->id();
            $table->float('temperature');
            $table->string('category');
            $table->string('description');
            $table->float('visibility');
            $table->float('windSpeed');
            $table->float('windDirection');
            $table->float('windGust');
            $table->float('temperatureFeelsLike');
            $table->float('temperatureMinimum');
            $table->float('temperatureMaximum');
            $table->float('pressure');
            $table->float('humidity');
            $table->float('cloudiness');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('state_weather');
    }
};
