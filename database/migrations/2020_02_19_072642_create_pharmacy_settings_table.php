<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePharmacySettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pharmacy_settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('ph_name');
            $table->string('ph_phone');
            $table->string('ph_email');
            $table->text('ph_address');
            $table->text('ph_about')->nullable();
            $table->string('ph_logo')->nullable();
            $table->tinyInteger('ph_status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pharmacy_settings');
    }
}
