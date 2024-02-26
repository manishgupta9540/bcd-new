<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('name',255);
            $table->string('description',255)->nullable();
            $table->integer('business_id')->unsigned()->index();
            $table->integer('candidate_id')->unsigned()->index()->nullable();
            $table->integer('job_id')->unsigned()->index();
            $table->integer('created_by')->unsigned(); 
            $table->integer('assigned_at')->unsigned()->nullable();
            $table->integer('assigned_by')->unsigned()->nullable();
            $table->integer('assigned_to')->unsigned()->nullable();
            $table->enum('priority',['normal','high','low'])->default('normal');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->integer('updated_by')->unsigned();
            $table->tinyInteger('is_completed')->default('0');
            $table->dateTime('completed_at')->nullable();
            $table->tinyInteger('is_approved')->default('0');
            $table->integer('approved_by')->unsigned()->nullable();
            $table->dateTime('approved_at')->nullable();
            $table->tinyInteger('is_assigned')->default('0');
            $table->tinyInteger('status')->default('2');
            $table->tinyInteger('task_status')->nullable();
            $table->dateTime('started_at')->nullable();
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
        Schema::dropIfExists('tasks');
    }
}
