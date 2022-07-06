<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dealers_pump_series', function (Blueprint $table) {
            $table->bigInteger('dealer_id')->unsigned();
            $table->bigInteger('series_id')->unsigned();
            $table->primary(['dealer_id', 'series_id']);

            $table->foreign('dealer_id')->references('id')->on('dealers')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('series_id')->references('id')->on('pump_series')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dealers_pump_series');
    }
};
