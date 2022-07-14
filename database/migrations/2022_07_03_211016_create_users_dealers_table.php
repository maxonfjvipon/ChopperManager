<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_dealers', function (Blueprint $table) {
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('dealer_id')->unsigned();
            $table->primary(['user_id', 'dealer_id']);

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('dealer_id')->references('id')->on('dealers')->cascadeOnUpdate()->cascadeOnDelete();
        });
        Schema::table('users', function (Blueprint $table) {
            $table->bigInteger('dealer_id')->unsigned();

            $table->foreign('dealer_id')->references('id')->on('dealers')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_dealers');
    }
};
