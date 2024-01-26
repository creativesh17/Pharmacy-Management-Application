<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRefundDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('refund_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('refund_id');
            $table->unsignedBigInteger('medicine_id');
            $table->string('medicine_name');
            $table->integer('sold_qty');
            $table->integer('refund_qty');
            $table->decimal('sell_price', 10, 2);
            $table->decimal('total_price', 10, 2)->default(0.00);
            $table->tinyInteger('refund_details_status')->default(1);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('refund_id')->references('id')->on('refunds')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('refund_details');
    }
}
