<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddressVerificationDecisionLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('address_verification_decision_logs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('candidate_id')->unsigned()->index()->nullable();
            $table->bigInteger('jaf_id')->unsigned()->index()->nullable();
            $table->enum('stay',['yes','no'])->nullable();
            $table->enum('address_type',['yes','no'])->nullable();
            $table->enum('ownership',['yes','no'])->nullable();
            $table->enum('profile',['yes','no'])->nullable();
            $table->enum('address_proof',['yes','no'])->nullable();
            $table->enum('location',['yes','no'])->nullable();
            $table->enum('signature',['yes','no'])->nullable();
            $table->enum('map_qc',['yes','no'])->nullable();
            $table->tinyInteger('qc_decision')->default(0)->comment('0 => Fail, 1 => Pass')->nullable();
            $table->text('comment')->nullable();
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
        Schema::dropIfExists('address_verification_decision_logs');
    }
}
