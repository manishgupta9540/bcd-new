<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCandidateHoldStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('candidate_hold_statuses', function (Blueprint $table) {
            $table->id();
            $table->integer('business_id')->unsigned()->index();
            $table->integer('candidate_id')->unsigned()->index();
            $table->enum('status',[0,1])->default(0);
            $table->bigInteger('hold_by')->nullable();
            $table->dateTime('hold_at')->nullable();
            $table->bigInteger('hold_remove_by')->nullable();
            $table->dateTime('hold_remove_at')->nullable();
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
        Schema::dropIfExists('candidate_hold_statuses');
    }
}
