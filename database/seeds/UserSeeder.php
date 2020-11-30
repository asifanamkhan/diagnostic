<?php

use Illuminate\Database\Seeder;
use App\{User, Role};

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'name' => 'Tom Kent',
	        'email' => 'tom@gmail.com',
	        'password' => Hash::make('112358'),
	        'avatar' => 'avatar.jpg'
        ]);

        $role = Role::find(1);

        $user->attachRole($role);
    }
}
