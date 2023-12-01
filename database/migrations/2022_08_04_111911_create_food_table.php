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
        Schema::create('food', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('restaurant_id')->nullable()->index();
            $table->unsignedBigInteger('food_category_id')->nullable()->index();
            $table->foreign('restaurant_id')->on('restaurants')->references('id')->onDelete('cascade');
            $table->foreign('food_category_id')->on('food_categories')->references('id')->onDelete('cascade');
            $table->string('name', 150);
            $table->text('description')->nullable();
            $table->double('price');
            $table->json('ingredient')->nullable();
            $table->string('preparation_time', 40);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_available')->default(true);
            $table->boolean('is_out_of_sold')->default(false);
            $table->string('label_image', 150)->nullable();
            $table->string('food_image', 150)->nullable();
            $table->json('language')->nullable();
            $table->unique(['restaurant_id', 'name']);
            $table->timestamps();
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
        Schema::dropIfExists('food');
    }
};