<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('lease_inspections', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lease_id')->comment('租赁ID');
            
            $table->enum('inspection_type', ['move_in', 'move_out', 'routine', 'pet_approval'])->comment('检查类型');
            $table->date('scheduled_date')->comment('计划检查日期');
            $table->date('completed_date')->nullable()->comment('实际完成日期');
            
            // 预约管理
            $table->boolean('booking_required')->default(true)->comment('是否需要预约');
            $table->integer('booking_notice_days')->default(5)->comment('预约提前天数');
            $table->boolean('booking_confirmed')->default(false)->comment('预约是否确认');
            
            // 检查结果
            $table->tinyInteger('condition_rating')->nullable()->comment('状况评分(1-5)');
            $table->text('damages_found')->nullable()->comment('发现的损坏');
            $table->string('photos_path', 500)->nullable()->comment('照片路径');
            
            // 费用产生
            $table->decimal('inspection_fee_charged', 8, 2)->default(0)->comment('检查费用（未按时检查的罚金）');
            $table->boolean('additional_cleaning_required')->default(false)->comment('是否需要额外清洁');
            $table->decimal('additional_cleaning_fee', 8, 2)->default(0)->comment('额外清洁费');
            
            // 参与人员
            $table->string('inspector_name', 100)->nullable()->comment('检查员姓名');
            $table->boolean('tenant_present')->default(false)->comment('租户是否在场');
            $table->date('tenant_signature_date')->nullable()->comment('租户签字日期');
            
            $table->enum('status', ['scheduled', 'completed', 'cancelled', 'no_show'])->default('scheduled')->comment('检查状态');
            $table->text('notes')->nullable()->comment('备注');
            
            $table->timestamp('created_at')->useCurrent();
            
            $table->index(['lease_id', 'inspection_type'], 'idx_lease_inspection_type');
            $table->index('scheduled_date', 'idx_scheduled_date');
            
            $table->foreign('lease_id')->references('lease_id')->on('leases');
            
            // $table->check('condition_rating BETWEEN 1 AND 5');
        });
    }

    public function down()
    {
        Schema::dropIfExists('lease_inspections');
    }
};