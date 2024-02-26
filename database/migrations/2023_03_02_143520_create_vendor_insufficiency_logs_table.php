<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorInsufficiencyLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_insufficiency_logs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('parent_id')->nullable();
            $table->bigInteger('business_id')->nullable();
            $table->bigInteger('candidate_id')->nullable();
            $table->bigInteger('coc_id')->nullable();
            $table->bigInteger('service_id')->nullable();
            $table->bigInteger('item_number')->nullable();
            $table->string('activity_type',15)->nullable();
            $table->string('status',50)->nullable();
            $table->text('comments');
            $table->text('attachments');
            $table->enum('attachment_file_platform',['web', 's3'])->nullable();
            $table->bigInteger('created_by')->nullable();
            $table->enum('user_type',['customer','coc','superadmin'])->default('customer')->nullable();
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
        Schema::dropIfExists('vendor_insufficiency_logs');
    }
}
