<?php

use Illuminate\Database\Seeder;
use App\ExpenseCategory;

class ExpenseCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(ExpenseCategory::class, 10)->create();
    }
}
