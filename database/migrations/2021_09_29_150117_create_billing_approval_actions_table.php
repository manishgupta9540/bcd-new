<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillingApprovalActionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('billing_approval_actions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('billing_id')->unsigned()->index()->nullable();
            $table->bigInteger('business_id')->unsigned()->index()->nullable();
            $table->text('notes')->nullable();
            $table->enum('action_type',['sent','cancel','approve'])->nullable();
            $table->bigInteger('created_by')->unsigned()->index()->nullable();
            $table->enum('user_type',['customer','client'])->nullable();
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
        Schema::dropIfExists('billing_approval_actions');
    }
}
