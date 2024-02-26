<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBatchMastersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('batch_masters', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('business_id')->unsigned()->index();
            $table->bigInteger('user_id')->unsigned()->index();
            $table->string('batch_name',200)->nullable();
            $table->bigInteger('customer_id')->unsigned()->index();
            $table->bigInteger('sla_id')->unsigned()->index();
            $table->bigInteger('no_of_candidates');
            $table->string('file_name',255)->nullable();
            $table->string('tat',100)->nullable();
            $table->enum('status',[0,1,2])->default(1);
            $table->bigInteger('created_by')->nullable();
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
        Schema::dropIfExists('batch_masters');
    }
} 
