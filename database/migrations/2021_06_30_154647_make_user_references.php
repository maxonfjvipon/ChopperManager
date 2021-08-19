<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeUserReferences extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('business_id')
                ->references('id')
                ->on('businesses')
                ->cascadeOnUpdate();
            $table->foreign('role_id')
                ->references('id')
                ->on('roles')
                ->cascadeOnUpdate();
            $table->foreign('city_id')
                ->references('id')
                ->on('cities')
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
    }
}
