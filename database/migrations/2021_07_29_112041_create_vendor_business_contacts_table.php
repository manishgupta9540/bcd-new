<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorBusinessContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_business_contacts', function (Blueprint $table) {
            $table->id();
            $table->string('contact_type',100)->nullable();
            $table->string('contact_type_title',50)->nullable();
            $table->string('first_name',200)->nullable();
            $table->string('last_name',200)->nullable();
            $table->string('designation',200)->nullable();
            $table->bigInteger('parent_id')->index()->unsigned();
            $table->bigInteger('business_id')->index()->unsigned();
            $table->string('phone_code',20)->nullable();
            $table->string('phone_iso',20)->nullable();
            $table->string('phone',20)->nullable();
            $table->string('landline_ext',10)->nullable();
            $table->string('landline_number',20)->nullable();
            $table->string('email',100)->nullable();
            $table->string('company_name',255)->nullable();
            $table->string('address_line1',200)->nullable();
            $table->string('address_line2',200)->nullable();
            $table->string('zipcode',10)->nullable();
            $table->string('city_name',100)->nullable();
            $table->integer('city_id')->nullable();
            $table->string('state_name',100)->nullable();
            $table->integer('state_id')->nullable();
            $table->string('country_name',100)->nullable();
            $table->integer('country_id')->nullable();
            $table->tinyInteger('status')->default('1');
            $table->tinyInteger('is_deleted')->default('0');
            $table->dateTime('deleted_at')->nullable();
            $table->string('deleted_by',20)->nullable();
            $table->string('created_by',20)->nullable();
            $table->string('updated_by',20)->nullable();
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
        Schema::dropIfExists('vendor_business_contacts');
    }
}
