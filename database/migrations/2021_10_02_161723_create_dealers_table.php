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
        Schema::create('dealers', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string('name', 512);
            $table->string('itn', 12)->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();

            $table->boolean('without_pumps')->default(false);

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
        Schema::dropIfExists('dealers');
    }
};
