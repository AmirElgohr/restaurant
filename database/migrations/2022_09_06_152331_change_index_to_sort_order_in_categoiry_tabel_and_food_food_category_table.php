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
        Schema::table('food_food_category', function (Blueprint $table) {
            $table->renameColumn('index', 'sort_order')->index();
        });
        Schema::table('food_categories', function (Blueprint $table) {
            $table->renameColumn('index', 'sort_order')->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('food_food_category', function (Blueprint $table) {
            $table->renameColumn('sort_order', 'index');
        });
        Schema::table('food_categories', function (Blueprint $table) {
            $table->renameColumn('sort_order', 'index');
        });
    }
};
