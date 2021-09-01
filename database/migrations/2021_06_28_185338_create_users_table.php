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
            $table->string('organization_name');
            $table->string('itn', 20)->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->rememberToken();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('phone', 20);
            $table->string('first_name');
            $table->string('middle_name');
            $table->string('last_name')->nullable();
            $table->bigInteger('business_id')->unsigned();
            $table->bigInteger('role_id')->unsigned()->default(2);
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
