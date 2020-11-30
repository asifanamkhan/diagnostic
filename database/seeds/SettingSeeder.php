<?php

use Illuminate\Database\Seeder;
use App\Setting;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Setting::create([
            'name' => 'Diagnostic Management System',
	        'logo' => 'logo.jpg',
	        'created_by' => 1
        ]);
    }
}
