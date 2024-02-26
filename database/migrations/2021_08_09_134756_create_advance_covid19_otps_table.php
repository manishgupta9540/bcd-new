<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdvanceCovid19OtpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('advance_covid19_otps', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('business_id')->unsigned()->index()->nullable();
            $table->string('txnId',100)->nullable();
            $table->string('mobile_no',50)->nullable();
            $table->enum('is_verified',[0,1])->default(0)->nullable();
            $table->tinyInteger('status')->default(1)->comment('0 => old,1 => new')->nullable();
            $table->bigInteger('created_by')->unsigned()->index()->nullable();
            $table->text('token')->nullable();
            $table->bigInteger('updated_by')->unsigned()->index()->nullable();
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
        Schema::dropIfExists('advance_covid19_otps');
    }
}
