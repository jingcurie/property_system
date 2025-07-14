<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('consents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rental_application_id')->constrained('rental_applications')->onDelete('cascade')->comment('Reference to the rental application');
            $table->boolean('credit_check_consent')->default(false)->comment('Consent for credit check');
            $table->boolean('background_check_consent')->default(false)->comment('Consent for background check');
            $table->timestamp('signed_at')->nullable()->comment('Time of digital signature');
            $table->string('esignature_provider')->nullable()->comment('E-signature provider (e.g., DocuSign)');
            $table->string('esignature_id')->nullable()->comment('Reference ID from the e-sign platform');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('consents');
    }
};
