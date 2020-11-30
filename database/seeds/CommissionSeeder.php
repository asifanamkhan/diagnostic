<?php

use Illuminate\Database\Seeder;
use App\{Test, Doctor, Commission};
use Faker\Generator as Faker;

class CommissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$doctors = Doctor::all();
    	$tests = Test::all();

        foreach ($tests as $test) {
        	foreach ($doctors as $doctor) {
        		Commission::create([
        			'test_id' => $test->id,
	                'doctor_id' => $doctor->id,
	                'commission_type' => 1,
	                'commission' => mt_rand(5, 25),
	                'created_by' => 1
        		]);
        	}
        }
    }
}
