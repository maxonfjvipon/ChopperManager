<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDoublePumpWorkSchemeIdToSelectionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('selections', function (Blueprint $table) {
            $table->bigInteger('double_pump_work_scheme_id')->unsigned()->nullable();

            $table->foreign('double_pump_work_scheme_id')->references('id')->on('double_pump_work_schemes')->cascadeOnUpdate()->cascadeOnDelete();
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
            $table->dropForeign('selections_double_pump_work_scheme_id_foreign');
            $table->dropColumn('double_pump_work_scheme_id');
        });
    }
}
