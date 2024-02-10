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
        Schema::create('routes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('origin_facility_id')->unsigned();
            $table->bigInteger('destination_facility_id')->unsigned();
            $table->char('estimated_travel_time');
            $table->char('distance');
            $table->timestamps();

            $table->foreign('origin_facility_id')->references('id')->on('facilities')->onDelete('cascade');
            $table->foreign('destination_facility_id')->references('id')->on('facilities')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('routes');
    }
};
