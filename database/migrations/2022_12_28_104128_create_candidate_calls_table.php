<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCandidateCallsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('candidate_calls', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('business_id')->unsigned()->index();
            $table->bigInteger('candidate_id')->unsigned()->index();
            $table->string('person_name',150);
            $table->text('remarks')->nullable();
            $table->datetime('start_date');
            $table->datetime('end_date');
            $table->string('service_id',150);
            $table->bigInteger('created_by')->unsigned()->index()->nullable();
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
        Schema::dropIfExists('candidate_calls');
    }
}
