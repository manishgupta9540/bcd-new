<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserOnboardingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_onboardings', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('parent_id')->unsigned()->index()->nullable();
            $table->bigInteger('business_id')->unsigned()->index()->nullable();
            $table->bigInteger('user_id')->unsigned()->index()->nullable();
            $table->string('session_id',255)->nullable();
            $table->text('step_1')->nullable();
            $table->text('step_2')->nullable();
            $table->text('step_3')->nullable();
            $table->text('step_4')->nullable();
            $table->text('step_5')->nullable();
            $table->text('step_6')->nullable();
            $table->enum('user_type',['customer','client','user','vendor','candidate','guest'])->default('client')->nullable();
            $table->enum('status',['draft','completed'])->default('draft')->nullable();
            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('updated_by')->nullable();
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
        Schema::dropIfExists('user_onboardings');
    }
}
