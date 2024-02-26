<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorServiceItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_service_items', function (Blueprint $table) {
            $table->id();
            $table->integer('parent_id')->unsigned()->index();
            $table->integer('business_id')->unsigned()->index();
            $table->bigInteger('vendor_id')->index()->unsigned()->nullable();
            $table->integer('service_id')->unsigned()->index();
            $table->decimal('price',10,2)->default(0)->nullable();
            $table->bigInteger('created_by')->unsigned()->nullable();
            $table->bigInteger('updated_by')->unsigned()->nullable();
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
        Schema::dropIfExists('vendor_service_items');
    }
}
