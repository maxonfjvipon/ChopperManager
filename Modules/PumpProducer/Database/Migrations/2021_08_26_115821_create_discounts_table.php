<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('discounts');
        Schema::create('discounts', function (Blueprint $table) {
            $table->float('value')->unsigned()->default(0);
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('discountable_id')->unsigned();
            $table->string('discountable_type');
            $table->primary(['discountable_id', 'user_id', 'discountable_type']); // fixme
        });
        Schema::table('discounts', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('discounts');
    }
}
