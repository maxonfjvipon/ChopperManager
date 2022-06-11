<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Selection\Entities\StationType;

class CreatePumpStationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pump_stations', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->float('cost_price', 12)->unsigned();
            $table->float('extra_percentage')->unsigned();
            $table->float('extra_sum', 12)->unsigned();
            $table->float('final_price', 12)->unsigned();
            $table->text('comment')->nullable();
            $table->integer('main_pumps_count')->unsigned();
            $table->integer('reserve_pumps_count')->unsigned();

            $table->softDeletes();

            $table->unsignedTinyInteger('af_station_type')->nullable();

            $table->bigInteger('input_collector_id')->unsigned();
            $table->bigInteger('output_collector_id')->unsigned();
            $table->bigInteger('control_system_id')->unsigned();
            $table->bigInteger('pump_id')->unsigned();
            $table->bigInteger('selection_id')->unsigned();
            $table->bigInteger('chassis_id')->unsigned();

            $table->foreign('input_collector_id')->references('id')->on('collectors')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('output_collector_id')->references('id')->on('collectors')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('control_system_id')->references('id')->on('control_systems')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('pump_id')->references('id')->on('pumps')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('selection_id')->references('id')->on('selections')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('chassis_id')->references('id')->on('chassis')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pump_stations');
    }
}
