<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCollectorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collectors', function (Blueprint $table) {
            $table->id();
            $table->string('article')->unique();
            $table->integer('pumps_count');
            $table->float('price');
            $table->timestamp('price_updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->float('weight');

            $table->softDeletes();

            $table->bigInteger('type_id')->unsigned();
            $table->bigInteger('dn_common_id')->unsigned();
            $table->bigInteger('dn_pipes_id')->unsigned();
            $table->bigInteger('connection_type_id')->unsigned();
            $table->bigInteger('material_id')->unsigned();
            $table->bigInteger('currency_id')->unsigned();

            $table->foreign('type_id')->references('id')->on('collector_types')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('dn_common_id')->references('id')->on('dns')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('dn_pipes_id')->references('id')->on('dns')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('connection_type_id')->references('id')->on('connection_types')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('material_id')->references('id')->on('collector_materials')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('currency_id')->references('id')->on('currencies')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('collectors');
    }
}
