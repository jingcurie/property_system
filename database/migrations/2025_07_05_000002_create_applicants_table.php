<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('applicants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rental_application_id')->constrained('rental_applications')->onDelete('cascade')->comment('Reference to the rental application');
            $table->string('full_name')->comment('Full name of the applicant');
            $table->string('email')->comment('Email address');
            $table->string('phone')->comment('Phone number');
            $table->date('date_of_birth')->comment('Date of birth');
            $table->enum('government_id_type', ['SSN', 'SIN', 'ITIN'])->nullable()->comment('Type of government-issued ID');
            $table->string('ssn_last4')->nullable()->comment('Last 4 digits of SSN/SIN/ITIN');
            $table->string('address_line1')->comment('Primary address line');
            $table->string('address_line2')->nullable()->comment('Secondary address line (optional)');
            $table->string('city')->comment('City of residence');
            $table->string('state', 5)->comment('Province/State code');
            $table->string('zip_code')->comment('Postal code');
            $table->string('country')->default('CA')->comment('Country code (default Canada)');
            $table->string('emergency_contact_name')->comment('Name of emergency contact');
            $table->string('emergency_contact_phone')->comment('Phone number of emergency contact');
            $table->string('renters_insurance_provider')->nullable()->comment('Insurance company name');
            $table->string('policy_number')->nullable()->comment('Insurance policy number');
            $table->decimal('coverage_amount', 10, 2)->nullable()->comment('Insurance coverage amount');
            $table->string('ip_address')->nullable()->comment('IP address at submission');
            $table->text('device_fingerprint')->nullable()->comment('Browser/device fingerprint');
            $table->foreignId('previous_application_id')->nullable()->constrained('rental_applications')->nullOnDelete()->comment('Link to previous related application');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applicants');
    }
};
