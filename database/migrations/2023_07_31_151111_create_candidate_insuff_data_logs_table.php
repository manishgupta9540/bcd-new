<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCandidateInsuffDataLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('candidate_insuff_data_logs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('business_id')->unsigned()->index();
            $table->bigInteger('candidate_id')->unsigned()->index();
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->bigInteger('days')->default('0')->nullable();
            $table->bigInteger('days_follow_up')->default('0')->nullable();
            $table->string('jaf_id')->nullable();
            $table->tinyInteger('status')->default('1')->comment('0 => Inactive, 1 => Raise, 2 => Clear');
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
        Schema::dropIfExists('candidate_insuff_data_logs');
    }
}
