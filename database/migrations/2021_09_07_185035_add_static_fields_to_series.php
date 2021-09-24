<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStaticFieldsToSeries extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pump_series', function (Blueprint $table) {
            $table->bigInteger('regulation_adjustment_id')->unsigned();
            $table->float('temp_min')->nullable();
            $table->float('temp_max')->nullable();

            $table->foreign('regulation_adjustment_id')->references('id')->on('electronic_power_adjustments')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}