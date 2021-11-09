<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePumpsPriceListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pumps_price_lists', function (Blueprint $table) {
            $table->bigInteger('pump_id')->unsigned();
            $table->bigInteger('country_id')->unsigned();
            $table->bigInteger('currency_id')->unsigned();
            $table->float('price')->unsigned();
            $table->primary(['pump_id', 'country_id']);

            $table->foreign('pump_id')->references('id')->on('pumps')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('country_id')->references('id')->on('countries')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('currency_id')->references('id')->on('currencies')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pumps_price_lists');
    }
}
