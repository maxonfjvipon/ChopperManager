<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\User\Entities\UserRole;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            $table->timestamps();

            $table->string('email')->unique();
            $table->string('password');
            $table->string('first_name');
            $table->string('middle_name');
            $table->string('last_name')->nullable();
            $table->string('itn')->unique()->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('organization_name')->nullable();

            $table->boolean('is_active')->default(true);

            $table->unsignedTinyInteger('role')->default(UserRole::Dealer);

            $table->bigInteger('area_id')->unsigned();

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
        Schema::dropIfExists('users');
    }
}
