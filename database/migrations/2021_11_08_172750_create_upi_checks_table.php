<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUpiChecksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('upi_checks', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('parent_id')->unsigned()->index()->nullable();
            $table->bigInteger('business_id')->unsigned()->index()->nullable();
            $table->bigInteger('candidate_id')->unsigned()->index()->nullable();
            $table->bigInteger('service_id')->unsigned()->index()->nullable();
            $table->enum('source_type',['API','Manual','SystemDB'])->nullable();
            $table->string('upi_id',100)->nullable();
            $table->string('name',100)->nullable();
            $table->decimal('price',10,2)->default('0')->nullable();
            $table->bigInteger('user_id')->unsigned()->index()->nullable();
            $table->enum('user_type',['coc','customer','superadmin','guest'])->nullable();
            $table->enum('platform_reference',['web','api'])->default('web')->nullable();
            $table->tinyInteger('is_verified')->default(1)->nullable();
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
        Schema::dropIfExists('upi_checks');
    }
}
