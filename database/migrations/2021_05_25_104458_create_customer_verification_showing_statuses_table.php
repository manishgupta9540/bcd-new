<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerVerificationShowingStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_verification_showing_statuses', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('parent_id')->default(2)->nullable();
            $table->bigInteger('business_id')->nullable();
            $table->bigInteger('coc_id')->nullable();
            $table->bigInteger('hide_by')->nullable();
            $table->dateTime('hide_at')->nullable();
            $table->bigInteger('shown_by')->nullable();
            $table->dateTime('shown_at')->nullable();
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
        Schema::dropIfExists('customer_verification_showing_statuses');
    }
}
