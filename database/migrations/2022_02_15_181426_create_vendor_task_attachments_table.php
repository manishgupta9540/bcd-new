<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorTaskAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
    **/
    public function up()
    {
        Schema::create('vendor_task_attachments', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('task_id')->unsigned()->index()->nullable();
            $table->bigInteger('business_id')->unsigned()->index()->nullable();
            $table->bigInteger('vendor_id')->unsigned()->index()->nullable();
            $table->string('file_name',255)->nullable();
            $table->string('file_title',255)->nullable();
            $table->tinyInteger('status')->default('1');
            $table->tinyInteger('is_deleted')->default('0');
            $table->dateTime('deleted_at')->nullable();
            $table->string('deleted_by',20)->nullable();
            $table->string('created_by',20)->nullable();
            $table->string('updated_by',20)->nullable();
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
        Schema::dropIfExists('vendor_task_attachments');
    }
}
