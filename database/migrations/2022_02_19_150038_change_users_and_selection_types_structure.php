<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeUsersAndSelectionTypesStructure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users_and_selection_types', function (Blueprint $table) {
            $table->foreign('type_id')->references('id')->on('selection_types')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users_and_selection_types', function (Blueprint $table) {
            $table->dropForeign('users_and_selection_types_type_id_foreign');
        });
    }
}
