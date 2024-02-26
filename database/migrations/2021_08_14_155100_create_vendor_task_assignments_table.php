<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorTaskAssignmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_task_assignments', function (Blueprint $table) {
            $table->id();
            $table->integer('parent_id')->unsigned()->index();
            $table->integer('business_id')->unsigned()->index();
            $table->integer('candidate_id')->unsigned()->index();
            $table->integer('vendor_task_id')->unsigned()->index();
            $table->integer('service_id')->unsigned()->index();
            $table->integer('vendor_sla_id')->unsigned()->index();
            $table->integer('no_of_verification')->unsigned()->index();
            $table->enum('status',['0','1','2'])->default('1');
            $table->integer('assigned_to')->unsigned()->nullable();
            $table->dateTime('assigned_at')->nullable();
            $table->integer('assigned_by')->unsigned()->nullable();
            $table->integer('reassigned_to')->unsigned()->nullable();
            $table->dateTime('reassigned_at')->nullable();
            $table->integer('reassigned_by')->unsigned()->nullable();
            $table->integer('updated_by')->unsigned();
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
        Schema::dropIfExists('vendor_task_assignments');
    }
}
