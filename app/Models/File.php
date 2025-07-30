<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property int $id Primary key ID
 * @property string|null $title
 * @property string $filename Original file name
 * @property string $path Relative storage path
 * @property string|null $mime_type MIME type, e.g., image/png, application/pdf
 * @property int $size File size in bytes
 * @property string $disk Storage disk, e.g., local, s3
 * @property string $fileable_type Associated model class, e.g., App\Models\RentalApplication
 * @property int $fileable_id Associated model ID
 * @property string|null $envelope_id
 * @property string|null $category Optional tag, e.g., contract, photo, idcard
 * @property string|null $description Short description or note
 * @property int $is_cover
 * @property int $sort_order
 * @property int $is_private Whether the file is private
 * @property int|null $uploaded_by Uploader user ID
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property int|null $deleted_by
 * @property string|null $signature_status
 * @property string|null $lease_document_type 租赁文档类型（仅当 fileable_type=lease 时使用）
 * @property int $requires_signature 是否需要签名
 * @property int $tenant_signed 租户是否已签名
 * @property string|null $tenant_signed_date 租户签名日期
 * @property int $landlord_signed 房东是否已签名
 * @property string|null $landlord_signed_date 房东签名日期
 * @property string|null $document_version 文档版本
 * @property int|null $superseded_by 被哪个文档替代（files.id）
 * @property-read Model|\Eloquent $fileable
 * @property-read \App\Models\User|null $uploader
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File whereDisk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File whereDocumentVersion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File whereEnvelopeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File whereFileableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File whereFileableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File whereFilename($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File whereIsCover($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File whereIsPrivate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File whereLandlordSigned($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File whereLandlordSignedDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File whereLeaseDocumentType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File whereMimeType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File whereRequiresSignature($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File whereSignatureStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File whereSupersededBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File whereTenantSigned($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File whereTenantSignedDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File whereUploadedBy($value)
 * @mixin \Eloquent
 */
class File extends Model
{
    protected $fillable = [
        'title',
        'filename',
        'path',
        'mime_type',
        'size',
        'disk',
        'fileable_type',
        'fileable_id',
        'category',
        'description',
        'is_private',
        'uploaded_by',
        'envelope_id',
        'signature_status',
        'tenant_signed',
        'tenant_signed_date',
    ];

    /**
     * 多态关联：文件属于某个模块（如租赁申请）
     */
    public function fileable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * 上传者
     */
    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
