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
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('email');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->rememberToken()->nullable();
            $table->timestamps();
            $table->integer('shop');
            $table->string('provider');
            $table->string('provider_id')->nullable();
            $table->integer('points')->defult(0);
            $table->boolean('google_like')->defult(0);
            $table->boolean('facebook_like')->defult(0);
            $table->boolean('instagram_like')->defult(0);
            $table->boolean('youtube_like')->defult(0);
            $table->boolean('redeme')->defult(0);
        });

        Schema::create('pointtransactions', function (Blueprint $table) {
            $table->bigincrements('id');
            $table->integer('user_id')->references('id')->on('users')->comment('user id of the user');
            $table->string('type')->comment('type of transcation, credit or debit');
            $table->integer('points')->comment('actual points transcated');
            $table->string('remarks')->nullable()->comment('remarks of the transcation, can add the reason');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pointtransactions');
        Schema::dropIfExists('users');
    }
}
