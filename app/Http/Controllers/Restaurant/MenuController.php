<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use App\Models\Food;
use App\Models\FoodCategory;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MenuController extends Controller
{
    public function show(Restaurant $restaurant)
    {
        $dataTheme = $restaurant->theme;
        if (request()->restaurant_view) {
            $dataTheme = request()->restaurant_view;
        }

        switch ($dataTheme) {
            case ('theme1'):
                $theme = 'theme1';
                $restaurant->load(['food_categories.foods' => function ($q) {
                    return $q->where('is_available', 1);
                }, 'foods']);
                break;
            case ('theme2'):
                $restaurant->load(['food_categories.foods']);
                $theme = 'theme2';
                break;
            case ('theme3'):
                $theme = 'theme3';
                $restaurant->load(['food_categories.foods' => function ($q) {
                    return $q->where('is_available', 1);
                }]);
                break;
            case ('theme4'):
                $theme = 'theme4';
                $restaurant->load(['food_categories.foods' =>  function ($q) {
                    return $q->where('is_available', 1);
                }, 'foods']);
                break;

            case ('theme5'):
                $theme = 'theme1';
                $restaurant->load(['food_categories.foods']);
                break;
            default:
                $restaurant->load(['food_categories.foods' =>  function ($q) {
                    return $q->where('is_available', 1);
                }]);
                $theme = 'theme1';
                break;
        }

        return view("frontend.$theme.index", ['restaurant' => $restaurant, 'food_categories' => $restaurant->food_categories]);
    }

    public function categoryItems(Restaurant $restaurant, FoodCategory $foodCategory)
    {
        if (request()->restaurant_view) {
            if (!in_array(request()->restaurant_view, ['theme2', 'theme4']))
                return redirect(route('restaurant.menu', ['restaurant' => $restaurant, 'restaurant_view' => request()->restaurant_view]));
        } else {
            if (!(in_array($restaurant->theme, ['theme2', 'theme4'])))
                return redirect(route('restaurant.menu', ['restaurant' => $restaurant]));
        }
        // DB::enableQueryLog();
        $restaurant->load(['food_categories' => function ($q) use ($foodCategory) {
            $q->where('id', $foodCategory->id);
        }]);
        $theme = request()->restaurant_view ?? $restaurant->theme;
        if (count($restaurant->food_categories) == 0)
            return redirect(route('restaurant.menu', ['restaurant' => $restaurant]));
        $restaurant->load(['food_categories']);
        $categoires = $restaurant->food_categories->pluck('local_lang_name', 'id');
        $foodCategory->load(['foods' => function ($q) {
            return $q->where('is_available', 1);
        }]);

        return view("frontend.{$theme}.foods", ['restaurant' => $restaurant, 'categoires' => $categoires, 'food_category' => $foodCategory, 'foods' => $foodCategory->foods]);
    }
}
