<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCandidateInsuffDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('candidate_insuff_data', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('insuff_log_id')->unsigned()->index();
            $table->bigInteger('jaf_id')->unsigned()->index();
            $table->bigInteger('candidate_id')->unsigned()->index();
            $table->text('notes')->nullable();
            $table->text('attachment')->nullable();
            $table->text('zip_name')->nullable();
            $table->enum('file_platform',['web','s3'])->default('web')->nullable();
            $table->date('start_date')->nullable();
            $table->bigInteger('days')->default('0')->nullable();
            $table->bigInteger('days_follow_up')->default('0')->nullable();
            $table->bigInteger('created_by')->unsigned()->index()->nullable();
            $table->bigInteger('updated_by')->unsigned()->index()->nullable();
            $table->tinyInteger('status')->default('0')->comment('0 => Raise, 1 => Clear');
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
        Schema::dropIfExists('candidate_insuff_data');
    }
}
