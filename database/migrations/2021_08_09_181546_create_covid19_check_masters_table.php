<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCovid19CheckMastersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('covid19_check_masters', function (Blueprint $table) {
            $table->id();
            $table->string('txnId',100)->nullable();
            $table->enum('source_type',['API','SystemDB'])->default('API')->nullable();
            $table->string('mobile_no',50)->nullable();
            $table->string('reference_id',50)->nullable();
            $table->text('token')->nullable();
            $table->enum('is_verified',[0,1])->default(1)->nullable();
            $table->string('file_name',100)->nullable();
            $table->longText('raw_data')->nullable();
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
        Schema::dropIfExists('covid19_check_masters');
    }
}
