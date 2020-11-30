<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('invoice')->unique();
            $table->integer('patient_id')->index();
            $table->integer('doctor_id')->index()->nullable();
            $table->date('date');
            $table->dateTime('delivery_date');
            $table->string('status')->nullable();
            $table->double('total_amount')->default(0);
            $table->double('discount')->default(0);
            $table->double('amount_after_discount')->default(0);
            $table->double('paid_amount')->default(0);
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
        Schema::dropIfExists('services');
    }
}
