<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSinglePumpSelectionAndPumpTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('single_pump_selections_and_pump_types');
        Schema::create('single_pump_selections_and_pump_types', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('selection_id')->unsigned();
            $table->bigInteger('type_id')->unsigned();
        });
        Schema::table('single_pump_selections_and_pump_types', function (Blueprint $table) {
            $table->foreign('selection_id', 'spsapt_selection_id_foreign')->references('id')->on('single_pump_selections')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('type_id', 'spsapt_type_id_foreign')->references('id')->on('pump_types')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('single_pump_selections_and_pump_types');
    }
}
