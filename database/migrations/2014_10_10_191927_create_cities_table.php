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
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            // $table->unsignedInteger('state_id')->index();
            $table->foreignId('state_id')->constrained()->onDelete('cascade');
            $table->string('state_code');
            $table->foreignId('country_id')->constrained()->onDelete('cascade');
            // $table->unsignedInteger('country_id')->index();
            $table->string('country_code', 2);
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->boolean('flag')->default(false);
            $table->string('wikiDataId')->nullable()->comment('Rapid API GeoDB Cities');
            $table->timestamps();
            // $table->index('state_id');
            // $table->index('country_id');
            // $table->foreign('state_id')->references('id')->on('states')->onDelete('cascade');
            // $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
            // $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cities');
    }
};
