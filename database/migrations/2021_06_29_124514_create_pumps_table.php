<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePumpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('pumps');

        Schema::create('pumps', function (Blueprint $table) {
            $table->string('article_num_main')->unique()->primary();
            $table->string('article_num_reserve')->nullable();
            $table->string('article_num_archive')->nullable();
            $table->bigInteger('series_id')->unsigned();
            $table->string('name');
            $table->float('weight');
            $table->float('rated_power');
            $table->float('rated_current');
            $table->bigInteger('connection_type_id')->unsigned();
            $table->float('fluid_temp_min');
            $table->float('fluid_temp_max');
            $table->integer('center_distance');
            $table->bigInteger('dn_suction_id')->unsigned();
            $table->bigInteger('dn_pressure_id')->unsigned();
            $table->bigInteger('category_id')->unsigned();
            $table->bigInteger('phase_id')->unsigned();
            $table->bigInteger('regulation_id')->unsigned();
            $table->string('performance')->nullable();
        });

        Schema::table('pumps', function (Blueprint $table) {
            $table->foreign('series_id')->references('id')->on('pump_series')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('connection_type_id')->references('id')->on('connection_types')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('dn_suction_id')->references('id')->on('dns')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('dn_pressure_id')->references('id')->on('dns')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('phase_id')->references('id')->on('mains_phases')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('category_id')->references('id')->on('pump_categories')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pumps');
    }
}
