{{-- 上传文件模块 --}}
@include('components.files.upload', [
    'files' => $property?->files ?? [],
    'fileable_type' => $property ? get_class($property) : App\Models\Property::class,
    'fileable_id' => $property?->property_id ?? 0,
    'attachments' => $attachments,
])
