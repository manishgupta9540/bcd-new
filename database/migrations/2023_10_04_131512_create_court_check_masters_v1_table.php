<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourtCheckMastersV1Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('court_check_masters_v1', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('parent_id')->unsigned()->index()->nullable();
            $table->bigInteger('business_id')->unsigned()->index()->nullable();
            $table->string('name',100)->nullable();
            $table->string('father_name',100)->nullable();
            $table->text('address')->nullable();
            $table->string('type',100)->nullable();
            $table->string('state',100)->nullable();
            $table->text('cases')->nullable();
            $table->enum('platform_reference',['web','api'])->default('web')->nullable();
            $table->tinyInteger('is_api_verified')->default(1)->nullable();
            $table->bigInteger('created_by')->unsigned()->index()->nullable();
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
        Schema::dropIfExists('court_check_masters_v1');
    }
}
