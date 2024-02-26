<?php

use Illuminate\Database\Seeder;

use App\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CreateAdminUserSeeder extends Seeder
{ 
    /*** Run the database seeds.** @return void*/
    
    public function run()
    {
        $user = User::create(['name' => 'Mithilesh Sah', 'email' => 'mithilesh.techsaga@gmail.com', 'password' => bcrypt('tech1') ]);
        $role = Role::create(['name' => 'Admin']);
        $permissions = Permission::pluck('id', 'id')->all();
        $role->syncPermissions($permissions);
        $user->assignRole([$role->id]);
    }
}

