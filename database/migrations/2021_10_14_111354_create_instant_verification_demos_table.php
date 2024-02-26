<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstantVerificationDemosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('instant_verification_demos', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('business_id')->index()->nullable();
            $table->bigInteger('parent_id')->nullable();
            $table->string('unique_id',100)->nullable();
            $table->bigInteger('service_id')->unsigned()->index()->nullable();
            $table->text('service_data')->nullable();
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
        Schema::dropIfExists('instant_verification_demos');
    }
}
