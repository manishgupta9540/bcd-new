<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuicksbookCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quicksbook_customers', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('parent_id')->unsigned()->index()->nullable();
            $table->bigInteger('business_id')->unsigned()->index()->nullable();
            $table->string('fully_qualified_name',255)->nullable();
            $table->string('email',255)->nullable();
            $table->string('display_name',255)->nullable();
            $table->string('suffix',55)->nullable();
            $table->string('title',255)->nullable();
            $table->string('middle',100)->nullable();
            $table->text('notes')->nullable();
            $table->string('family_name',255)->nullable();
            $table->string('phone',25)->nullable();
            $table->string('company_name',255)->nullable();
            $table->string('city',255)->nullable();
            $table->string('address_line1',255)->nullable();
            $table->string('zipcode',10)->nullable();
            $table->string('latitude',255)->nullable();
            $table->string('longitude',255)->nullable();
            $table->string('country_subdivision_code',55)->nullable();
            $table->string('given_name',100)->nullable();
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
        Schema::dropIfExists('quicksbook_customers');
    }
}
