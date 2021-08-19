<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSinglePumpSelectionAndPumpRegulationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('single_pump_selections_and_pump_regulations');
        Schema::create('single_pump_selections_and_pump_regulations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('selection_id')->unsigned();
            $table->bigInteger('regulation_id')->unsigned();
        });
        Schema::table('single_pump_selections_and_pump_regulations', function (Blueprint $table) {
            $table->foreign('selection_id', 'spsapr_selection_id_foreign')->references('id')->on('single_pump_selections')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('regulation_id', 'spsapr_regulation_id_foreign')->references('id')->on('pump_regulations')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('single_pump_selections_and_pump_regulations');
    }
}
