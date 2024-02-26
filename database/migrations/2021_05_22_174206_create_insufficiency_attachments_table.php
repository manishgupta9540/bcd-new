<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInsufficiencyAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('insufficiency_attachments', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('parent_id')->nullable();
            $table->bigInteger('business_id')->nullable();
            $table->bigInteger('candidate_id')->nullable();
            $table->bigInteger('coc_id')->nullable();
            $table->bigInteger('service_id')->nullable();
            $table->bigInteger('jaf_form_data_id')->nullable();
            $table->bigInteger('item_number')->nullable();
            $table->enum('status',['raise','removed'])->nullable();
            $table->text('file_name')->nullable();
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
        Schema::dropIfExists('insufficiency_attachments');
    }
}
