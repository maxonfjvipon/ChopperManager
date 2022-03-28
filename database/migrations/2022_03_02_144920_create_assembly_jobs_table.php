<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssemblyJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assembly_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('article', 64)->unique();
            $table->integer('pumps_count');
            $table->timestamp('price_update_at')->useCurrent()->useCurrentOnUpdate();
            $table->float('price');
            $table->float('pumps_weight');

            $table->bigInteger('currency_id')->unsigned();
            $table->bigInteger('control_system_type_id')->unsigned()->nullable();
            $table->bigInteger('control_system_id')->unsigned()->nullable();

            $table->softDeletes();

            $table->foreign('currency_id')->references('id')->on('currencies')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('control_system_type_id')->references('id')->on('control_system_types')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('control_system_id')->references('id')->on('control_systems')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('assembly_jobs');
    }
}