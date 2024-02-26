<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImportBulkCriminalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('import_bulk_criminals', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('business_id');
            $table->bigInteger('parent_id')->nullable();
            $table->string('unique_id',150)->nullable();
            $table->bigInteger('sla_id')->nullable();
            $table->bigInteger('service_id')->nullable();
            $table->string('client_display_id',150)->nullable();
            $table->string('client_emp_code',150)->nullable();
            $table->string('entity_code',100)->nullable();
            $table->string('name',255)->nullable();
            $table->string('first_name',150)->nullable();
            $table->string('middle_name',100)->nullable();
            $table->string('last_name',150)->nullable();
            $table->string('father_name',150)->nullable();
            $table->string('aadhar_number',100);
            $table->date('dob')->nullable();
            $table->string('gender',20)->nullable();
            $table->string('phone',40)->nullable();
            $table->string('email',255)->nullable();
            $table->text('address')->nullable();
            $table->string('address_type',255)->nullable();
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
        Schema::dropIfExists('import_bulk_criminals');
    }
}
