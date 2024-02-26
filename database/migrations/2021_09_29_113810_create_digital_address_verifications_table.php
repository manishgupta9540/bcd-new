<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDigitalAddressVerificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('digital_address_verifications', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('business_id')->unsigned()->index()->nullable();
            $table->bigInteger('candidate_id')->unsigned()->index()->nullable();
            $table->bigInteger('jaf_id')->unsigned()->index()->nullable();
            $table->bigInteger('assigned_by_id')->unsigned()->index()->nullable();
            $table->enum('assigned_by',['admin','client','vendor'])->nullable();
            $table->enum('status',['0','1'])->default('0')->nullable();
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
        Schema::dropIfExists('digital_address_verifications');
    }
}
