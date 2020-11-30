<?php

use Illuminate\Database\Seeder;
use App\TestCategory;

class TestCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(TestCategory::class, 5)->create();
    }
}
