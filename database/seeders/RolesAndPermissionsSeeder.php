<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (User::ROLES as $roleName) {
            $role = Role::create(['name' => $roleName]);

            foreach (User::ROLE_PERMISSIONS[$roleName] ?? [] as $permissionName) {
                Permission::create(['name' => $permissionName])->roles()->sync(['role_id' => $role->id]);
            }
        }
    }
}
