<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourtChecksV1Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('court_checks_v1', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('parent_id')->unsigned()->index()->nullable();
            $table->bigInteger('business_id')->unsigned()->index()->nullable();
            $table->bigInteger('candidate_id')->unsigned()->index()->nullable();
            $table->bigInteger('service_id')->unsigned()->index()->nullable();
            $table->enum('source_type',['API','Manual','SystemDB'])->nullable();
            $table->string('name',100)->nullable();
            $table->string('father_name',100)->nullable();
            $table->text('address')->nullable();
            $table->string('type',100)->nullable();
            $table->string('state',100)->nullable();
            $table->text('cases')->nullable();
            $table->decimal('price',10,2)->default('0')->nullable();
            $table->bigInteger('user_id')->unsigned()->index()->nullable();
            $table->enum('user_type',['coc','customer','superadmin','guest'])->nullable();
            $table->enum('platform_reference',['web','api'])->default('web')->nullable();
            $table->tinyInteger('is_verified')->default(1)->nullable();
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
        Schema::dropIfExists('court_checks_v1');
    }
}
