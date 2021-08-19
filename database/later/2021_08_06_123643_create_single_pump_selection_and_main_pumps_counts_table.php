<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSinglePumpSelectionAndMainPumpsCountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('single_pump_selections_and_main_pumps_counts');
        Schema::create('single_pump_selections_and_main_pumps_counts', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('selection_id')->unsigned();
            $table->tinyInteger('count')->unsigned();
        });
        Schema::table('single_pump_selections_and_main_pumps_counts', function (Blueprint $table) {
            $table->foreign('selection_id', 'spsampc_selection_id_foreign')->references('id')->on('single_pump_selections')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('single_pump_selections_and_main_pumps_counts');
    }
}
