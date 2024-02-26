<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJafCheckApiJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jaf_check_api_jobs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('parent_id')->nullable()->unsigned();
            $table->bigInteger('business_id')->nullable()->unsigned();
            $table->bigInteger('candidate_id')->nullable()->unsigned();
            $table->bigInteger('jaf_id')->nullable()->unsigned();
            $table->bigInteger('service_id')->nullable()->unsigned();
            $table->enum('is_insuff',[0,1])->default(0);
            $table->bigInteger('raised_by')->nullable()->unsigned();
            $table->string('mail_from')->nullable();
            $table->text('mail_to')->nullable();
            $table->enum('api_status',[0,1])->default(0);
            $table->enum('is_executed',[0,1])->default(0);
            $table->timestamp('executed_at')->nullable();
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
        Schema::dropIfExists('jaf_check_api_jobs');
    }
}
