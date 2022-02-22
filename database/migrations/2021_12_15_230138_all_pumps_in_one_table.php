<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AllPumpsInOneTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pumps', function (Blueprint $table) {
            $table->renameColumn('performance', 'sp_performance');
            $table->string('pumpable_type');
            $table->text('dp_peak_performance')->nullable();
            $table->text('dp_standby_performance')->nullable();
        });
        Schema::table('selections', function (Blueprint $table) {
            $table->renameColumn('double_pump_work_scheme_id', 'dp_work_scheme_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('selections', function (Blueprint $table) {
            $table->renameColumn('dp_work_scheme_id', 'double_pump_work_scheme_id');
        });
        Schema::table('pumps', function (Blueprint $table) {
            $table->renameColumn('sp_performance', 'performance');
            $table->dropColumn('pumpable_type');
            $table->dropColumn('dp_standby_performance');
            $table->dropColumn('dp_peak_performance');
        });
    }
}