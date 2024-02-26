<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWalletTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->integer('business_id')->unsigned()->index();
            $table->integer('user_id')->unsigned()->index();
            $table->string('transaction_id',50)->nullable();
            $table->string('rayzorpay_id',50)->nullable();
            $table->enum('payment_source',['manual','online'])->default('manual')->nullable();
            $table->enum('transaction_type',['debit','credit'])->nullable();
            $table->decimal('amount',$precision=10,$scale=2)->default(0);
            $table->string('notes',200)->nullable();
            $table->string('is_payment_done',10)->default(0)->nullable();
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
        Schema::dropIfExists('wallet_transactions');
    }
}
