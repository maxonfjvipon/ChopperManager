<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePumpAndApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('pumps_and_applications');
        Schema::create('pumps_and_applications', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('pump_id')->unsigned();
            $table->bigInteger('application_id')->unsigned();
        });

        Schema::table('pumps_and_applications', function (Blueprint $table) {
            $table->foreign('application_id')
                ->references('id')
                ->on('pump_applications')
                ->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pumps_and_applications');
    }
}
