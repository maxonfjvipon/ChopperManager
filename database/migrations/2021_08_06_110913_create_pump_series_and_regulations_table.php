<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePumpSeriesAndRegulationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pump_series_and_regulations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('series_id')->unsigned();
            $table->bigInteger('regulation_id')->unsigned();
        });
        Schema::table('pump_series_and_regulations', function (Blueprint $table) {
            $table->foreign('series_id')
                ->references('id')
                ->on('pump_series')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreign('regulation_id')
                ->references('id')
                ->on('pump_regulations')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pump_series_and_regulations');
    }
}
