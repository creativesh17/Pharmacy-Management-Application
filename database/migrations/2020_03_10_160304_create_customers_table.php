<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('customer_name')->default('Walking Customer');
            $table->unsignedBigInteger('user_id');
            $table->string('customer_phone')->nullable()->unique();
            $table->string('customer_email')->nullable()->unique();
            $table->text('customer_address')->nullable();
            $table->text('customer_note')->nullable();
            $table->tinyInteger('customer_status')->default(1);
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
        Schema::dropIfExists('customers');
    }
}
