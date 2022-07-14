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
        Schema::create('pump_series', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('is_discontinued')->default(false);

            $table->unsignedTinyInteger('currency');

            $table->softDeletes();

            $table->bigInteger('brand_id')->unsigned();

            $table->foreign('brand_id')->references('id')->on('pump_brands')->cascadeOnDelete()->cascadeOnUpdate();
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
