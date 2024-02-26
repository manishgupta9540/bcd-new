<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateECourtCheckItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('e_court_check_items', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('e_court_check_id')->unsigned()->index()->nullable();
            $table->bigInteger('parent_id')->unsigned()->index()->nullable();
            $table->bigInteger('business_id')->unsigned()->index()->nullable();
            $table->bigInteger('candidate_id')->unsigned()->index()->nullable();
            $table->bigInteger('service_id')->unsigned()->index()->nullable();
            $table->string('name_as_per_court_record',100)->nullable();
            $table->string('case_id',100)->nullable();
            $table->text('detail_link')->nullable();
            $table->bigInteger('user_id')->unsigned()->index()->nullable();
            $table->enum('user_type',['coc','customer','superadmin','guest'])->nullable();
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
        Schema::dropIfExists('e_court_check_items');
    }
}
