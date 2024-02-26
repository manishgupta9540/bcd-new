<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGuestInstantCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guest_instant_carts', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('parent_id')->unsigned()->index()->nullable();
            $table->bigInteger('business_id')->unsigned()->index()->nullable();
            $table->bigInteger('user_id')->unsigned()->index()->nullable();
            $table->bigInteger('giv_m_id')->unsigned()->index()->nullable();
            $table->bigInteger('service_id')->unsigned()->index()->nullable();
            $table->integer('number_of_verification')->unsigned()->nullable();
            $table->string('currency',50)->default('INR')->nullable();
            $table->decimal('sub_total',10,2)->nullable();
            $table->decimal('total_price',10,2)->nullable();
            $table->enum('status',['success','failed'])->nullable();
            $table->text('zip_name')->nullable();
            // $table->tinyInteger('is_deleted')->default(0)->nullable();
            // $table->bigInteger('deleted_by')->unsigned()->index()->nullable();
            // $table->dateTime('deleted_at')->nullable();
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
        Schema::dropIfExists('guest_instant_carts');
    }
}
