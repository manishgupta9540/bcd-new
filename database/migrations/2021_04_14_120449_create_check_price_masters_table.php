<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCheckPriceMastersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('check_price_masters', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('service_id')->nullable();
            $table->decimal('price',10,2)->nullable();
            $table->bigInteger('business_id')->default(2)->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->enum('used_by',['superadmin','user'])->default('superadmin')->nullable();
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
        Schema::dropIfExists('check_price_masters');
    }
}
