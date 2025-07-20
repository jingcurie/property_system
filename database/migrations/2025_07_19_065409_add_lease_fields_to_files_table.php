<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('files', function (Blueprint $table) {
            // 租赁文档特有字段
            $table->enum('lease_document_type', [
                'lease_agreement', 'addendum', 'form_k', 'condition_report',
                'insurance_policy', 'id_copy', 'income_proof', 'signed_contract'
            ])->nullable()->comment('租赁文档类型（仅当 fileable_type=lease 时使用）');
            
            // 签署状态
            $table->boolean('requires_signature')->default(false)->comment('是否需要签名');
            $table->boolean('tenant_signed')->default(false)->comment('租户是否已签名');
            $table->date('tenant_signed_date')->nullable()->comment('租户签名日期');
            $table->boolean('landlord_signed')->default(false)->comment('房东是否已签名');
            $table->date('landlord_signed_date')->nullable()->comment('房东签名日期');
            
            // 版本管理
            $table->string('document_version', 20)->nullable()->comment('文档版本');
            $table->unsignedBigInteger('superseded_by')->nullable()->comment('被哪个文档替代（files.id）');
            
            // 索引
            $table->index(['fileable_type', 'fileable_id', 'lease_document_type'], 'idx_lease_document_type');
            $table->index(['requires_signature', 'tenant_signed', 'landlord_signed'], 'idx_signature_status');
            
            $table->foreign('superseded_by')->references('id')->on('files')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('files', function (Blueprint $table) {
            $table->dropForeign(['superseded_by']);
            $table->dropIndex('idx_lease_document_type');
            $table->dropIndex('idx_signature_status');
            
            $table->dropColumn([
                'lease_document_type', 'requires_signature',
                'tenant_signed', 'tenant_signed_date', 'landlord_signed', 
                'landlord_signed_date', 'document_version', 'superseded_by'
            ]);
        });
    }
};
