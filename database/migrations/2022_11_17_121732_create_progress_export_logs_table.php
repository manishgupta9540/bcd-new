<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProgressExportLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('progress_export_logs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('business_id')->unsigned()->index()->nullable();
            $table->string('duration_type',100)->nullable();
            $table->string('month',100)->nullable();
            $table->string('year',100)->nullable();
            $table->string('customer_id',255)->nullable();
            $table->string('report_type',150)->nullable();
            $table->date('from_date')->nullable();
            $table->date('to_date')->nullable();
            $table->bigInteger('created_by')->unsigned()->index()->nullable();
            $table->tinyInteger('status')->default('0')->nullable();
            $table->string('file_name',150)->nullable();
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
        Schema::dropIfExists('progress_export_logs');
    }
}
