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
        Schema::dropIfExists('pumps');

        Schema::create('pumps', function (Blueprint $table) {
            $table->id();
            $table->string('part_num_main')->unique();
            $table->string('part_num_backup')->nullable();
            $table->string('part_num_archive')->nullable();
            $table->bigInteger('series_id')->unsigned();
            $table->string('name');
            $table->float('price');
            $table->bigInteger('currency_id')->unsigned();
            $table->float('weight');
            $table->float('power');
            $table->float('amperage');
            $table->bigInteger('connection_type_id')->unsigned();
            $table->float('min_liquid_temp');
            $table->float('max_liquid_temp');
            $table->integer('between_axes_dist');
            $table->bigInteger('dn_input_id')->unsigned();
            $table->bigInteger('dn_output_id')->unsigned();
            $table->bigInteger('category_id')->unsigned();
            $table->bigInteger('phase_id')->unsigned();
//            $table->boolean('regulation');
            $table->string('performance')->nullable();
//            $table->float('coef_a')->nullable();
//            $table->float('coef_b')->nullable();
//            $table->float('coef_c')->nullable();
        });

        Schema::table('pumps', function (Blueprint $table) {
            $table->foreign('series_id')
                ->references('id')
                ->on('pump_series')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreign('currency_id')
                ->references('id')
                ->on('currencies')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreign('connection_type_id')
                ->references('id')
                ->on('connection_types')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreign('dn_input_id')
                ->references('id')
                ->on('dns')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreign('dn_output_id')
                ->references('id')
                ->on('dns')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreign('phase_id')
                ->references('id')
                ->on('current_phases')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreign('category_id')
                ->references('id')
                ->on('pump_categories')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
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
