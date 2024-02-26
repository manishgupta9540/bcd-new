<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorBusinessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_businesses', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('parent_id')->index()->unsigned()->nullable();
            $table->bigInteger('business_id')->index()->unsigned()->nullable();
            $table->string('contact_person',255)->nullable();
            $table->string('phone_code',20)->nullable();
            $table->string('phone_iso',20)->nullable();
            $table->string('phone',20)->nullable();
            $table->string('email',100)->nullable();
            $table->string('company_name',255)->nullable();
            $table->string('company_short_name',100)->nullable();
            $table->string('business_type',50)->nullable();
            $table->string('address_line1',200)->nullable();
            $table->string('address_line2',200)->nullable();
            $table->string('zipcode',10)->nullable();
            $table->string('city_name',100)->nullable();
            $table->integer('city_id')->nullable();
            $table->string('state_name',100)->nullable();
            $table->integer('state_id')->nullable();
            $table->string('country_name',100)->nullable();
            $table->integer('country_id')->nullable();
            $table->string('fax_number',20)->nullable();
            $table->string('client_business',255)->nullable();
            $table->string('website',50)->nullable();
            $table->text('type_of_facility')->nullable();
            $table->string('pan_number',100)->nullable();
            $table->string('gst_number',150)->nullable();
            $table->string('tin_number',100)->nullable();
            $table->string('hr_name',200)->nullable();
            $table->date('work_order_date')->nullable();
            $table->date('work_operating_date')->nullable();
            $table->string('contract_signed_by',200)->nullable();
            $table->string('billing_detail',255)->nullable();
            $table->enum('billing_mode',['online','offline'])->nullable();
            $table->string('service_type',100)->nullable();
            $table->string('created_by',200)->nullable();
            $table->string('updated_by',200)->nullable();
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
        Schema::dropIfExists('vendor_businesses');
    }
}
