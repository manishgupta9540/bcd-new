<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCinCheckMastersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cin_check_masters', function (Blueprint $table) {
            $table->id();
            $table->string('cin_number',100)->nullable();
            $table->string('registration_number',100)->nullable();
            $table->string('company_name',200)->nullable();
            $table->text('registered_address')->nullable();
            $table->string('date_of_incorporation',100)->nullable();
            $table->string('email_id',100)->nullable();
            $table->text('paid_up_capital_in_rupees')->nullable();
            $table->text('authorised_capital')->nullable();
            $table->string('company_category',200)->nullable();
            $table->string('company_subcategory',200)->nullable();
            $table->string('company_class',100)->nullable();
            $table->string('whether_company_is_listed',100)->nullable();
            $table->string('company_efilling_status',100)->nullable();
            $table->string('date_of_last_AGM',100)->nullable();
            $table->string('date_of_balance_sheet',100)->nullable();
            $table->text('another_maintained_address')->nullable();
            $table->text('directors')->nullable();
            $table->enum('platform_reference',['web','api'])->default('web')->nullable();
            $table->tinyInteger('is_api_verified')->default(1)->nullable();
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
        Schema::dropIfExists('cin_check_masters');
    }
}
