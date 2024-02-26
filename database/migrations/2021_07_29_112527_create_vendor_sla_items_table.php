<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorSlaItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_sla_items', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('parent_id')->index()->unsigned()->nullable();
            $table->bigInteger('business_id')->index()->unsigned()->nullable();
            $table->bigInteger('vendor_id')->index()->unsigned()->nullable();
            $table->integer('sla_id')->nullable();
            $table->integer('service_id')->nullable();
            $table->integer('number_of_verifications')->default(1);
            $table->text('notes')->nullable();
            $table->integer('tat')->default('10');
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('status')->default('1');
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
        Schema::dropIfExists('vendor_sla_items');
    }
}
