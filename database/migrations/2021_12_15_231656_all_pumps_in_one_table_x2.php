<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AllPumpsInOneTableX2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pumps', function (Blueprint $table) {
            $table->float('weight')->nullable()->change();
            $table->float('rated_power', 8, 4)->nullable()->change();
            $table->float('rated_current', 8, 3)->nullable()->change();
            $table->bigInteger('connection_type_id')->unsigned()->nullable()->change();
            $table->float('fluid_temp_min')->nullable()->change();
            $table->float('fluid_temp_max')->nullable()->change();
            $table->integer('ptp_length')->nullable()->change();
            $table->bigInteger('dn_suction_id')->unsigned()->nullable()->change();
            $table->bigInteger('dn_pressure_id')->unsigned()->nullable()->change();
            $table->bigInteger('connection_id')->unsigned()->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
