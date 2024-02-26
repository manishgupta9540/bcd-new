<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUpiCheckMastersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('upi_check_masters', function (Blueprint $table) {
            $table->id();
            $table->string('upi_id',100)->nullable();
            $table->string('name',100)->nullable();
            $table->enum('platform_reference',['web','api'])->default('web')->nullable();
            $table->tinyInteger('is_api_verified')->default(1)->nullable();
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
        Schema::dropIfExists('upi_check_masters');
    }
}
