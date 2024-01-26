<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMedicinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medicines', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('med_name')->unique();
            $table->string('generic_name')->nullable()->unique();
            $table->unsignedBigInteger('medicinecategory_id')->nullable();
            $table->unsignedBigInteger('manufacturer_id')->nullable();
            $table->decimal('sell_price', 10, 2)->nullable();
            $table->text('note')->nullable();
            $table->tinyInteger('med_status')->default(1);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('medicinecategory_id')->references('id')->on('medicine_categories')->onDelete('cascade');
            $table->foreign('manufacturer_id')->references('id')->on('manufacturers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('medicines');
    }
}
