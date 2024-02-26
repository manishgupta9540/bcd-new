<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlockUserLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('block_user_logs', function (Blueprint $table) {
            $table->id();
            $table->text('email')->nullable();
            $table->timestamp('hit_time')->nullable();
            $table->bigInteger('attempts')->unsigned()->nullable();
            $table->enum('is_blocked',['0','1'])->default('0');
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
        Schema::dropIfExists('block_user_logs');
    }
}
