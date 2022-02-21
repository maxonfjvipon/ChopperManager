<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPumpSeriesIdsFieldToSelectionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('single_pump_selections', function (Blueprint $table) {
            $table->text('pump_series_ids');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('single_pump_selections', function (Blueprint $table) {
            $table->dropColumn('pump_series_ids');
        });
    }
}
