<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGuestServiceFormInputsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guest_service_form_inputs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('service_id')->unsigned()->index()->nullable();
            $table->string('label_name')->nullable();
            $table->tinyInteger('status')->default(1)->nullable();
            $table->bigInteger('created_by')->unsigned()->index()->nullable();
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
        Schema::dropIfExists('guest_service_form_inputs');
    }
}
