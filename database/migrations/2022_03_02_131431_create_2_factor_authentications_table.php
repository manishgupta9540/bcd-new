<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Create2FactorAuthenticationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('2_factor_authentications', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned()->index()->nullable();
            $table->string('email',200)->nullable();
            $table->integer('otp')->nullable();
            $table->enum('status',['0','1'])->default('0')->comment("0 => not verified, 1 => verified");
            $table->dateTime('email_send_at', 0);
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
        Schema::dropIfExists('2_factor_authentications');
    }
}
