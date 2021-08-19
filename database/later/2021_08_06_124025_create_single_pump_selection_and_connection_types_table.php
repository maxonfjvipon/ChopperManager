<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSinglePumpSelectionAndConnectionTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('single_pump_selections_and_connection_types');
        Schema::create('single_pump_selections_and_connection_types', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('selection_id')->unsigned();
            $table->bigInteger('connection_type_id')->unsigned();
        });
        Schema::table('single_pump_selections_and_connection_types', function (Blueprint $table) {
            $table->foreign('selection_id', 'spsact_selection_id_foreign')->references('id')->on('single_pump_selections')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('connection_type_id', 'spsact_connection_type_id_foreign')->references('id')->on('connection_types')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('single_pump_selection_and_connection_types');
    }
}
