<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
    use Spatie\Permission\Models\Permission;
    use Spatie\Permission\Models\Role;

    return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $roles = [
            'manager' => [2, 3, 4, 5, 9, 10, 11, 12, 13, 15, 16, 18, ],
            'installer' => [11, 18, 23, 12, 24, ],
            'admin' => [1, 2, 3, 4, 8, 9, 10, 11, 12, 16, 13, 15, 18, 19, 20, ],
            'collector' => [12, 17, 4, 5, 6, 25, ],
            'evening_manager' => [18, 10, 2, 3, 4, 5, 9, 11, 16, 15, 12],
//        'courier' => [7, 25],
//        'measurer' => [2, 11, 23, ],
            'director' => [1, 2, 3, 4, 8, 9, 10, 11, 12, 16, 13, 15, 18, 19, 20, ],
        ];

        $permissions = [
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

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
        foreach ($roles as $role => $permissionsIds) {
            $role = Role::create(['name' => $role]);
            $role->syncPermissions(Permission::whereIn('id', $permissionsIds)->get());
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
};
