<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameSinglePumpSelectionsTable1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('single_pump_selections', 'selections');
        Schema::table('selections', function (Blueprint $table) {
            $table->string('selected_pump_name')->nullable()->change();
            $table->string('mains_pumps_counts', 20)->nullable()->change();
            $table->integer('pumps_count')->unsigned()->nullable()->change();
            $table->float('flow')->nullable()->change();
            $table->float('head')->nullable()->change();
            $table->float('fluid_temperature')->default(20)->nullable()->change();
            $table->float('deviation')->nullable()->default(0)->nullable()->change();
            $table->smallInteger('reserve_pumps_count')->tinyInteger('reserve_pumps_count')->unsigned()->nullable()->change();
            $table->bigInteger('range_id')->unsigned()->nullable()->change();
            $table->string('custom_range', 7)->nullable()->change();
            $table->string('pump_series_ids')->nullable()->change();
            $table->bigInteger('pump_id')->unsigned()->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('selections', function (Blueprint $table) {
            $table->renameColumn('pumpable_id', 'pump_id');
        });
    }
}
