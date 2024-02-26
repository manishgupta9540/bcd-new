<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActionMastersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('action_masters', function (Blueprint $table) {
            $table->id();
            $table->integer('parent_id')->unsigned();
            $table->string('action',100)->nullable();
            $table->string('action_title',50);
            $table->string('route_group',50)->nullable();
            $table->enum('status', ['0', '1'])->default('1');
            $table->enum('show_in_menu', ['0', '1'])->default('1');
            $table->enum('show_in_permission', ['0', '1'])->default('0');
            $table->string('icon',100)->nullable();
            $table->integer('display_order')->unsigned()->nullable();
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
        Schema::dropIfExists('action_masters');
    }
}
