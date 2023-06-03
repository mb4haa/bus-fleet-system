<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::create('trip_stations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trip_id')->constrained('bus_trips')->onDelete('CASCADE')->onUpdate('CASCADE');
            $table->foreignId('station_id')->constrained('stations')->onDelete('CASCADE')->onUpdate('CASCADE');
            $table->integer('station_order');
        });
    }

    
    public function down(): void
    {
        Schema::dropIfExists('trip_stations');
    }
};
