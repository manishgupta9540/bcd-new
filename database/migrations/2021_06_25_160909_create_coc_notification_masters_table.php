<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCocNotificationMastersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coc_notification_masters', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('parent_id')->index()->unsigned()->nullable();
            $table->bigInteger('business_id')->index()->unsigned()->nullable();
            $table->bigInteger('candidate_id')->index()->unsigned()->nullable();
            $table->text('service_id')->nullable();
            $table->string('source_email',100)->nullable();
            $table->string('source_phone',100)->nullable();
            $table->bigInteger('dest_id')->index()->unsigned()->nullable();
            $table->string('dest_email',100)->nullable();
            $table->string('dest_phone',100)->nullable();
            $table->string('activity_type',100)->nullable();
            $table->bigInteger('created_by')->index()->unsigned()->nullable();
            $table->enum('user_type',['customer','coc','superadmin'])->default('customer');
            $table->enum('status',[0,1])->default(0)->nullable();
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
        Schema::dropIfExists('coc_notifications_masters');
    }
}
