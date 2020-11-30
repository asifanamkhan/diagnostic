<?php

use Illuminate\Database\Seeder;
use App\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
            'name' => 'super_admin',
        	'display_name' => 'Super Admin',
        	'description' => 'Super Admin Has Every Permissions'
        ]);

        Role::create([
            'name' => 'admin',
        	'display_name' => 'Regular Admin',
        	'description' => 'Regular Admin Has Almost Every Permissions Except Financial Data'
        ]);

        Role::create([
            'name' => 'operator',
        	'display_name' => 'System Operator',
        	'description' => 'System Operator Has Permissions of Data Entry Only'
        ]);
    }
}
