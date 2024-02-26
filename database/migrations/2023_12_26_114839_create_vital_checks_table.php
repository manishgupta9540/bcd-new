<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVitalChecksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vital_checks', function (Blueprint $table) {
            $table->id();
            $table->string('billingCodeId',100)->nullable();
            $table->bigInteger('parent_id')->unsigned()->index()->nullable();
            $table->bigInteger('business_id')->unsigned()->index()->nullable();
            $table->bigInteger('candidate_id')->unsigned()->index()->nullable();
            $table->bigInteger('service_id')->unsigned()->index()->nullable();
            $table->enum('source_type',['API','Manual','SystemDB'])->nullable();
            $table->string('product_type',100)->nullable();
            $table->string('categories',100)->nullable();
            $table->string('relevancy_score',100)->nullable();
            $table->string('subject',100)->nullable();
            $table->string('subjectId',100)->nullable();
            $table->string('dateOfBirth',100)->nullable();
            $table->string('address_line_1',100)->nullable();
            $table->string('city_district',100)->nullable();
            $table->string('country',100)->nullable();
            $table->string('state',100)->nullable();
            $table->string('postal_code',100)->nullable();
            $table->longtext('data_response')->nullable();
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
        Schema::dropIfExists('vital_checks');
    }
}
