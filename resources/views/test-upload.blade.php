@extends('layouts.app')

<div>hello</div>

@section('content')
<div class="container py-4">
    <h3 class="mb-4">测试文件上传组件</h3>

      @include('components.files.upload', [
    'files' => [],
    'fileable_type' => 1,
    'fileable_id' => null,
])
</div>
@endsection