<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersAndPumpProducersAndDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('users_and_pump_producers_and_discounts');
        Schema::create('users_and_pump_producers_and_discounts', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('pump_producer_id')->unsigned();
            $table->float('discount')->default(0);
        });

        Schema::table('users_and_pump_producers_and_discounts', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('pump_producer_id')->references('id')->on('pump_producers')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_and_pump_producers_and_discounts');
    }
}
