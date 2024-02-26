<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJafItemAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jaf_item_attachments', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('jaf_id')->unsigned()->index();
            $table->bigInteger('candidate_id')->unsigned()->index();
            $table->bigInteger('business_id')->unsigned()->index();
            $table->string('file_name',255)->nullable();
            $table->string('file_title',255)->nullable();
            $table->enum('	attachment_type',['main','supporting'])->default('main');
            $table->string('status',255)->nullable();
            $table->bigInteger('updated_by')->unsigned()->index();
            $table->tinyInteger('is_temp')->nullable();
            $table->integer('order_numbers')->nullable();
            $table->tinyInteger('is_deleted')->nullable();
            $table->dateTime('deleted_at')->nullable();
            $table->bigInteger('deleted_by')->nullable();
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
        Schema::dropIfExists('jaf_item_attachments');
    }
}
