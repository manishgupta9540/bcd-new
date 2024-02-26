<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillingApprovalAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('billing_approval_attachments', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('billing_id')->unsigned()->index()->nullable();
            $table->bigInteger('billing_approval_id')->unsigned()->index()->nullable();
            $table->bigInteger('business_id')->unsigned()->index()->nullable();
            $table->enum('request_type',['sent','cancel','approve'])->nullable();
            $table->bigInteger('created_by')->unsigned()->index()->nullable();
            $table->enum('user_type',['customer','coc'])->nullable();
            $table->text('file_name')->nullable();
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
        Schema::dropIfExists('billing_approval_attachments');
    }
}
