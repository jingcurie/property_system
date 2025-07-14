<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Property;
use App\Models\RentalApplication;
use App\Models\Role;


class DashboardController extends Controller
{
    public function index()
    {
        $userCount = 128;  // 模拟总用户
        $propertyCount = 56; // 模拟房源数
        $pendingApplications = 12; // 模拟待审核
        $activeLandlords = 34; // 模拟活跃房东

        $rentalTrend = collect([
            '6月05日' => 3,
            '6月10日' => 5,
            '6月15日' => 8,
            '6月20日' => 12,
            '6月25日' => 15,
            '6月30日' => 20,
            '7月04日' => 25,
        ]);

        $propertyStatus = collect([
            '已出租' => 30,
            '待出租' => 20,
            '维修中' => 6,
        ]);

        $roles = collect([
            (object)['name' => '管理员', 'users_count' => 2],
            (object)['name' => '房东', 'users_count' => 25],
            (object)['name' => '租客', 'users_count' => 101],
        ]);

        $statCards = [
    ['title' => '总用户', 'value' => 128, 'icon' => 'people', 'color' => 'primary'],
    ['title' => '有效房源', 'value' => 56, 'icon' => 'house', 'color' => 'success'],
    ['title' => '待审批申请', 'value' => 12, 'icon' => 'file-earmark-check', 'color' => 'warning'],
    ['title' => '活跃房东', 'value' => 34, 'icon' => 'person-check', 'color' => 'info'],
    ['title' => '即将到期租赁', 'value' => 8, 'icon' => 'calendar-x', 'color' => 'danger'],
    ['title' => '维修中房源', 'value' => 3, 'icon' => 'tools', 'color' => 'dark'],
];

$rentalTrend = [
    '6月05日' => 3, '6月10日' => 6, '6月15日' => 10, '6月20日' => 18,
    '6月25日' => 22, '6月30日' => 30, '7月04日' => 35,
];

$propertyStatus = collect([
    '已出租' => 35, '待出租' => 15, '维修中' => 6,
]);

$latestApplications = [
    ['name' => '张三', 'date' => '2025-07-03', 'status' => '待审', 'status_color' => 'warning'],
    ['name' => '李四', 'date' => '2025-07-02', 'status' => '通过', 'status_color' => 'success'],
    ['name' => '王五', 'date' => '2025-07-01', 'status' => '驳回', 'status_color' => 'danger'],
];

$expiringLeases = [
    ['property' => '金色阳光 2-201', 'tenant' => '刘女士', 'due' => '2025-07-10'],
    ['property' => '绿地花园 6-802', 'tenant' => '周先生', 'due' => '2025-07-12'],
];

$maintenanceProperties = [
    ['property' => '香榭丽都 3-404', 'status' => '水管维修'],
    ['property' => '银湖湾 1-101', 'status' => '电路检修'],
];

$notifications = [
    ['title' => '系统升级通知：7月5日凌晨维护', 'time' => '2小时前'],
    ['title' => '新增“租赁模板设置”功能', 'time' => '1天前'],
];

return view('dashboard', compact(
    'statCards', 'rentalTrend', 'propertyStatus',
    'latestApplications', 'expiringLeases',
    'maintenanceProperties', 'notifications'
));


      
    }
}
