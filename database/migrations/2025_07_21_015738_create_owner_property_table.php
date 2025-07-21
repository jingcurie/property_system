<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::create('owner_property', function (Blueprint $table) {
        $table->id();
        $table->foreignId('owner_id')->constrained()->onDelete('cascade');
        $table->foreignId('property_id')->constrained()->onDelete('cascade');
        $table->decimal('ownership_percent', 5, 2)->nullable();
        $table->boolean('is_primary')->default(false);
        $table->text('notes')->nullable();
        $table->timestamps();

        $table->unique(['owner_id', 'property_id']); // 防止重复插入同一对
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('owner_property');
    }
};
