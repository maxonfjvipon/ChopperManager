<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dealer_markups', function (Blueprint $table) {
            $table->id();
            $table->integer('cost_from');
            $table->integer('cost_to');

            $table->integer('value');

            $table->bigInteger('dealer_id')->unsigned();

            $table->foreign('dealer_id')->references('id')->on('dealers')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dealer_markups');
    }
};
