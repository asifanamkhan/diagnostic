<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
        	RoleSeeder::class,
        	UserSeeder::class,
            DoctorSeeder::class,
            DoctorPaymentSeeder::class,
            ExpenseCategorySeeder::class,
            ExpenseSeeder::class,
            PatientSeeder::class,
            ProductCategorySeeder::class,
            ProductSeeder::class,
            SupplierSeeder::class,
            PurchaseSeeder::class,
            PurchasePaymentSeeder::class,
            TestCategorySeeder::class,
            TestSeeder::class,
            CommissionSeeder::class,
            ServiceSeeder::class,
            StockAdjustmentSeeder::class,
            SettingSeeder::class
        ]);
    }
}
