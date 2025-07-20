<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('lease_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lease_id')->comment('租赁ID');
            
            $table->enum('payment_type', ['rent', 'late_fee', 'nsf_fee', 'violation_fine', 'deposit', 'cleaning_fee', 'other'])->comment('支付类型');
            $table->decimal('amount', 12, 2)->comment('支付金额');
            
            // 期间
            $table->date('payment_period_start')->nullable()->comment('租金所属期间开始');
            $table->date('payment_period_end')->nullable()->comment('租金所属期间结束');
            $table->date('due_date')->comment('到期日期');
            $table->date('paid_date')->nullable()->comment('实际支付日期');
            
            // 支付方式
            $table->enum('payment_method', ['auto_debit', 'cash', 'cheque', 'e_transfer', 'credit_card'])->comment('支付方式');
            $table->string('payment_reference', 100)->nullable()->comment('支付参考号（支票号、交易号等）');
            
            // 状态
            $table->enum('status', ['pending', 'paid', 'failed', 'cancelled'])->default('pending')->comment('支付状态');
            $table->integer('nsf_count')->default(0)->comment('NSF次数');
            
            // 关联
            $table->unsignedBigInteger('violation_id')->nullable()->comment('违约记录ID（如果是违约罚金）');
            
            $table->text('notes')->nullable()->comment('备注');
            $table->timestamps();
            
            $table->index(['lease_id', 'payment_period_start'], 'idx_lease_payment_period');
            $table->index(['due_date', 'status'], 'idx_due_date');
            $table->index('payment_type', 'idx_payment_type');
            
            $table->foreign('lease_id')->references('lease_id')->on('leases');
            $table->foreign('violation_id')->references('id')->on('lease_violations');
        });
    }

    public function down()
    {
        Schema::dropIfExists('lease_payments');
    }
};