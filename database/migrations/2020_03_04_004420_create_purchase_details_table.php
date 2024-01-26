<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('purchase_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('purchase_id');
            $table->unsignedBigInteger('medicine_id');
            $table->string('medicine_name')->nullable();
            $table->string('darr_no')->nullable();
            $table->decimal('sell_price', 10, 2);
            $table->decimal('medicine_price', 10, 2);
            $table->integer('stock');
            $table->integer('stock_original')->nullable();
            $table->integer('stock_damaged')->default(0);
            $table->decimal('total_price', 10, 2)->default(0.00);
            $table->date('expiry_date')->nullable();
            $table->tinyInteger('purchase_details_status')->default(1);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('purchase_id')->references('id')->on('purchases')->onDelete('cascade');
            $table->foreign('medicine_id')->references('id')->on('medicines')->onDelete('cascade');
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_details');
    }
}
