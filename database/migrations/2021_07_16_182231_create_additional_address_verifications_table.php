<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdditionalAddressVerificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('additional_address_verifications', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('parent_id')->index()->unsigned()->nullable();
            $table->bigInteger('business_id')->index()->unsigned()->nullable();
            $table->bigInteger('candidate_id')->index()->unsigned()->nullable();
            $table->bigInteger('report_item_id')->index()->unsigned()->nullable();
            $table->string('contact_person_name',255)->nullable();
            $table->string('contact_contact_no',255)->nullable();
            $table->string('relation_with_associate',255)->nullable();
            $table->string('residence_status',255)->nullable();
            $table->string('locality',255)->nullable();
            $table->string('mode_of_verification',255)->nullable();
            $table->string('comments',255)->nullable();
            $table->string('remarks',100)->nullable();
            $table->string('verified_by',255)->nullable();
            $table->bigInteger('created_by')->index()->unsigned()->nullable();
            $table->bigInteger('updated_by')->index()->unsigned()->nullable();
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
        Schema::dropIfExists('additional_address_verifications');
    }
}
