<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('trip_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_trip_id')->constrained()->onDelete('cascade');
            $table->string('document_type');
            $table->string('file_name');
            $table->string('file_path');
            $table->string('file_size');
            $table->string('mime_type');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index('business_trip_id');
            $table->index('document_type');
        });
    }

    public function down()
    {
        Schema::dropIfExists('trip_documents');
    }
};
