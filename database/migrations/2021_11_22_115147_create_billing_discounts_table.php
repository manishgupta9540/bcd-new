<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillingDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('billing_discounts', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('billing_id')->unsigned()->index()->nullable();
            $table->decimal('discount',10,2)->default(0)->nullable();
            $table->decimal('discount_amt',10,2)->default(0)->nullable();
            $table->enum('discount_type',['flat','percentage'])->nullable();
            $table->enum('discount_ref',['amount','check'])->nullable();
            $table->text('discount_checks')->nullable();
            $table->bigInteger('created_by')->unsigned()->index()->nullable();
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
        Schema::dropIfExists('billing_discounts');
    }
}
