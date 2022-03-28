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
        Schema::create('pumps', function (Blueprint $table) {
            $table->id();
            $table->string('article')->unique();
            $table->string('name');
            $table->text('description');
            $table->float('price');
            $table->timestamp('price_updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->float('power')->unsigned();
            $table->float('current')->unsigned();
            $table->string('size')->comment('LENGTHxHEIGHTxWIDTH');
            $table->integer('suction_height')->unsigned();
            $table->integer('ptp_length')->unsigned();
            $table->float('weight')->unsigned();
            $table->text('performance');

            $table->softDeletes();

            $table->bigInteger('series_id')->unsigned();
            $table->bigInteger('currency_id')->unsigned();
            $table->bigInteger('connection_type_id')->unsigned();
            $table->bigInteger('orientation_id')->unsigned();
            $table->bigInteger('dn_suction_id')->unsigned();
            $table->bigInteger('dn_pressure_id')->unsigned();
            $table->bigInteger('collector_switch_id')->unsigned();

            $table->foreign('series_id')->references('id')->on('pump_series')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('currency_id')->references('id')->on('currencies')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('connection_type_id')->references('id')->on('connection_types')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('orientation_id')->references('id')->on('pump_orientations')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('dn_suction_id')->references('id')->on('dns')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('dn_pressure_id')->references('id')->on('dns')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('collector_switch_id')->references('id')->on('collector_switches')->cascadeOnUpdate()->cascadeOnDelete();
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
