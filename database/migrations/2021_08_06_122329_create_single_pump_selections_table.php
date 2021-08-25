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
            $table->string('selected_pump_name');
            $table->integer('pumps_count')->unsigned();
            $table->float('pressure');
            $table->float('consumption');
            $table->float('liquid_temperature')->default(20);
            $table->float('limit')->nullable()->default(0);
            $table->tinyInteger('backup_pumps_count')->unsigned();

            $table->boolean('power_limit_checked')->default(false);
            $table->bigInteger('power_limit_condition_id')->unsigned()->nullable();
            $table->integer('power_limit_value')->unsigned()->nullable();

            $table->boolean('between_axes_limit_checked')->default(false);
            $table->bigInteger('between_axes_limit_condition_id')->unsigned()->nullable();
            $table->integer('between_axes_limit_value')->unsigned()->nullable();

            $table->boolean('dn_input_limit_checked')->default(false);
            $table->bigInteger('dn_input_limit_condition_id')->unsigned()->nullable();
            $table->bigInteger('dn_input_limit_id')->unsigned()->nullable();

            $table->boolean('dn_output_limit_checked')->default(false);
            $table->bigInteger('dn_output_limit_condition_id')->unsigned()->nullable();
            $table->bigInteger('dn_output_limit_id')->unsigned()->nullable();

            $table->string('connection_type_ids', 1024)->nullable();
            $table->string('current_phase_ids', 3)->nullable();
            $table->string('main_pumps_counts', 20);
            $table->string('pump_producer_ids');
            $table->string('pump_regulation_ids', 3)->nullable();
            $table->string('pump_type_ids')->nullable();
            $table->string('pump_application_ids')->nullable();

            $table->string('separator')->default('|');

            $table->boolean('deleted')->default(false);
        });
        Schema::table('single_pump_selections', function (Blueprint $table) {
            $table->foreign('project_id')->references('id')->on('projects')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('power_limit_condition_id')->references('id')->on('limit_conditions')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('between_axes_limit_condition_id')->references('id')->on('limit_conditions')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('dn_input_limit_condition_id')->references('id')->on('limit_conditions')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('dn_input_limit_id')->references('id')->on('dns')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('dn_output_limit_condition_id')->references('id')->on('limit_conditions')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('dn_output_limit_id')->references('id')->on('dns')->cascadeOnUpdate()->cascadeOnDelete();
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
