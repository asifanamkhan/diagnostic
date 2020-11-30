<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->integer('product_category_id')->index();
            $table->string('name');
            $table->string('code')->unique();
            $table->string('unit')->nullable()->comment('1 for Piece, 2 for Box');
            $table->double('purchased')->default(0);
            $table->double('used')->default(0);
            $table->double('stock')->default(0);
            $table->double('alert_quantity')->default(5);
            $table->string('image')->nullable();
            $table->date('expire_date')->nullable();
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
        Schema::dropIfExists('products');
    }
}
