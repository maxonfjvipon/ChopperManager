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
        Schema::dropIfExists('pumps_and_coefficients');
        Schema::create('pumps_and_coefficients', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('pump_article_num')->unsigned();
            $table->tinyInteger('position')->unsigned();
            $table->double('k', 7);
            $table->double('b', 7);
            $table->double('c', 7);
        });
        Schema::table('pumps_and_coefficients', function (Blueprint $table) {
            $table->foreign('pump_article_num')->references('article_num_main')->on('pumps')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pump_and_coefficients');
    }
}
