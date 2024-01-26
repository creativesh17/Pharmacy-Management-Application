<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRefundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('refunds', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('invoice_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('branch_id');
            $table->unsignedBigInteger('customer_id');
            $table->date('refund_date')->nullable();
            $table->string('payment_type')->default('Cash');
            $table->decimal('refund_total', 10, 2)->default(0.00);
            $table->decimal('refund_cut', 10, 2)->default(0.00);
            $table->decimal('refund_nettotal', 10, 2)->default(0.00);
            $table->decimal('refund_paid', 10, 2)->default(0.00);
            $table->text('refund_note')->nullable();
            $table->tinyInteger('refund_status')->default(1);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('refunds');
    }
}
