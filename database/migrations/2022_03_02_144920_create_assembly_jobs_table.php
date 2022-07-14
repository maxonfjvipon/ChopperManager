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
            $table->timestamp('price_updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->float('price');
            $table->float('pumps_weight');

            $table->unsignedTinyInteger('currency');
            $table->unsignedTinyInteger('collector_type');
            $table->bigInteger('control_system_type_id')->unsigned()->nullable();

            $table->softDeletes();

            $table->foreign('control_system_type_id')->references('id')->on('control_system_types')->cascadeOnDelete()->cascadeOnUpdate();
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
