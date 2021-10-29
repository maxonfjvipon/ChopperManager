<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeriesAndTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('pump_series_and_types');
        Schema::create('pump_series_and_types', function (Blueprint $table) {
            $table->bigInteger('series_id')->unsigned();
            $table->bigInteger('type_id')->unsigned();
            $table->primary(['series_id', 'type_id']);
        });
        Schema::table('pump_series_and_types', function (Blueprint $table) {
            $table->foreign('series_id')->references('id')->on('pump_series')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('type_id')->references('id')->on('pump_types')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pump_series_and_types');
    }
}
