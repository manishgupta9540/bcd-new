<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorClockInOutLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_clock_in_out_locations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('vendor_id')->unsigned()->index()->nullable();
            $table->bigInteger('candidate_id')->unsigned()->index()->nullable();
            $table->string('geo_address',255)->nullable();
            $table->string('geo_zipcode',255)->nullable();
            $table->string('geo_city',255)->nullable();
            $table->string('geo_state',255)->nullable();
            $table->string('geo_country',255)->nullable();
            $table->string('geo_latitude',255)->nullable();
            $table->string('geo_longitude',255)->nullable();
            $table->dateTime('clock_in',0);
            $table->dateTime('clock_out',0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vendor_clock_in_out_locations');
    }
}
