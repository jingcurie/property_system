<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('lease_fee_structure', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lease_id')->comment('租赁ID');
            
            // 清洁费（按户型）
            $table->string('unit_type', 50)->comment('户型：1 Bdrm, 2 Bdrm 1 Bath等');
            $table->decimal('mandatory_cleaning_fee', 8, 2)->nullable()->comment('强制清洁费');
            $table->boolean('cleaning_fee_paid')->default(false)->comment('清洁费是否已付');
            $table->decimal('move_out_inspection_fee', 8, 2)->default(200)->comment('搬出检查费');
            
            // 搬家费用
            $table->decimal('move_in_fee', 8, 2)->nullable()->comment('搬入费');
            $table->decimal('move_out_fee', 8, 2)->nullable()->comment('搬出费');
            $table->boolean('elevator_booking_required')->default(true)->comment('是否需要预约电梯');
            $table->integer('elevator_booking_notice_days')->default(3)->comment('电梯预约提前天数');
            
            // 钥匙管理
            $table->decimal('key_deposit', 8, 2)->default(20)->comment('钥匙押金');
            $table->decimal('fob_deposit', 8, 2)->default(100)->comment('门禁卡押金');
            $table->decimal('key_loan_fee_regular', 8, 2)->default(35)->comment('常规时间借钥匙费');
            $table->decimal('key_loan_fee_after_hours', 8, 2)->default(75)->comment('非工作时间借钥匙费');
            
            // 违约处理费用
            $table->decimal('lease_break_fee_half_month', 10, 2)->nullable()->comment('违约费-半月（租户找替换者）');
            $table->decimal('lease_break_fee_one_month', 10, 2)->nullable()->comment('违约费-一月（房东找替换者）');
            $table->decimal('lease_break_fee_two_month', 10, 2)->nullable()->comment('违约费-两月（立即解约）');
            
            $table->timestamp('created_at')->useCurrent();
            
            $table->foreign('lease_id')->references('lease_id')->on('leases')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('lease_fee_structure');
    }
};