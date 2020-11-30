<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockAdjustmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_adjustments', function (Blueprint $table) {
            $table->id();
            $table->date('date')->index();
            $table->integer('product_id')->index();
            $table->integer('adjustment_class')->index()->comment('1 for Uses, 2 for Wastage, 3 for Adjustment, 4 for Others');
            $table->integer('adjustment_type')->index()->default(2)->comment('1 for Addition, 2 for Subtraction');
            $table->double('prev_quantity')->default(0);
            $table->double('adjusted_quantity')->default(0);
            $table->double('after_quantity')->default(0);
            $table->text('description')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stock_adjustments');
    }
}
