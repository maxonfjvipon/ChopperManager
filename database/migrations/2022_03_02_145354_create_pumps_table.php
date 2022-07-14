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
            $table->float('price', 12);
            $table->timestamp('price_updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->float('power')->unsigned();
            $table->float('current')->unsigned();
            $table->string('size')->comment('LENGTHxHEIGHTxWIDTH');
            $table->integer('suction_height')->unsigned();
            $table->integer('ptp_length')->unsigned();
            $table->float('weight')->unsigned();
            $table->text('performance');
            $table->boolean('is_discontinued')->default(false);

            $table->softDeletes();

            $table->unsignedSmallInteger('dn_suction');
            $table->unsignedSmallInteger('dn_pressure');
            $table->unsignedTinyInteger('connection_type');
            $table->unsignedTinyInteger('collector_switch');
            $table->unsignedTinyInteger('orientation');

            $table->bigInteger('series_id')->unsigned();

            $table->foreign('series_id')->references('id')->on('pump_series')->cascadeOnUpdate()->cascadeOnDelete();
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
