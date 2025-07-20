<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('leases', function (Blueprint $table) {
            $table->decimal('cleaning_fee', 10, 2)->nullable();
            $table->boolean('insurance_required')->default(false);
            $table->text('termination_policy')->nullable();
            $table->string('parking_info')->nullable();
            $table->string('storage_info')->nullable();
            $table->boolean('strata_acknowledged')->default(false);
            $table->boolean('form_k_signed')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('leases', function (Blueprint $table) {
            $table->dropColumn([
                'lease_type',
                'pets_allowed',
                'pet_deposit',
                'furnished',
                'furniture_deposit',
                'cleaning_fee',
                'insurance_required',
                'termination_policy',
                'parking_info',
                'storage_info',
                'strata_acknowledged',
                'form_k_signed',
            ]);
        });
    }
};
