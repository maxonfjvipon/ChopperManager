<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pump_coefficients', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('pump_id')->unsigned();
            $table->tinyInteger('position')->unsigned();
            $table->double('k', 7);
            $table->double('b', 7);
            $table->double('c', 7);
        });
        Schema::table('pump_coefficients', function (Blueprint $table) {
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
        Schema::dropIfExists('pump_coefficients');
    }
};
