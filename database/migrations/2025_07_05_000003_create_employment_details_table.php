<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('employment_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('applicant_id')->constrained('applicants')->onDelete('cascade')->comment('Reference to the applicant');
            $table->string('employer_name')->comment('Employer name');
            $table->string('job_title')->comment('Job title/position');
            $table->decimal('monthly_income', 10, 2)->comment('Monthly income amount');
            $table->json('income_proof_files')->nullable()->comment('Attached income documents (JSON array)');
            $table->string('other_income_source')->nullable()->comment('Description of other income sources');
            $table->enum('income_verified_by', ['manual', 'third_party'])->nullable()->comment('Verification method');
            $table->date('verification_date')->nullable()->comment('Date of income verification');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employment_details');
    }
};
