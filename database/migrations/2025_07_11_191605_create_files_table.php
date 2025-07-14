<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('files', function (Blueprint $table) {
            $table->id()->comment('Primary key ID');

            $table->string('filename')->comment('Original file name');
            $table->string('path')->comment('Relative storage path');
            $table->string('mime_type')->nullable()->comment('MIME type, e.g., image/png, application/pdf');
            $table->unsignedBigInteger('size')->comment('File size in bytes');
            $table->string('disk')->default('local')->comment('Storage disk, e.g., local, s3');

            $table->string('fileable_type')->comment('Associated model class, e.g., App\\Models\\RentalApplication');
            $table->unsignedBigInteger('fileable_id')->comment('Associated model ID');

            $table->string('tag')->nullable()->comment('Optional tag, e.g., contract, photo, idcard');
            $table->string('description', 255)->nullable()->comment('Short description or note');
            $table->boolean('is_private')->default(true)->comment('Whether the file is private');
            $table->unsignedBigInteger('uploaded_by')->nullable()->comment('Uploader user ID');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
