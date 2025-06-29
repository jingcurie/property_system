@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>租赁申请列表</h1>

        <form method="GET" action="{{ route('applications.index') }}" class="row g-2 mb-3">
            <div class="col-auto">
                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">-- 全部状态 --</option>
                    <option value="待处理" {{ request('status') == '待处理' ? 'selected' : '' }}>待处理</option>
                    <option value="已审核" {{ request('status') == '已审核' ? 'selected' : '' }}>已审核</option>
                </select>
            </div>
        </form>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>房源</th>
                    <th>申请人</th>
                    <th>电话</th>
                    <th>起租日期</th>
                    <th>退租日期</th>
                    <th>留言</th>
                    <th>申请时间</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                @foreach($applications as $app)
                    <tr>
                        <td>{{ $app->id }}</td>
                        <td>{{ $app->property->title ?? '已删除房源' }}</td>
                        <td>{{ $app->applicant_name }}</td>
                        <td>{{ $app->phone }}</td>
                        <td>{{ $app->start_date }}</td>
                        <td>{{ $app->end_date }}</td>
                        <td>{{ $app->message }}</td>
                        <td>{{ $app->created_at }}</td>
                        <td>
                            <form action="{{ route('applications.updateStatus', $app) }}" method="POST" class="d-inline">
                                @csrf @method('PATCH')
                                <select name="status" class="form-select form-select-sm d-inline w-auto"
                                    onchange="this.form.submit()">
                                    <option value="待处理" {{ $app->status == '待处理' ? 'selected' : '' }}>待处理</option>
                                    <option value="已审核" {{ $app->status == '已审核' ? 'selected' : '' }}>已审核</option>
                                </select>
                            </form>

                            <form action="{{ route('applications.destroy', $app) }}" method="POST" class="d-inline"
                                onsubmit="return confirm('确定要删除此申请吗？')">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm ms-1">删除</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $applications->links() }}
    </div>
@endsection