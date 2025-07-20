<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('lease_violation_rules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lease_id')->comment('租赁ID');
            
            // 违约罚金标准
            $table->decimal('pet_violation_fine', 8, 2)->default(500)->comment('宠物违约罚金');
            $table->decimal('smoking_violation_fine', 8, 2)->default(500)->comment('吸烟违约罚金');
            $table->decimal('noise_violation_fine', 8, 2)->default(200)->comment('噪音违约罚金');
            $table->decimal('overholding_daily_fine', 8, 2)->default(300)->comment('超期居住每日罚金');
            $table->decimal('lock_change_fine', 8, 2)->default(500)->comment('更换门锁罚金');
            $table->decimal('smoke_alarm_tampering_fine', 8, 2)->default(200)->comment('烟雾报警器破坏罚金');
            
            // 房客政策违约
            $table->decimal('unauthorized_occupant_fine', 8, 2)->default(1000)->comment('未授权居住者罚金（半个月租金）');
            
            // 层高违约罚金
            $table->decimal('strata_bylaw_violation_fine', 8, 2)->default(200)->comment('层高章程违约罚金');
            
            $table->timestamp('created_at')->useCurrent();
            
            $table->foreign('lease_id')->references('lease_id')->on('leases')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('lease_violation_rules');
    }
};
