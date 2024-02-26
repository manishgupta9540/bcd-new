<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCandidateDocumentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('candidate_documents', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('candidate_id')->unsigned()->index();
            $table->string('document_name')->nullable();
            $table->string('attachments')->nullable();
            $table->string('id_number')->nullable();
            $table->string('remarks')->nullable();
            $table->text('zip_name')->nullable();
            $table->enum('file_platform',['web', 's3'])->nullable();
            $table->bigInteger('created_by')->unsigned()->index()->nullable();
            $table->tinyInteger('status')->default('0')->comment('0','1');
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
        Schema::dropIfExists('candidate_document');
    }
}
