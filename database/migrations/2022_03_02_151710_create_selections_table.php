<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSelectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('selections', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->float('flow')->unsigned();
            $table->float('head')->unsigned();
            $table->float('deviation')->unsigned()->nullable();
            $table->string('main_pumps_counts', 16);
            $table->integer('reserve_pumps_count')->unsigned();
            $table->string('control_system_type_ids');
            $table->string('pump_brand_ids');
            $table->string('pump_series_ids', 512);
            $table->string('jockey_brand_ids')->nullable();
            $table->string('jockey_series_ids', 512)->nullable();
            $table->text('collectors');
            $table->text('comment')->nullable();

            $table->integer('gate_valves_count')->unsigned()->nullable();
            $table->boolean('avr')->nullable();
            $table->boolean('kkv')->nullable();
            $table->boolean('on_street')->nullable();
            $table->float('jockey_flow')->unsigned()->nullable();
            $table->float('jockey_head')->unsigned()->nullable();

            $table->softDeletes();

            $table->unsignedTinyInteger('type');
            $table->unsignedTinyInteger('station_type');

            $table->bigInteger('pump_id')->unsigned()->nullable();
            $table->bigInteger('jockey_pump_id')->unsigned()->nullable();
            $table->bigInteger('project_id')->unsigned();
            $table->bigInteger('created_by')->unsigned();

            $table->foreign('jockey_pump_id')->references('id')->on('pumps')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('pump_id')->references('id')->on('pumps')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('project_id')->references('id')->on('projects')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('created_by')->references('id')->on('users')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('selections');
    }
}
