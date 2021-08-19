<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('users');
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('inn', 20)->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->rememberToken();
            $table->string('phone', 20);
            $table->string('fio')->nullable();
            $table->bigInteger('business_id')->unsigned();
            $table->bigInteger('role_id')->unsigned();
            $table->bigInteger('city_id')->unsigned();
            $table->timestamp('created_at', 0)->useCurrent();
            $table->boolean('deleted')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
