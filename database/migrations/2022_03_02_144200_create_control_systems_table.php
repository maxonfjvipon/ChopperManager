<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateControlSystemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('control_systems', function (Blueprint $table) {
            $table->id();
            $table->string('article')->unique();
            $table->integer('pumps_count')->unsigned();
            $table->float('power')->unsigned();
            $table->float('price', 12)->unsigned();
            $table->timestamp('price_updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->float('weight')->unsigned();
            $table->text('description')->nullable();
            $table->boolean('avr')->nullable();
            $table->integer('gate_valves_count')->unsigned()->nullable();
            $table->boolean('kkv')->nullable();
            $table->boolean('on_street')->nullable();
            $table->boolean('has_jockey')->nullable();

            $table->bigInteger('type_id')->unsigned();
            $table->unsignedTinyInteger('currency');
            $table->unsignedTinyInteger('montage_type')->nullable();

            $table->softDeletes();

            $table->foreign('type_id')->references('id')->on('control_system_types')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('control_systems');
    }
}
