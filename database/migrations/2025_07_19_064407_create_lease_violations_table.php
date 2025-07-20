<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('lease_violations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lease_id')->comment('租赁ID');
            
            $table->enum('violation_type', [
                'pet', 'smoking', 'noise', 'unauthorized_occupant',
                'overholding', 'lock_change', 'smoke_alarm_tampering',
                'strata_bylaw', 'property_damage', 'late_payment',
                'insurance_lapse', 'cleaning_violation'
            ])->comment('违约类型');
            
            $table->date('violation_date')->comment('违约发生日期');
            $table->text('description')->comment('违约描述');
            
            // 罚金
            $table->decimal('fine_amount', 8, 2)->default(0)->comment('罚金金额');
            $table->decimal('strata_fine_amount', 8, 2)->default(0)->comment('层高罚金');
            $table->decimal('total_amount', 8, 2)->storedAs('fine_amount + strata_fine_amount')->comment('总金额');
            
            // 支付状态
            $table->date('payment_due_date')->nullable()->comment('缴费截止日期');
            $table->date('paid_date')->nullable()->comment('实际缴费日期');
            $table->enum('payment_status', ['pending', 'paid', 'partial', 'waived', 'disputed'])->default('pending')->comment('支付状态');
            
            // 处理
            $table->boolean('resolved')->default(false)->comment('是否已解决');
            $table->date('resolved_date')->nullable()->comment('解决日期');
            $table->text('resolution_notes')->nullable()->comment('解决备注');
            
            // 升级处理
            $table->boolean('notice_served')->default(false)->comment('是否已发出通知');
            $table->date('notice_served_date')->nullable()->comment('通知发出日期');
            $table->boolean('eviction_threatened')->default(false)->comment('是否威胁驱逐');
            
            $table->timestamps();
            
            $table->index(['lease_id', 'violation_type'], 'idx_lease_violation_type');
            $table->index('violation_date', 'idx_violation_date');
            $table->index(['payment_status', 'payment_due_date'], 'idx_payment_status');
            
            $table->foreign('lease_id')->references('lease_id')->on('leases');
        });
    }

    public function down()
    {
        Schema::dropIfExists('lease_violations');
    }
};