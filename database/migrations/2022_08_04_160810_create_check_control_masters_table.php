<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCheckControlMastersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('check_control_masters', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('check_control_coc_id')->nullable();
            $table->bigInteger('service_id')->unsigned()->index()->nullable();
            $table->bigInteger('service_input_id')->unsigned()->index()->nullable();
            $table->enum('is_required',['0','1'])->default('1');
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
        Schema::dropIfExists('check_control_masters');
    }
}
