<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillingApprovalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('billing_approvals', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('billing_id')->unsigned()->index()->nullable();
            $table->bigInteger('business_id')->unsigned()->index()->nullable();
            $table->bigInteger('request_sent_by')->unsigned()->index()->nullable();
            $table->dateTime('request_sent_at')->nullable();
            $table->bigInteger('request_cancel_by')->unsigned()->index()->nullable();
            $table->dateTime('request_cancel_at')->nullable();
            $table->bigInteger('request_approve_by')->unsigned()->index()->nullable();
            $table->dateTime('request_approve_at')->nullable();
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
        Schema::dropIfExists('billing_approvals');
    }
}
