<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePumpAndTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('pumps_and_types');
        Schema::create('pumps_and_types', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('pump_id')->unsigned();
            $table->bigInteger('type_id')->unsigned();
        });

        Schema::table('pumps_and_types', function (Blueprint $table) {
            $table->foreign('pump_id')
                ->references('id')
                ->on('pumps')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreign('type_id')
                ->references('id')
                ->on('pump_types')
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
        Schema::dropIfExists('pumps_and_types');
    }
}
