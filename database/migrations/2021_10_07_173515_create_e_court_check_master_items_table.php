<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateECourtCheckMasterItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('e_court_check_master_items', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('e_court_master_id')->unsigned()->index()->nullable();
            $table->string('name_as_per_court_record',100)->nullable();
            $table->string('case_id',100)->nullable();
            $table->text('detail_link')->nullable();
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
        Schema::dropIfExists('e_court_check_master_items');
    }
}
