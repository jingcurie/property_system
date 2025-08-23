<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>租赁申请报告</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            color: #333;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        .status-approved { color: #28a745; }
        .status-rejected { color: #dc3545; }
        .status-under_review { color: #17a2b8; }
        .status-submitted { color: #6c757d; }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>租赁申请报告</h1>
        <p>生成时间: {{ now()->timezone('america/vancouver')->format('Y-m-d H:i:s') }}</p>
        <p>总记录数: {{ $applications->count() }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>申请编号</th>
                <th>房源地址</th>
                <th>申请人姓名</th>
                <th>申请人邮箱</th>
                <th>申请人电话</th>
                <th>雇主名称</th>
                <th>月收入</th>
                <th>申请状态</th>
                <th>风险评分</th>
                <th>提交时间</th>
                <th>审核人</th>
                <th>审核时间</th>
            </tr>
        </thead>
        <tbody>
            @foreach($applications as $app)
            <tr>
                <td>{{ $app->application_code }}</td>
                <td>
                    @if($app->property)
                        {{ trim($app->property->address_street . ' ' . $app->property->address_city . ', ' . $app->property->address_province . ' ' . $app->property->address_postal_code) }}
                    @else
                        -
                    @endif
                </td>
                <td>{{ $app->applicant->full_name ?? '-' }}</td>
                <td>{{ $app->applicant->email ?? '-' }}</td>
                <td>{{ $app->applicant->phone ?? '-' }}</td>
                <td>{{ $app->employment->employer_name ?? '-' }}</td>
                <td>
                    @if($app->employment && $app->employment->monthly_income)
                        ${{ number_format($app->employment->monthly_income, 2) }}
                    @else
                        -
                    @endif
                </td>
                <td class="status-{{ $app->status }}">
                    {{ dict('application_status', app()->getLocale())[$app->status] ?? $app->status }}
                </td>
                <td>{{ $app->risk_score ?? '-' }}</td>
                <td>
                    @if($app->submitted_at)
                        {{ $app->submitted_at->timezone('america/vancouver')->format('Y-m-d H:i') }}
                    @else
                        -
                    @endif
                </td>
                <td>{{ $app->reviewer->name ?? '-' }}</td>
                <td>
                    @if($app->reviewed_at)
                        {{ $app->reviewed_at->timezone('america/vancouver')->format('Y-m-d H:i') }}
                    @else
                        -
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>此报告由系统自动生成，数据仅供参考。</p>
        <p>© {{ date('Y') }} 物业管理系统</p>
    </div>
</body>
</html> 