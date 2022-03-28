<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->float('cost_price')->unsigned();
            $table->float('extra_percentage')->unsigned();
            $table->float('extra_sum')->unsigned();
            $table->float('final_price')->unsigned();
            $table->string('fire_type');
            $table->text('comment');
            $table->integer('main_pumps_count')->unsigned();
            $table->integer('reserve_pumps_count')->unsigned();
            $table->string('control_system_box_location');
            $table->string('sizes');
            $table->float('weight')->unsigned();

            $table->softDeletes();

            $table->bigInteger('collector_id')->unsigned();
            $table->bigInteger('control_system_id')->unsigned();
            $table->bigInteger('pump_id')->unsigned();
            $table->bigInteger('station_type_id')->unsigned();
            $table->bigInteger('selection_id')->unsigned();

            $table->foreign('collector_id')->references('id')->on('collectors')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('control_system_id')->references('id')->on('control_systems')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('pump_id')->references('id')->on('pumps')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('selection_id')->references('id')->on('selections')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('station_type_id')->references('id')->on('station_types')->cascadeOnUpdate()->cascadeOnDelete();
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
