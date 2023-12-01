<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('restaurant_id')->comment('Restaurant ID')->nullable()->index();
            $table->foreign('restaurant_id')->on('restaurants')->references('id')->onDelete('cascade');
        });

        Schema::create('restaurant_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('restaurant_id')->comment('Restaurant ID')->nullable()->index();
            $table->foreign('restaurant_id')->on('restaurants')->references('id')->onDelete('cascade');
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->tinyInteger('role')->comment('1 - Admin')->default(1);
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
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('restaurant_id');
        });
        Schema::dropIfExists('restaurant_users');
    }
};