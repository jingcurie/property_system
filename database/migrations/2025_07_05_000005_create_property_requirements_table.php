<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('property_requirements', function (Blueprint $table) {
            $table->string('property_id', 20)
                ->collation('utf8mb4_general_ci')
                ->comment('Target property ID');

            $table->foreign('property_id')
                ->references('property_id')
                ->on('properties')
                ->onDelete('cascade');
            $table->integer('required_min_credit_score')->nullable()->comment('Minimum credit score required');
            $table->decimal('pet_deposit_amount', 10, 2)->nullable()->comment('Additional pet deposit required');
            $table->boolean('mandatory_insurance')->default(false)->comment('Whether tenant insurance is required');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('property_requirements');
    }
};
