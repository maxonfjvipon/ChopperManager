<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChassisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chassis', function (Blueprint $table) {
            $table->id();
            $table->string('article', 64)->unique();
            $table->integer('pumps_count');
            $table->timestamp('price_updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->float('pumps_weight');
            $table->float('price');

            $table->softDeletes();

            $table->bigInteger('currency_id')->unsigned();

            $table->foreign('currency_id')->references('id')->on('currencies')->cascadeOnDelete()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chassis');
    }
}
