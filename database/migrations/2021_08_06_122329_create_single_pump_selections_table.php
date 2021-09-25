<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSinglePumpSelectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('single_pump_selections');
        Schema::create('single_pump_selections', function (Blueprint $table) {
            $table->id();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->bigInteger('project_id')->unsigned();
            $table->bigInteger('pump_id')->unsigned();
            $table->string('selected_pump_name'); // fixme ???
            $table->integer('pumps_count')->unsigned();
            $table->float('flow');
            $table->float('head');
            $table->float('fluid_temperature')->default(20);
            $table->float('deviation')->nullable()->default(0);
            $table->tinyInteger('reserve_pumps_count')->unsigned();

            $table->boolean('power_limit_checked')->default(false);
            $table->bigInteger('power_limit_condition_id')->unsigned()->nullable();
            $table->integer('power_limit_value')->unsigned()->nullable();

            $table->boolean('ptp_length_limit_checked')->default(false);
            $table->bigInteger('ptp_length_limit_condition_id')->unsigned()->nullable();
            $table->integer('ptp_length_limit_value')->unsigned()->nullable();

            $table->boolean('dn_suction_limit_checked')->default(false);
            $table->bigInteger('dn_suction_limit_condition_id')->unsigned()->nullable();
            $table->bigInteger('dn_suction_limit_id')->unsigned()->nullable();

            $table->boolean('dn_pressure_limit_checked')->default(false);
            $table->bigInteger('dn_pressure_limit_condition_id')->unsigned()->nullable();
            $table->bigInteger('dn_pressure_limit_id')->unsigned()->nullable();

            $table->string('connection_type_ids', 1024)->nullable();
            $table->string('mains_connection_ids', 3)->nullable();
            $table->string('main_pumps_counts', 20);
            $table->string('pump_brand_ids');
            $table->string('power_adjustment_ids', 3)->nullable();
            $table->string('pump_type_ids')->nullable();
            $table->string('pump_application_ids')->nullable();

            $table->softDeletes();
        });
        Schema::table('single_pump_selections', function (Blueprint $table) {
            $table->foreign('project_id')->references('id')->on('projects')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('power_limit_condition_id')->references('id')->on('limit_conditions')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('ptp_length_limit_condition_id', 'spscdlci')->references('id')->on('limit_conditions')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('dn_suction_limit_condition_id')->references('id')->on('limit_conditions')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('dn_suction_limit_id')->references('id')->on('dns')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('dn_pressure_limit_condition_id')->references('id')->on('limit_conditions')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('dn_pressure_limit_id')->references('id')->on('dns')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('pump_id')->references('id')->on('pumps')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('single_pump_selections');
    }
}
