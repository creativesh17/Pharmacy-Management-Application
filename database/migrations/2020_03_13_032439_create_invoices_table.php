<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('branch_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('customer_id');
            $table->date('invoice_date')->nullable();
            $table->string('payment_type')->default('Cash');
            $table->decimal('invoice_total', 10, 2)->default(0.00);
            $table->decimal('invoice_discount', 10, 2)->default(0.00);
            $table->decimal('invoice_nettotal', 10, 2)->default(0.00);
            $table->decimal('invoice_received', 10, 2)->default(0.00);
            $table->decimal('invoice_due', 10, 2)->default(0.00);
            $table->text('invoice_note')->nullable();
            $table->tinyInteger('invoice_status')->default(1);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoices');
    }
}
