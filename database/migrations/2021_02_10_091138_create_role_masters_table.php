<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoleMastersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role_masters', function (Blueprint $table) {
            $table->id();
            $table->integer('business_id')->index()->unsigned();    
            $table->integer('parent_id')->index()->unsigned();
            $table->integer('user_id')->index()->unsigned();
            $table->string('role_type',50)->nullable();     
            $table->string('role',50)->nullable();
            $table->enum('status',[0,1,2])->default(0);
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
        Schema::dropIfExists('role_masters');
    }
}
