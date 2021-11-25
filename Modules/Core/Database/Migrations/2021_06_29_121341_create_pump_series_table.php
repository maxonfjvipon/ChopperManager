<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePumpSeriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('pump_series');
        Schema::create('pump_series', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->bigInteger('brand_id')->unsigned();
            $table->softDeletes();
        });

        Schema::table('pump_series', function (Blueprint $table) {
            $table->foreign('brand_id')->references('id')->on('pump_brands')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pump_series');
    }
}