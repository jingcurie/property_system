<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('rental_applications', function (Blueprint $table) {
            $table->id();
            $table->string('property_id', 20)->collation('utf8mb4_general_ci')->comment('Reference to the property');
            $table->foreign('property_id')->references('property_id')->on('properties')->onDelete('cascade');

            $table->string('application_code')->unique()->comment('Unique application code for tracking');
            $table->enum('status', ['submitted', 'under_review', 'approved', 'rejected'])->default('submitted')->comment('Application status');
            $table->timestamp('submitted_at')->nullable()->comment('Time when the application was submitted');
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete()->comment('User ID of the reviewer');
            $table->timestamp('reviewed_at')->nullable()->comment('Time when the application was reviewed');
            $table->text('notes')->nullable()->comment('Administrative notes or remarks');
            $table->boolean('fair_housing_acknowledged')->default(false)->comment('Whether applicant acknowledged Fair Housing policy');
            $table->decimal('risk_score', 5, 2)->nullable()->comment('System-generated risk score (0-100)');
            $table->boolean('auto_approval')->default(false)->comment('Whether system auto-approved this application');
            $table->integer('purge_after_months')->default(24)->comment('Data retention period in months');
            $table->timestamp('last_accessed_at')->nullable()->comment('Last time the application was accessed (privacy auditing)');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rental_applications');
    }
};
