<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePumpAndCoefficientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pumps_and_coefficients', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('pump_id')->unsigned();
            $table->tinyInteger('position')->unsigned();
            $table->double('k', 7);
            $table->double('b', 7);
            $table->double('c', 7);
        });
        Schema::table('pumps_and_coefficients', function (Blueprint $table) {
            $table->foreign('pump_id')->references('id')->on('pumps')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pumps_and_coefficients', function (Blueprint $table) {
            $table->dropForeign('pumps_and_coefficients_pump_id_foreign');
        });
        Schema::dropIfExists('pump_and_coefficients');
    }
}
