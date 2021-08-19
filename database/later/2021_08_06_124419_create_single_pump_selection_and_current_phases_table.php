<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSinglePumpSelectionAndCurrentPhasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('single_pump_selections_and_current_phases');
        Schema::create('single_pump_selections_and_current_phases', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('selection_id')->unsigned();
            $table->bigInteger('phase_id')->unsigned();
        });
        Schema::table('single_pump_selections_and_current_phases', function (Blueprint $table) {
            $table->foreign('selection_id', 'spsacp_selection_id_foreign')->references('id')->on('single_pump_selections')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('phase_id', 'spsacp_phase_id_foreign')->references('id')->on('current_phases')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('single_pump_selections_and_current_phases');
    }
}
