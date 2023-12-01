<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RemoveFoodCategoryIdInFoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('foods', function (Blueprint $table) {
            $table->dropForeign('food_food_category_id_foreign');
            $table->dropColumn('food_category_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('foods', function (Blueprint $table) {
            $table->unsignedBigInteger('food_category_id')->nullable()->index();
            $table->foreign('food_category_id')->on('food_categories')->references('id')->onDelete('cascade');
        });
    }
}
