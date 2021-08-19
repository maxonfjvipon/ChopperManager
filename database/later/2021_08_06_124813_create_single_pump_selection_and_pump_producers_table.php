<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSinglePumpSelectionAndPumpProducersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('single_pump_selections_and_pump_producers');
        Schema::create('single_pump_selections_and_pump_producers', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('selection_id')->unsigned();
            $table->bigInteger('producer_id')->unsigned();
        });
        Schema::table('single_pump_selections_and_pump_producers', function (Blueprint $table) {
            $table->foreign('selection_id', 'spsapp_selection_id_foreign')->references('id')->on('single_pump_selections')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('producer_id', 'spsapp_producer_id_foreign')->references('id')->on('pump_producers')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('single_pump_selections_and_pump_producers');
    }
}
