<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGuestVerificationServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guest_verification_services', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('parent_id')->nullable();
            $table->bigInteger('business_id')->nullable();
            $table->bigInteger('candidate_id')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->bigInteger('gv_id')->nullable();
            $table->bigInteger('service_id')->nullable();
            $table->text('service_number')->nullable();
            $table->decimal('price',10,2)->nullable();
            $table->enum('status',['success','failed'])->nullable();
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
        Schema::dropIfExists('guest_verification_services');
    }
}
