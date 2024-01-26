<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('purchases', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('purchase_invoice_id');
            $table->date('purchase_date')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('branch_id');
            $table->unsignedBigInteger('supplier_id');
            $table->decimal('purchase_total', 10, 2);
            $table->decimal('purchase_discount', 10, 2);
            $table->decimal('purchase_paid', 10, 2);
            $table->decimal('purchase_nettotal', 10, 2);
            $table->decimal('purchase_due', 10, 2)->nullable();
            $table->string('payment_type')->default('Cash');
            $table->text('purchase_note')->nullable();
            $table->tinyInteger('purchase_status')->default(1);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
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
        Schema::dropIfExists('purchases');
    }
}
