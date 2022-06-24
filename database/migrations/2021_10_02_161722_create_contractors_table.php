<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contractors', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name', 1024);
            $table->string('email')->nullable();
            $table->string('itn')->nullable();
            $table->string('phone')->nullable();

            $table->bigInteger('area_id')->unsigned();

            $table->foreign('area_id')->references('id')->on('areas')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contractors');
    }
};
