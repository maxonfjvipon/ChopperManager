<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCountryAndCurrencyReferenceToUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->bigInteger('country_id')->unsigned();
            $table->bigInteger('currency_id')->unsigned();
            $table->foreign('country_id')->references('id')->on('countries')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('currency_id')->references('id')->on('currencies')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('users_country_id_foreign');
            $table->dropForeign('users_currency_id_foreign');

            $table->dropColumn('currency_id');
            $table->dropColumn('country_id');
        });
    }
}
