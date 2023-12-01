<?php

namespace App\Repositories\Restaurant;

use App\Models\Food;
use App\Models\FoodCategory;
use Illuminate\Support\Facades\DB;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;



/**
 * Class FoodRepository.
 */
class FoodRepository extends BaseRepository
{
    /**
     * @return string
     *  Return the model
     */
    public function model()
    {
        return Food::class;
    }

    public function table()
    {
        return $this->model->getTable();
    }

    public function getUserRestaurantFoodsQuery($params)
    {
        $table = $this->table();
        return $this->model->when(isset($params['restaurant_id']), function ($query) use ($params, $table) {
            $query->where("$table.restaurant_id", $params['restaurant_id']);
        })->select(
            "$table.id",
            "$table.name",
            "$table.description",
            "$table.price",
            "$table.preparation_time",
            "$table.is_available",
            "$table.gallery_images",
            "$table.food_image",
            "$table.lang_name",
            "$table.lang_description",
            "$table.created_at",
        );
    }

    public function getUserRestaurantFoods($params)
    {
        DB::enableQueryLog();
        $table = $this->table();
        $food_category_table = (new FoodCategory)->getTable();

        $foods = $this->getUserRestaurantFoodsQuery($params)->with('food_categories')
            ->when(isset($params['food_category_id']), function ($query) use ($params, $table, $food_category_table) {

                $query->whereHas('food_categories', function ($query1) use ($params, $food_category_table) {
                    $query1->where("$food_category_table.id", $params['food_category_id']);
                })->leftJoin('food_food_category', "$table.id", 'food_food_category.food_id')->where('food_food_category.food_category_id', $params['food_category_id'])->orderby('food_food_category.sort_order');
            })->orderby("$table.id", 'desc')
            ->when(isset($params['filter']), function ($q) use ($params, $table, $food_category_table) {
                $q->where(function ($query) use ($params, $table, $food_category_table) {
                    $query->where("$table.name", 'like', '%' . $params['filter'] . '%');
                    $query->orwhere("$table.id", "=",  $params['filter']);
                    $query->orwhere("$table.price", "=",  $params['filter']);
                });
            })

            // ->paginate($params['par_page']);
            ->get();
        // dd(DB::getQueryLog(), $foods);
        return $foods;
    }

    public function getUserRestaurantFood($params)
    {
        $food = $this->getUserRestaurantFoodsQuery($params)->where('id', $params['id'])->first();
        return $food;
    }

    public function getUserRestaurantFoodCount($params = null)
    {
        $food = $this->getUserRestaurantFoodsQuery($params)->count('id');
        return $food;
    }
    public function getUserRestaurantFoodsCustome($params = null)
    {
        $food = $this->getUserRestaurantFoodsQuery($params)->orderBy('id', 'desc');
        if (isset($params['recodes'])) {
            $food = $food->limit($params['recodes']);
        }
        $food = $food->get();
        return $food;
    }
}
