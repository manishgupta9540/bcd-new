<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCovid19ChecksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('covid19_checks', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('parent_id')->unsigned()->index()->nullable();
            $table->bigInteger('business_id')->unsigned()->index()->nullable();
            $table->bigInteger('candidate_id')->unsigned()->index()->nullable();
            $table->bigInteger('service_id')->unsigned()->index()->nullable();
            $table->string('txnId',100)->nullable();
            $table->enum('source_type',['API','SystemDB'])->default('API')->nullable();
            $table->string('mobile_no',50)->nullable();
            $table->string('reference_id',50)->nullable();
            $table->text('token')->nullable();
            $table->enum('is_verified',[0,1])->default(1)->nullable();
            $table->decimal('price',10,2)->default(0)->nullable();
            $table->bigInteger('user_id')->unsigned()->index()->nullable();
            $table->enum('used_by',['customer','coc','superadmin','guest'])->default('customer')->nullable();
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
        Schema::dropIfExists('covid19_checks');
    }
}
