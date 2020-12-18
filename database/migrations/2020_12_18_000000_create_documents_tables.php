<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('milestone_document_groups', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->unsignedSmallInteger('display_order')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
        });
        Schema::create('milestone_document_templates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('auto_title')->nullable();
            $table->boolean('use_publish_date')->default(false);
            $table->unsignedBigInteger('document_group_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
        });
        Schema::create('milestone_document_template_sections', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('description')->nullable();
            $table->unsignedBigInteger('document_template_id');
            $table->enum('document_template_section_type', ['markdown', 'text']);
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
        });
        Schema::create('milestone_document_section_fields', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('document_id');
            $table->unsignedBigInteger('document_section_id');
            $table->text('content');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
        });
        Schema::create('milestone_document_sections', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('document_id');
            $table->unsignedBigInteger('document_template_section_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
        });
        Schema::create('milestone_documents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title')->nullable();
            $table->unsignedBigInteger('document_template_id');
            $table->datetime('publish_date')->nullable();
            $table->unsignedBigInteger('document_group_id');
            $table->unsignedBigInteger('user_id');
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
        Schema::dropIfExists('milestone_document_groups');
        Schema::dropIfExists('milestone_document_templates');
        Schema::dropIfExists('milestone_document_template_sections');
        Schema::dropIfExists('milestone_document_section_fields');
        Schema::dropIfExists('milestone_document_sections');
        Schema::dropIfExists('milestone_documents');
    }
}
