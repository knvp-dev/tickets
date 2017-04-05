<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('avatar')->default("default.jpg");
            $table->string('password');
            $table->string('stripe_id')->nullable();
            $table->boolean('stripe_active')->default(false);
            $table->string('stripe_subscription')->nullable();
            $table->timestamp('subscription_end_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('team_user', function (Blueprint $table) {
            $table->integer('team_id');
            $table->integer('user_id');
            $table->primary(['team_id', 'user_id']);
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
        Schema::dropIfExists('team_user');
    }
}
