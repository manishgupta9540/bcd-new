<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceVitalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_vitals', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('business_id')->unsigned()->index()->nullable();
            $table->bigInteger('parent_id')->unsigned()->index()->nullable();
            $table->bigInteger('service_type_id')->unsigned()->index()->nullable();
            $table->bigInteger('service_id')->unsigned()->index()->nullable();
            $table->string('vital_name',255)->nullable();
            $table->string('verification_type',255)->nullable();
            $table->tinyInteger('is_multiple_type')->default('0')->comment('1 => Multiple, 0 => No');
            $table->tinyInteger('status')->default('1')->comment('0 => Inactive, 1 => Active');
            $table->string('sort_number',255)->nullable();
            $table->string('type_name',255)->nullable();
           
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
        Schema::dropIfExists('service_vitals');
    }
}
