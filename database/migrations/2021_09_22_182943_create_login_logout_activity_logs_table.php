<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoginLogoutActivityLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('login_logout_activity_logs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned()->index();
            $table->enum('user_type',['customer','client','user','vendor','guest','candidate','superadmin'])->nullable();
            $table->dateTime('login_at')->nullable();
            $table->dateTime('last_login_activity_at')->nullable();
            $table->dateTime('logout_at')->nullable();
            $table->string('ip_address',50)->nullable();
            $table->string('platform',50)->nullable();
            $table->string('device_type',50)->nullable();
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
        Schema::dropIfExists('login_logout_activity_logs');
    }
}
