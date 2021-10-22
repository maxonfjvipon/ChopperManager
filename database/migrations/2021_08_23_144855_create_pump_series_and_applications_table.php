<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePumpSeriesAndApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('pump_series_and_applications');
        Schema::create('pump_series_and_applications', function (Blueprint $table) {
            $table->bigInteger('series_id')->unsigned();
            $table->bigInteger('application_id')->unsigned();
            $table->primary(['series_id', 'application_id']);
        });
        Schema::table('pump_series_and_applications', function (Blueprint $table) {
            $table->foreign('series_id')->references('id')->on('pump_series')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('application_id')->references('id')->on('pump_applications')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pump_series_and_applications');
    }
}
