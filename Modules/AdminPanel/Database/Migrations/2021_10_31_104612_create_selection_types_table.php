<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSelectionTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('selection_types');
        Schema::create('selection_types', function (Blueprint $table) {
            $table->id();
            $table->json('name');
            $table->string('prefix')->nullable();
            $table->string('default_img')->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::connection('landlord')->statement('SET FOREIGN_KEY_CHECKS = 0;');
        Schema::dropIfExists('selection_types');
        DB::connection('landlord')->statement('SET FOREIGN_KEY_CHECKS = 1;');
    }
}
