<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('lease_cleaning_schedule', function (Blueprint $table) {
            $table->id();
            $table->string('unit_type', 50)->unique()->comment('户型');
            
            // 基础清洁费
            $table->decimal('regular_fee', 8, 2)->comment('常规清洁费');
            $table->decimal('furnished_extra_fee', 8, 2)->default(100)->comment('家具额外费');
            
            // 违约清洁费（按搬出人数）
            $table->decimal('break_lease_fee_1_person', 8, 2)->nullable()->comment('违约清洁费-1人');
            $table->decimal('break_lease_fee_2_person', 8, 2)->nullable()->comment('违约清洁费-2人');
            $table->decimal('break_lease_fee_3_person', 8, 2)->nullable()->comment('违约清洁费-3人');
            
            // 特殊情况
            $table->decimal('pet_cleaning_surcharge', 8, 2)->default(0)->comment('宠物清洁附加费');
            $table->decimal('smoking_cleaning_surcharge', 8, 2)->default(500)->comment('吸烟清洁附加费');
            
            $table->date('effective_date')->default(DB::raw('CURRENT_DATE'))->comment('生效日期');
            $table->timestamp('created_at')->useCurrent();
            
            $table->index('unit_type', 'idx_unit_type');
        });
        
        // 插入预设数据
        DB::table('lease_cleaning_schedule')->insert([
            ['unit_type' => 'Studio/Bachelor', 'regular_fee' => 300, 'break_lease_fee_1_person' => 160, 'break_lease_fee_2_person' => 0, 'break_lease_fee_3_person' => 0],
            ['unit_type' => '1 Bdrm', 'regular_fee' => 350, 'break_lease_fee_1_person' => 160, 'break_lease_fee_2_person' => 0, 'break_lease_fee_3_person' => 0],
            ['unit_type' => '1 Bdrm 1 Den', 'regular_fee' => 370, 'break_lease_fee_1_person' => 160, 'break_lease_fee_2_person' => 0, 'break_lease_fee_3_person' => 0],
            ['unit_type' => '2 Bdrm 1 Bath', 'regular_fee' => 380, 'break_lease_fee_1_person' => 110, 'break_lease_fee_2_person' => 110, 'break_lease_fee_3_person' => 0],
            ['unit_type' => '2 Bdrm 1.5 or 2 Bath', 'regular_fee' => 420, 'break_lease_fee_1_person' => 105, 'break_lease_fee_2_person' => 110, 'break_lease_fee_3_person' => 0],
            ['unit_type' => '3 Bdrm 2 Bath', 'regular_fee' => 450, 'break_lease_fee_1_person' => 165, 'break_lease_fee_2_person' => 125, 'break_lease_fee_3_person' => 130],
            ['unit_type' => '3 Bdrm 2.5 or 3 Bath', 'regular_fee' => 480, 'break_lease_fee_1_person' => 175, 'break_lease_fee_2_person' => 110, 'break_lease_fee_3_person' => 145],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('lease_cleaning_schedule');
    }
};