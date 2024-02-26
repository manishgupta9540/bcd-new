<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJafLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jaf_logs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('parent_id')->index()->nullable();
            $table->bigInteger('business_id')->index()->nullable();
            $table->bigInteger('candidate_id')->index()->nullable();
            $table->bigInteger('created_by')->index()->nullable();
            $table->enum('user_type',['customer','coc','superadmin'])->nullable();
            $table->enum('activity_type',['jaf-update'])->nullable();
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
        Schema::dropIfExists('jaf_logs');
    }
}
