<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBranchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('branches', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('branch_title');
            $table->string('branch_code');
            $table->unsignedBigInteger('user_id');
            $table->string('branch_phone')->unique()->nullable();
            $table->text('branch_address');
            $table->text('branch_note')->nullable();
            $table->date('branch_start_date');
            $table->tinyInteger('branch_status')->default(1);
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
        Schema::dropIfExists('branches');
    }
}
