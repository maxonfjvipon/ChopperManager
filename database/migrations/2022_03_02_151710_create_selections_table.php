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
            $table->string('main_pumps_counts');
            $table->integer('reserve_pumps_count')->unsigned();
            $table->text('control_systems');
            $table->text('series');
            $table->text('collector_types');

            $table->integer('gate_valves_count')->unsigned()->nullable();
            $table->boolean('avr')->nullable();
            $table->boolean('kkv')->nullable();
            $table->boolean('on_street')->nullable();
            $table->string('pump_article')->nullable();
            $table->float('jockey_pump_flow')->unsigned()->nullable();
            $table->float('jockey_pump_head')->unsigned()->nullable();

            $table->softDeletes();

            $table->bigInteger('jockey_pump_id')->unsigned()->nullable();
            $table->bigInteger('project_id')->unsigned();
            $table->bigInteger('type_id')->unsigned();

            $table->foreign('jockey_pump_id')->references('id')->on('pumps')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('project_id')->references('id')->on('projects')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('type_id')->references('id')->on('selection_types')->cascadeOnDelete()->cascadeOnUpdate();
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
