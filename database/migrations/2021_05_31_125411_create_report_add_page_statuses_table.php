<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportAddPageStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('report_add_page_statuses', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('parent_id')->default(2)->nullable();
            $table->bigInteger('business_id')->nullable();
            $table->bigInteger('coc_id')->nullable();
            $table->enum('status',['enable','disable'])->nullable();
            $table->bigInteger('disable_by')->nullable();
            // $table->dateTime('disable_at')->nullable();
            $table->bigInteger('enable_by')->nullable();
            // $table->dateTime('shown_at')->nullable();
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
        Schema::dropIfExists('report_add_page_satuses');
    }
}
