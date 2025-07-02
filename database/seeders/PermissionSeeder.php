<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // 1. 定义角色
        $roles = [
            'admin',
            'manager',
            'agent',
            'finance',
            'support',
            'viewer',
        ];

        // 2. 创建角色
        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }

        // 3. 定义权限列表（按模块）
        $permissions = [
            // 房源管理
            'property.view',
            'property.create',
            'property.edit',
            'property.delete',

            // 租赁申请
            'rental_application.view',
            'rental_application.create',
            'rental_application.edit',
            'rental_application.approve',

            // 用户与角色
            'user.view',
            'user.create',
            'user.edit',
            'user.delete',
            'role.manage',

            // 财务
            'payment.view',
            'payment.edit',
            'payment.approve',

            // 系统
            'settings.access',
            'logs.view',
            'trash.manage',
        ];

        // 4. 创建权限
        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // 5. 分配权限给角色
        $rolePermissions = [

            'admin' => $permissions, // 拥有全部权限

            'manager' => [
                'property.view', 'property.create', 'property.edit',
                'rental_application.view', 'rental_application.approve',
                'payment.view', 'payment.approve',
                'user.view',
            ],

            'agent' => [
                'property.view', 'property.create', 'property.edit',
                'rental_application.view', 'rental_application.create', 'rental_application.edit',
            ],

            'finance' => [
                'payment.view', 'payment.edit', 'payment.approve',
                'rental_application.view',
            ],

            'support' => [
                'rental_application.view',
                'property.view',
                'user.view',
            ],

            'viewer' => [
                'property.view',
                'rental_application.view',
                'payment.view',
                'user.view',
            ],
        ];

        foreach ($rolePermissions as $role => $perms) {
            Role::findByName($role)->syncPermissions($perms);
        }

        $this->command->info('✅ 权限与角色初始化完成！');
    }
}
