<?php

namespace App\Http\Controllers;

use App\Models\FoodCategory;
use App\Models\User;
use App\Repositories\Restaurant\FoodCategoryRepository;
use App\Repositories\Restaurant\FoodRepository;
use App\Repositories\Restaurant\RestaurantRepository;
use App\Repositories\Restaurant\UserRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cookie;

class HomeController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {


        $user = auth()->user();
        $restaurant = $user->restaurant;
        $params = [];
        if($user->user_type != User::USER_TYPE_ADMIN){
            $params['user_id'] = $user->id;
        }
        $data['user'] = $user;

        if($user->hasRole('admin')){
//            $data['restaurants_count'] = (new RestaurantRepository())->getCountRestaurants($params);

            $data['users_count'] = (new UserRepository())->getRestaurantsCount();
            $data['categories_count'] = (new FoodCategoryRepository)->getCountRestaurantFoodCategories();
            $data['foods_count'] = (new FoodRepository())->getUserRestaurantFoodCount();

            $data['restaurants'] = (new RestaurantRepository())->getUserRestaurantsDetails(['latest' => 1, 'recodes' => 6]);
//            $data['users'] = (new UserRepository())->getRestaurantUsersRecodes(['user_id' => $user->id, 'recodes' => 6]);
            $data['categories'] = (new FoodCategoryRepository)->getRestaurantCategories(['recodes' => 6]);
            $data['foods'] = (new FoodRepository)->getUserRestaurantFoodsCustome(['recodes' => 6]);
        }else{
            $data['categories_count'] = (new FoodCategoryRepository)->getCountRestaurantFoodCategories(['restaurant_id' => $restaurant->id]);
            $data['foods_count'] = (new FoodRepository())->getUserRestaurantFoodCount(['restaurant_id' => $restaurant->id]);

            $data['categories'] = (new FoodCategoryRepository)->getRestaurantCategories(['restaurant_id' => $restaurant->id, 'recodes' => 6]);
            $data['foods'] = (new FoodRepository)->getUserRestaurantFoodsCustome(['restaurant_id' => $restaurant->id, 'recodes' => 6]);
        }

        // dd($data['users']);
        return view('dashboard.index', $data);
    }

    public static function getCurrentUsersAllRestaurants()
    {
        $user = auth()->user();
        $params = [];
        if($user->user_type != User::USER_TYPE_ADMIN)
        {
            $params['user_id'] = $user->id;
        }

        return (new RestaurantRepository())->getUserRestaurantsDetails($params);
    }


    public function globalSearch()
    {
        $request = request();
        $search = [];
        if(strlen($request->search) > 2)
        {
            $search = globalSearch($request->search, $request->user());
        }

        return view('layouts.search')->with('search', $search);
    }
}
