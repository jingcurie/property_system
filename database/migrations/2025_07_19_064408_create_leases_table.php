<?php
// 2025_01_01_000001_create_leases_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('leases', function (Blueprint $table) {
            // 主键
            $table->id('lease_id');
            
            // 租赁基本信息
            $table->string('lease_number', 50)->unique()->comment('租赁编号，如 L2025001');
            $table->string('lease_group_id', 50)->comment('租赁组ID，处理续约关系');
            $table->integer('version_number')->default(1)->comment('版本号（续约递增）');
            
            // 基础信息
            $table->unsignedBigInteger('property_id')->comment('房产ID');
           $table->unsignedBigInteger('tenant_id')->comment('租户ID');
            
            // 租期类型
            $table->enum('lease_type', ['fixed_term', 'month_to_month', 'periodic'])->comment('租赁类型');
            $table->date('start_date')->comment('开始日期');
            $table->date('end_date')->nullable()->comment('结束日期');
            
            // 核心财务
            $table->decimal('monthly_rent', 12, 2)->comment('月租金');
            $table->tinyInteger('rent_due_day')->default(1)->comment('租金到期日');
            $table->decimal('late_fee_amount', 8, 2)->default(0)->comment('滞纳金金额');
            $table->tinyInteger('late_fee_grace_days')->default(5)->comment('滞纳金宽限天数');
            $table->decimal('nsf_fee', 8, 2)->default(20.00)->comment('NSF费用');
            
            // 押金
            $table->decimal('security_deposit', 12, 2)->comment('保证金');
            $table->decimal('furniture_deposit', 10, 2)->default(0)->comment('家具押金');
            $table->decimal('pet_deposit', 10, 2)->default(0)->comment('宠物押金');
            
            // 公用事业（使用JSON存储）
            $table->json('utilities_included')->nullable()->comment('包含的公用事业');
            
            // 政策
            $table->boolean('pets_allowed')->default(false)->comment('是否允许宠物');
            $table->boolean('smoking_allowed')->default(false)->comment('是否允许吸烟');
            $table->boolean('subletting_allowed')->default(false)->comment('是否允许转租');
            
            // 保险
            $table->boolean('tenant_insurance_required')->default(true)->comment('是否需要租户保险');
            $table->decimal('minimum_coverage_amount', 10, 2)->nullable()->comment('最低保险金额');
            
            // 状态
            $table->enum('status', ['draft', 'active', 'expired', 'terminated', 'voided'])->comment('租赁状态');
            
            // 审计字段
            $table->timestamps();
            
            // 索引
            $table->index(['lease_group_id', 'version_number'], 'idx_lease_group');
            $table->index(['property_id', 'start_date', 'end_date'], 'idx_property_dates');
            $table->index('tenant_id', 'idx_tenant');
            
            // 外键约束（如果需要的话）
            // $table->foreign('property_id')->references('property_id')->on('properties');
            // $table->foreign('tenant_id')->references('tenant_id')->on('tenants');
        });
    }

    public function down()
    {
        Schema::dropIfExists('leases');
    }
};