<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDigitalAddressVerificationLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('digital_address_verification_logs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('business_id')->unsigned()->index();
            $table->bigInteger('candidate_id')->unsigned()->index();
            $table->bigInteger('jaf_id')->unsigned()->index();
            $table->bigInteger('assigned_by')->unsigned()->index();
            $table->tinyInteger('status')->unsigned()->nullable()->default('1');
            $table->string('type',100)->nullable();
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
        Schema::dropIfExists('digital_address_verification_logs');
    }
}
