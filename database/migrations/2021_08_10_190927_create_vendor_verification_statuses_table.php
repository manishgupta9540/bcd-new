<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorVerificationStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_verification_statuses', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('parent_id')->unsigned()->index();
            $table->bigInteger('business_id')->unsigned()->index();
            $table->bigInteger('candidate_id')->unsigned()->index();
            $table->bigInteger('vendor_task_id')->unsigned()->index();
            $table->bigInteger('vendor_verification_data_id')->unsigned()->index();
            $table->bigInteger('service_id')->unsigned()->index();
            $table->bigInteger('vendor_sla_id')->unsigned()->index();
            $table->bigInteger('no_of_verification')->unsigned()->index();
            $table->string('remarks',255)->nullable();
            $table->enum('status',['done','unable_to_verify'])->nullable();
            $table->bigInteger('created_by')->nullable();
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
        Schema::dropIfExists('vendor_verification_statuses');
    }
}