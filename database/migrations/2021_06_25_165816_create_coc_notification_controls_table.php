<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCocNotificationControlsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coc_notification_controls', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('parent_id')->index()->unsigned()->default(2)->nullable();
            $table->bigInteger('business_id')->index()->unsigned()->nullable();
            $table->bigInteger('coc_id')->index()->unsigned()->nullable();
            $table->bigInteger('hide_by')->index()->unsigned()->nullable();
            $table->dateTime('hide_at')->nullable();
            $table->bigInteger('shown_by')->index()->unsigned()->nullable();
            $table->dateTime('shown_at')->nullable();
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
        Schema::dropIfExists('coc_notification_controls');
    }
}
