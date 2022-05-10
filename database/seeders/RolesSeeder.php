<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    protected $roles = [
        'manager' => [2, 3, 4, 5, 9, 10, 11, 12, 13, 15, 16, 18, ],
        'installer' => [11, 18, 23, 12, 24, ],
        'admin' => [1, 2, 3, 4, 8, 9, 10, 11, 12, 16, 13, 15, 18, 19, 20, ],
        'collector' => [12, 17, 4, 5, 6, 25, ],
        'evening_manager' => [18, 10, 2, 3, 4, 5, 9, 11, 16, 15, 12],
//        'courier' => [7, 25],
//        'measurer' => [2, 11, 23, ],
        'director' => [1, 2, 3, 4, 8, 9, 10, 11, 12, 16, 13, 15, 18, 19, 20, ],
    ];

    protected $permissions = [
        'loginToIdsMsk',
        'addOrders',
        'seeManagement',
        'seeDocuments',
        'seePlan',
        'seeSpecs',
        'seeTodaysGraph',
        'seeExtendedPlan',
        'seeNotificationsForInstallers',
        'seeWages',
        'canCalculate',
        'seeWarehouse',
        'seeInventory',
        'seeHisMoney',
        'seePhonePause',
        'seeGraphsMaps',
        'seeListGrid',
        'seeOrders',
        'seeAllGraph',
        'seeAdditional',
        'seeInstallersGraph',
        'seeManagerWages',
        'seeHisGraph',
        'seeInfoForInstallers',
        'seeEarning',
        'seeSoldPlan'
    ];
    /*
     *
1) canLoginToIdsMsk
2) addOrders
3) seeManagement
4) seeDocuments
5) seePlan
6) seeSpecs
7) seeTodaysGraph
8) seeExtendedPlan
9) seeNotificationsForInstallers
10)	seeWages
11)	canCalculate
12)	seeWarehouse - Администраторская версия склада
13)	seeInventory
14)	seeHisMoney
15)	seePhonePause
16)	seeGraphsMaps
17) seeListGrid
18)	seeOrders
19)	seeAllGraph – График работы сотрудников
20)	seeAdditional – Управление -> Прочее
21)	seeInstallersGraph
22)	seeManagerWages
23)	seeGraphForInstallers – График для монтажников и сборщиков
24)	seeInfoForInstallers – сюда входит Склад-остатки, Склад-история перемещений, Уведомления, Информация, Сумма к
    выплате, Рейтинг, Бонусная часть
25)	seeEarning – Заработок
26)	seeSoldPlan
*/
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        foreach ($this->permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
        foreach ($this->roles as $role => $permissionsIds) {
            $role = Role::create(['name' => $role]);
            $role->syncPermissions(Permission::whereIn('id', $permissionsIds)->get());
        }
    }
}
