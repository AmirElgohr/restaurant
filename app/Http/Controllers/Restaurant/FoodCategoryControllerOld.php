<?php

namespace App\Http\Controllers\Restaurant;

use Illuminate\Support\Str;
use App\Models\FoodCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\File\File;
use App\Http\Requests\Restaurant\FoodCategoryRequest;
use App\Models\Food;
use App\Repositories\Restaurant\FoodCategoryRepository;
use Illuminate\Http\UploadedFile;

class FoodCategoryControllerOld extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {

        $request = request();
        $user = auth()->user();

        $params = $request->only('par_page', 'sort', 'direction', 'filter', 'restaurant_id');
        // $par_page = 10;
        // if (in_array($request->par_page, [10, 25, 50, 100])) {
        //     $par_page = $request->par_page;
        // }
        // $params['par_page'] = $par_page;
        $params['restaurant_id'] = $params['restaurant_id'] ?? $user->restaurant_id;
        $foodCategories = (new FoodCategoryRepository)->getRestaurantFoodCategories($params);
        return view('restaurant.food_categories.index', ['foodCategories' => $foodCategories]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('restaurant.food_categories.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FoodCategoryRequest $request)
    {
        try {
            // dd($request->all());
            DB::beginTransaction();
            $input = $request->only('category_name', 'restaurant', 'restaurant_id', 'category_image', 'lang_category_name');
            $input['sort_order'] = FoodCategory::max('sort_order') + 1;
            FoodCategory::create($input);
            DB::commit();
            $request->session()->flash('Success', __('system.messages.saved', ['model' => __('system.food_categories.title')]));
        } catch (\Illuminate\Database\QueryException $ex) {
            DB::rollback();
            $request->session()->flash('Error', __('system.messages.operation_rejected'));
            return redirect()->back();
        }
        return redirect()->route('restaurant.food_categories.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FoodCategory  $foodCategory
     * @return \Illuminate\Http\Response
     */
    public function show(FoodCategory $foodCategory)
    {
    }

    public function checkRestaurantIsValidFoodCategory($food_category_id, $user = null)
    {

        if (empty($user)) {
            $user = auth()->user();
        }
        $user->load(['restaurant.food_categories' => function ($q) use ($food_category_id) {
            $q->where('id', $food_category_id);
        }]);
        if (!isset($user->restaurant) || count($user->restaurant->food_categories) == 0) {
            $back = request()->get('back', route('restaurant.food_categories.index'));
            request()->session()->flash('Error', __('system.messages.not_found', ['model' => __('system.food_categories.title')]));

            return $back;
        }
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FoodCategory  $foodCategory
     * @return \Illuminate\Http\Response
     */
    public function edit(FoodCategory $foodCategory)
    {
        if (($redirect = $this->checkRestaurantIsValidFoodCategory($foodCategory->id)) != null) {
            return redirect($redirect);
        }
        return view('restaurant.food_categories.edit', ['foodCategory' => $foodCategory]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FoodCategory  $foodCategory
     * @return \Illuminate\Http\Response
     */
    public function update(FoodCategoryRequest $request, FoodCategory $foodCategory)
    {

        if (($redirect = $this->checkRestaurantIsValidFoodCategory($foodCategory->id)) != null) {
            return redirect($redirect);
        }
        $input = $request->only('category_name', 'category_image', 'lang_category_name');
        $foodCategory->fill($input)->save();

        $request->session()->flash('Success', __('system.messages.updated', ['model' => __('system.food_categories.title')]));
        if ($request->back) {
            return redirect($request->back);
        }
        return redirect(route('restaurant.food_categories.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FoodCategory  $foodCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(FoodCategory $foodCategory)
    {
        $request = request();
        if (($redirect = $this->checkRestaurantIsValidFoodCategory($foodCategory->id)) != null) {
            return redirect($redirect);
        }
        $foodCategory->load('foods');
        $foods = $foodCategory->foods;
        $foodCategory->delete();
        $foods->loadCount('food_categories');
        foreach ($foods as $food) {
            if ($food->food_categories_count == 0) {
                $food->delete();
            }
        }
        $request->session()->flash('Success', __('system.messages.deleted', ['model' => __('system.food_categories.title')]));
        if ($request->back) {
            return redirect($request->back);
        }
        return redirect(route('restaurant.food_categories.index'));
    }
    public function positionChange()
    {
        $request = request();
        $foodCategory = FoodCategory::where('id', $request->id)->update(['sort_order' => $request->index]);
        return true;
    }


    public static function getCurrentRestaurantAllFoodCategories()
    {
        $user = request()->user();
        $user->load(['restaurant.food_categories' => function ($q) {
            $q->orderBy('category_name', 'asc');
        }]);
        $food_categories = $user->restaurant->food_categories->mapWithKeys(function ($food_category, $key) {
            return [$food_category->id => $food_category->category_name];
        });
        return ['' => __('system.fields.select_Category')] + $food_categories->toarray();
    }

    public function add_static_data()
    {

        $request = request();
        if ($request->please_add_data) {
            ini_set('memory_limit', -1);
            $data =  array(
                array(
                    'name' => 'Mocktails1',
                    'image' => 'https://firebasestorage.googleapis.com/v0/b/navjivan-group.appspot.com/o/mocktails.jpg?alt=media&token=cd5a7366-ae75-47ef-b724-60fb10ddcccc',
                    'data' => array(
                        0 =>
                        array(
                            'name' => 'Long Island Iced Tea ',
                            'price' => '175',
                            'desc' => 'Choice of flavor Lemon, Peach, Black Currant ',
                        ),
                        1 =>
                        array(
                            'name' => 'Lime Mojito ',
                            'price' => '215',
                            'desc' => 'Mint, Lime, Sprite ',
                        ),
                        2 =>
                        array(
                            'name' => 'Passion Fruit Mojito ',
                            'price' => '225',
                            'desc' => 'Passion Fruit Syrup, Mint, Lime, Soda ',
                        ),
                        3 =>
                        array(
                            'name' => 'Orange Mimosa ',
                            'price' => '225',
                            'desc' => 'Orange Juice, Fresh Mint, Lemon Juice, sprite ',
                        ),
                        4 =>
                        array(
                            'name' => 'Blue Breezer ',
                            'price' => '225',
                            'desc' => 'Blue Caracoa, Litchi Crush, Grenadine Syrup, Lime Juice, Sprite ',
                        ),
                        5 =>
                        array(
                            'name' => 'Refreshing Thai Coconut ',
                            'price' => '250',
                            'desc' => 'Coconut Syrup, Basil Leaves, Lime Juice, Topped with Sprite',
                        ),
                        6 =>
                        array(
                            'name' => 'Pina Colada ',
                            'price' => '225',
                            'desc' => 'Pineapple Juice, Coconut Milk, Ice cream ',
                        ),
                        7 =>
                        array(
                            'name' => 'Tom &amp; Jerry ',
                            'price' => '250',
                            'desc' => 'Apple Juice, Orange Juice, Grenadine Syrup',
                        ),
                        8 =>
                        array(
                            'name' => 'Strawberry Citrus ',
                            'price' => '240',
                            'desc' => 'Strawberry crush, Blackberry syrup, Lime juice, Soda',
                        ),
                        9 =>
                        array(
                            'name' => 'Mai Thai ',
                            'price' => '240',
                            'desc' => 'Pineapple Juice, Pineapple Crush and Cranberry Juice',
                        ),
                        10 =>
                        array(
                            'name' => 'Italian Cream Soda ',
                            'price' => '265',
                            'desc' => 'Passion Fruit, Water melon, Sprite, Soda, Cream ',
                        ),
                        11 =>
                        array(
                            'name' => 'Sparkling Mix Fruit Shagria ',
                            'price' => '265',
                            'desc' => 'Muddled Fresh Fruit, fruit crushes topped with Soda &amp; Sprite',
                        ),
                        12 =>
                        array(
                            'name' => 'Litchi Coconut Mocker ',
                            'price' => '265',
                            'desc' => 'Milk, Litchi, Coconut Milk, Ice Cream',
                        ),
                        13 =>
                        array(
                            'name' => 'Shining Bull ',
                            'price' => '285',
                            'desc' => 'Water Melon, Strawberry, Cranberry, Grenadine syrup, Red Bull',
                        ),
                        14 =>
                        array(
                            'name' => 'Bull Red ',
                            'price' => '285',
                            'desc' => 'Raspberry Syrup, Honey, Lime, Red Bull',
                        ),
                    )
                ),
                array(
                    'name' => 'Continental Soups',
                    'image' => 'https://firebasestorage.googleapis.com/v0/b/navjivan-group.appspot.com/o/lakeviewnew%2FSoup%20(1).jpg?alt=media&token=424afa6c-b4ae-498a-ba1e-77f8bb09d7e9',
                    'data' => array(
                        0 =>
                        array(
                            'name' => 'Cream of Tomato ',
                            'price' => '155',
                            'desc' => NULL,
                        ),
                        1 =>
                        array(
                            'name' => 'Cream of Mushroom ',
                            'price' => '160',
                            'desc' => NULL,
                        ),
                        2 =>
                        array(
                            'name' => 'Cream of Vegetable ',
                            'price' => '160',
                            'desc' => NULL,
                        ),
                        3 =>
                        array(
                            'name' => 'Cream of Garlic ',
                            'price' => '160',
                            'desc' => NULL,
                        ),
                        4 =>
                        array(
                            'name' => 'Minestrone  ',
                            'price' => '160',
                            'desc' => NULL,
                        ),
                        5 =>
                        array(
                            'name' => 'Mexican Tortilla ',
                            'price' => '165',
                            'desc' => NULL,
                        ),
                        6 =>
                        array(
                            'name' => 'Broccoli Almond ',
                            'price' => '185',
                            'desc' => NULL,
                        ),
                    )
                ),
                array(
                    'name' => 'Chinese Soups',
                    'image' => 'https://firebasestorage.googleapis.com/v0/b/navjivan-group.appspot.com/o/Hot-Sour-Soup-4-square-scaled.jpg?alt=media&token=faf63270-c666-4dd6-8eb6-c0c990aef6e4',
                    'data' => array(
                        0 =>
                        array(
                            'name' => 'Vegetable Clear ',
                            'price' => '155',
                            'desc' => NULL,
                        ),
                        1 =>
                        array(
                            'name' => 'Sweet Corn ',
                            'price' => '160',
                            'desc' => NULL,
                        ),
                        2 =>
                        array(
                            'name' => 'Tum Yum ',
                            'price' => '160',
                            'desc' => NULL,
                        ),
                        3 =>
                        array(
                            'name' => 'Hot &amp; Sour  ',
                            'price' => '160',
                            'desc' => NULL,
                        ),
                        4 =>
                        array(
                            'name' => 'Lemon Coriander	 ',
                            'price' => '160',
                            'desc' => NULL,
                        ),
                        5 =>
                        array(
                            'name' => 'Manchow ',
                            'price' => '160',
                            'desc' => NULL,
                        ),
                        6 =>
                        array(
                            'name' => 'Shanghai Soup ',
                            'price' => '165',
                            'desc' => NULL,
                        ),
                        7 =>
                        array(
                            'name' => 'Spinach Noodles Soup ',
                            'price' => '170',
                            'desc' => NULL,
                        ),
                        8 =>
                        array(
                            'name' => 'Burmese Soup ',
                            'price' => '175',
                            'desc' => NULL,
                        ),
                        9 =>
                        array(
                            'name' => 'Hawa Hawai Soup ',
                            'price' => '170',
                            'desc' => NULL,
                        ),
                        10 =>
                        array(
                            'name' => 'Green Asian Soup ',
                            'price' => '170',
                            'desc' => NULL,
                        ),
                    )

                ),
                array(
                    'name' => 'Accompaniments',
                    'image' => 'https://firebasestorage.googleapis.com/v0/b/navjivan-group.appspot.com/o/papad%20(1).jpg?alt=media&token=58dc480e-6e0b-4fae-a009-22dd685aec7f',
                    'data' => array(
                        0 =>
                        array(
                            'name' => 'Roasted Papad ',
                            'price' => '40',
                            'desc' => NULL,
                        ),
                        1 =>
                        array(
                            'name' => 'Fried Papad ',
                            'price' => '50',
                            'desc' => NULL,
                        ),
                        2 =>
                        array(
                            'name' => 'Masala Papad ',
                            'price' => '75',
                            'desc' => NULL,
                        ),
                        3 =>
                        array(
                            'name' => 'Cheese Masala Papad ',
                            'price' => '95',
                            'desc' => NULL,
                        ),
                        4 =>
                        array(
                            'name' => 'Karari Roomali ',
                            'price' => '180',
                            'desc' => NULL,
                        ),
                        5 =>
                        array(
                            'name' => 'Green Salad ',
                            'price' => '135',
                            'desc' => NULL,
                        ),
                        6 =>
                        array(
                            'name' => 'Masala Onion ',
                            'price' => '135',
                            'desc' => NULL,
                        ),
                        7 =>
                        array(
                            'name' => 'Tossed Salad ',
                            'price' => '165',
                            'desc' => NULL,
                        ),
                        8 =>
                        array(
                            'name' => 'German Potato Salad ',
                            'price' => '175',
                            'desc' => NULL,
                        ),
                        9 =>
                        array(
                            'name' => 'Batata Hara ',
                            'price' => '175',
                            'desc' => NULL,
                        ),
                        10 =>
                        array(
                            'name' => 'Russian Salad ',
                            'price' => '185',
                            'desc' => NULL,
                        ),
                        11 =>
                        array(
                            'name' => 'Cocktail Salad ',
                            'price' => '185',
                            'desc' => NULL,
                        ),
                        12 =>
                        array(
                            'name' => 'Boondi Raita ',
                            'price' => '145',
                            'desc' => NULL,
                        ),
                        13 =>
                        array(
                            'name' => 'Vegetable Raita ',
                            'price' => '145',
                            'desc' => NULL,
                        ),
                        14 =>
                        array(
                            'name' => 'Pudina Pyaaz Ka Raita ',
                            'price' => '145',
                            'desc' => NULL,
                        ),
                        15 =>
                        array(
                            'name' => 'Pineapple Raita ',
                            'price' => '175',
                            'desc' => NULL,
                        ),
                        16 =>
                        array(
                            'name' => 'Karari Roomali With Cheese ',
                            'price' => '205',
                            'desc' => NULL,
                        ),
                    ),
                ),
                array(
                    'name' => 'Indian Starters',
                    'image' => 'https://firebasestorage.googleapis.com/v0/b/navjivan-group.appspot.com/o/lakeviewnew%2FIndian%20Starter.jpg?alt=media&token=74c87385-320b-4e1e-82a3-a9edc9317abd',
                    'data' => array(
                        0 =>
                        array(
                            'name' => 'Hara Bhara Kebab ',
                            'price' => '250',
                            'desc' => NULL,
                        ),
                        1 =>
                        array(
                            'name' => 'Rajwadi Roll ',
                            'price' => '250',
                            'desc' => NULL,
                        ),
                        2 =>
                        array(
                            'name' => 'Veg Bullets ',
                            'price' => '265',
                            'desc' => NULL,
                        ),
                    ),
                ),
                array(
                    'name' => 'Chinese Starters',
                    'image' => 'https://firebasestorage.googleapis.com/v0/b/navjivan-group.appspot.com/o/lakeviewnew%2Fchinese%20starter.jpg?alt=media&token=440506bc-62e1-41f1-9610-231b0983d05f',
                    'data' => array(
                        0 =>
                        array(
                            'name' => 'Chinese Bhel (Cold) ',
                            'price' => '225',
                            'desc' => NULL,
                        ),
                        1 =>
                        array(
                            'name' => 'Sizzling Chilly Potato ',
                            'price' => '245',
                            'desc' => NULL,
                        ),
                        2 =>
                        array(
                            'name' => 'Vegetable Cilantro	Chilly ',
                            'price' => '260',
                            'desc' => NULL,
                        ),
                        3 =>
                        array(
                            'name' => 'Crispy Vegetables ',
                            'price' => '260',
                            'desc' => NULL,
                        ),
                        4 =>
                        array(
                            'name' => 'Humpty Dumpty ',
                            'price' => '260',
                            'desc' => NULL,
                        ),
                        5 =>
                        array(
                            'name' => 'Vegetable Green Balls ',
                            'price' => '260',
                            'desc' => NULL,
                        ),
                        6 =>
                        array(
                            'name' => 'Vegetable Lollipop ',
                            'price' => '260',
                            'desc' => NULL,
                        ),
                        7 =>
                        array(
                            'name' => 'Vegetable Manchurian Dry ',
                            'price' => '260',
                            'desc' => NULL,
                        ),
                        8 =>
                        array(
                            'name' => 'Vegetable “65” ',
                            'price' => '260',
                            'desc' => NULL,
                        ),
                        9 =>
                        array(
                            'name' => 'Peri Peri Balls ',
                            'price' => '260',
                            'desc' => NULL,
                        ),
                        10 =>
                        array(
                            'name' => 'Kung Pao Veg ',
                            'price' => '270',
                            'desc' => NULL,
                        ),
                        11 =>
                        array(
                            'name' => 'Babycorn Butter Pepper	 ',
                            'price' => '300',
                            'desc' => NULL,
                        ),
                        12 =>
                        array(
                            'name' => 'Mushroom Spicy Coriander ',
                            'price' => '300',
                            'desc' => NULL,
                        ),
                        13 =>
                        array(
                            'name' => 'Mushroom in Chilly Garlic Sauce ',
                            'price' => '300',
                            'desc' => NULL,
                        ),
                        14 =>
                        array(
                            'name' => 'Babycorn Mushroom Red Chilly Pepper ',
                            'price' => '300',
                            'desc' => NULL,
                        ),
                        15 =>
                        array(
                            'name' => 'Salt &amp; Pepper Paneer ',
                            'price' => '295',
                            'desc' => NULL,
                        ),
                        16 =>
                        array(
                            'name' => 'Tangy Paneer ',
                            'price' => '295',
                            'desc' => NULL,
                        ),
                        17 =>
                        array(
                            'name' => 'Basil Chilly Paneer	 ',
                            'price' => '295',
                            'desc' => NULL,
                        ),
                        18 =>
                        array(
                            'name' => 'Chilly Paneer ',
                            'price' => '295',
                            'desc' => NULL,
                        ),
                        19 =>
                        array(
                            'name' => 'Shanghai Paneer ',
                            'price' => '295',
                            'desc' => NULL,
                        ),
                        20 =>
                        array(
                            'name' => 'Honey Chilly Paneer ',
                            'price' => '295',
                            'desc' => NULL,
                        ),
                        21 =>
                        array(
                            'name' => 'Thai Chi Paneer ',
                            'price' => '295',
                            'desc' => NULL,
                        ),
                        22 =>
                        array(
                            'name' => 'Paneer Red Chilly Peppers ',
                            'price' => '305',
                            'desc' => NULL,
                        ),
                        23 =>
                        array(
                            'name' => 'Paneer Lemon Butter ',
                            'price' => '305',
                            'desc' => NULL,
                        ),
                        24 =>
                        array(
                            'name' => 'Malaysian Paneer ',
                            'price' => '305',
                            'desc' => NULL,
                        ),
                        25 =>
                        array(
                            'name' => 'Paneer Patiya ',
                            'price' => '305',
                            'desc' => NULL,
                        ),
                        26 =>
                        array(
                            'name' => 'Moon Man Paneer	 ',
                            'price' => '305',
                            'desc' => NULL,
                        ),
                        27 =>
                        array(
                            'name' => 'Paneer BBQ	 ',
                            'price' => '305',
                            'desc' => NULL,
                        ),
                        28 =>
                        array(
                            'name' => 'Belgiun Cottage Cheese ',
                            'price' => '305',
                            'desc' => NULL,
                        ),
                        29 =>
                        array(
                            'name' => 'Cheesy Cigars ',
                            'price' => '325',
                            'desc' => NULL,
                        ),
                        30 =>
                        array(
                            'name' => 'Sichuan Cheesy Goldies ',
                            'price' => '335',
                            'desc' => NULL,
                        ),
                    ),
                ),
                array(
                    'name' => 'Tandoori Starters',
                    'image' => 'https://firebasestorage.googleapis.com/v0/b/navjivan-group.appspot.com/o/tandooristarter.jpg?alt=media&token=4894a015-97ca-4ad8-9262-fc26f12dc190',
                    'data' => array(
                        0 =>
                        array(
                            'name' => 'Malai Broccoli ',
                            'price' => '265',
                            'desc' => NULL,
                        ),
                        1 =>
                        array(
                            'name' => 'Chatpati Broccoli	 ',
                            'price' => '265',
                            'desc' => NULL,
                        ),
                        2 =>
                        array(
                            'name' => 'Nizami Aloo ',
                            'price' => '265',
                            'desc' => NULL,
                        ),
                        3 =>
                        array(
                            'name' => 'Banarasi Subziyon Ki Seekh ',
                            'price' => '265',
                            'desc' => NULL,
                        ),
                        4 =>
                        array(
                            'name' => 'Dehati Seekh ',
                            'price' => '265',
                            'desc' => NULL,
                        ),
                        5 =>
                        array(
                            'name' => 'Chhole ki Sheekh ',
                            'price' => '275',
                            'desc' => NULL,
                        ),
                        6 =>
                        array(
                            'name' => 'Kaju Paneer Ki Seekh ',
                            'price' => '280',
                            'desc' => NULL,
                        ),
                        7 =>
                        array(
                            'name' => 'Tandoori Manchurian ',
                            'price' => '280',
                            'desc' => NULL,
                        ),
                        8 =>
                        array(
                            'name' => 'Tikka Paneer ',
                            'price' => '280',
                            'desc' => NULL,
                        ),
                        9 =>
                        array(
                            'name' => 'Angara  Paneer Tikka ',
                            'price' => '280',
                            'desc' => NULL,
                        ),
                        10 =>
                        array(
                            'name' => 'Banjara Paneer Tikka ',
                            'price' => '280',
                            'desc' => NULL,
                        ),
                        11 =>
                        array(
                            'name' => 'Kadai Paneer Tikka ',
                            'price' => '280',
                            'desc' => NULL,
                        ),
                        12 =>
                        array(
                            'name' => 'Bhuna Paneer Tikka ',
                            'price' => '280',
                            'desc' => NULL,
                        ),
                        13 =>
                        array(
                            'name' => 'Hyderabadi Paneer Tikka ',
                            'price' => '280',
                            'desc' => NULL,
                        ),
                        14 =>
                        array(
                            'name' => 'Shahjahani Paneer Tikka ',
                            'price' => '280',
                            'desc' => NULL,
                        ),
                        15 =>
                        array(
                            'name' => 'Chilli Malai Paneer Tikka ',
                            'price' => '290',
                            'desc' => NULL,
                        ),
                        16 =>
                        array(
                            'name' => 'Five Spice Paneer Tikka ',
                            'price' => '290',
                            'desc' => NULL,
                        ),
                        17 =>
                        array(
                            'name' => 'Lassunia Paneer Tikka ',
                            'price' => '290',
                            'desc' => NULL,
                        ),
                        18 =>
                        array(
                            'name' => 'Saagwala Paneer Tikka ',
                            'price' => '290',
                            'desc' => NULL,
                        ),
                        19 =>
                        array(
                            'name' => 'Mushroom Tikka ',
                            'price' => '300',
                            'desc' => NULL,
                        ),
                        20 =>
                        array(
                            'name' => 'Multani Mushroom ',
                            'price' => '300',
                            'desc' => NULL,
                        ),
                        21 =>
                        array(
                            'name' => 'Double Roll Paneer Tikka ',
                            'price' => '300',
                            'desc' => NULL,
                        ),
                        22 =>
                        array(
                            'name' => 'Cheesy Paneer Tikka ',
                            'price' => '305',
                            'desc' => NULL,
                        ),
                        23 =>
                        array(
                            'name' => 'Hydrabadi Cheesy Tikka ',
                            'price' => '305',
                            'desc' => NULL,
                        ),
                        24 =>
                        array(
                            'name' => 'Stuffed Paneer Tikka ',
                            'price' => '315',
                            'desc' => NULL,
                        ),
                        25 =>
                        array(
                            'name' => 'Tandoori Platter ',
                            'price' => '550',
                            'desc' => NULL,
                        ),
                    ),
                ),
                array(
                    'name' => 'Continental Starters ',
                    'image' => 'https://firebasestorage.googleapis.com/v0/b/navjivan-group.appspot.com/o/lakeviewnew%2FContinental%20Starter.jpg?alt=media&token=6ef353ce-72a8-4f05-ab04-9fd2adbf02d5',
                    'data' => array(
                        0 =>
                        array(
                            'name' => 'French Fries / Banana Fries ',
                            'price' => '165',
                            'desc' => 'Deep fried finger cuts of potatoes / Banana ',
                        ),
                        1 =>
                        array(
                            'name' => 'Cheese Dip French Fries ',
                            'price' => '195',
                            'desc' => 'Finger Cut potato with Cheese Dip ',
                        ),
                        2 =>
                        array(
                            'name' => 'Farmer’s Bread with Herbs &amp; Cheese ',
                            'price' => '235',
                            'desc' => 'French bread with garlic, butter, cheese &amp; herbs ',
                        ),
                        3 =>
                        array(
                            'name' => 'Bruschetta ',
                            'price' => '250',
                            'desc' => 'Sliced garlic bread topped with bell peppers, herbs, cheese &amp; baked ',
                        ),
                        4 =>
                        array(
                            'name' => 'Tuscany Bread ',
                            'price' => '260',
                            'desc' => 'French loaf bread topped with Basil Pesto, Corn, Bell peppers, Tomato, Chopped Spinach, Cheese &amp; baked ',
                        ),
                        5 =>
                        array(
                            'name' => 'Terrific Nachos ',
                            'price' => '245',
                            'desc' => 'Crispy corn tortilla chips topped with cheese sauce &amp; refried beans ',
                        ),
                        6 =>
                        array(
                            'name' => 'Over Loaded Nachos ',
                            'price' => '325',
                            'desc' => 'Crispy corn tortilla chips loaded with salsa sauce, cheese sauce &amp; refried beans ',
                        ),
                        7 =>
                        array(
                            'name' => 'Exotic Tacos	 ',
                            'price' => '265',
                            'desc' => 'Crispy tortilla Shells filled with lettuce, refried beans &amp; Julien cut veggies &amp; cheese ',
                        ),
                        8 =>
                        array(
                            'name' => 'Quesadilla  ',
                            'price' => '315',
                            'desc' => 'Soft tortillas with a filling of beans, corn &amp; cheese, grilled and served crispy with salsa dip',
                        ),
                        9 =>
                        array(
                            'name' => 'Chimichanga ',
                            'price' => '315',
                            'desc' => 'Pocket made of soft tortillas with a filling of beans, corn &amp; cheese, topped with classic tomato sauce &amp; mozzarella and baked.',
                        ),
                        10 =>
                        array(
                            'name' => 'Italian Cheesy wonton ',
                            'price' => '325',
                            'desc' => 'Wonton pouch stuffed with cheese, corn, bell pepper and deep fried served with cocktail dip',
                        ),
                        11 =>
                        array(
                            'name' => 'Vegetable Poppers ',
                            'price' => '305',
                            'desc' => 'Spicy vegetable Bullets served with Cocktail Sauce ',
                        ),
                        12 =>
                        array(
                            'name' => 'Cheese Corn Balls ',
                            'price' => '305',
                            'desc' => 'cheeeesy, balls stuffed with cheese, corn &amp; herbs ',
                        ),
                        13 =>
                        array(
                            'name' => 'Spinach Cheese Rolls ',
                            'price' => '305',
                            'desc' => 'Rolls Stuffed with Spinach, Corn, Cheese and deep fried served with cocktail Sauce ',
                        ),
                        14 =>
                        array(
                            'name' => 'Sauté Vegetable ',
                            'price' => '285',
                            'desc' => 'Exotic vegetables sauted with olive oil with choice of flavour – simple oregano, garlic butter or spicy peri peri.',
                        ),
                        15 =>
                        array(
                            'name' => 'Paneer Titbit ',
                            'price' => '305',
                            'desc' => 'Triangle shaped paneer, deep fried and tossed with tomato concasse. ',
                        ),
                        16 =>
                        array(
                            'name' => 'Garlic Butter Pan Fried Mushroom ',
                            'price' => '305',
                            'desc' => 'Dice Mushroom, Bell peppers and Onion tossed in Garlic with Butter ',
                        ),
                        17 =>
                        array(
                            'name' => 'Paneer Steak ',
                            'price' => '325',
                            'desc' => 'Marinated Paneer Steak Served with butter tossed Speghetti',
                        ),
                        18 =>
                        array(
                            'name' => 'Paneer Parmegiano ',
                            'price' => '345',
                            'desc' => 'Paneer stuffed with onion, cheese &amp; herbs, coated with Penko, grilled on tawa, baked with Mozrella',
                        ),
                    ),
                ),
                array(
                    'name' => 'Fondue',
                    'image' => 'https://firebasestorage.googleapis.com/v0/b/navjivan-group.appspot.com/o/pavbhajifondue.jpg?alt=media&token=6289dbcc-80de-429b-9f1d-5a2683cfe1cc',
                    'data' => array(
                        0 =>
                        array(
                            'name' => 'Pav Bhaji Fondue ',
                            'price' => '375',
                            'desc' => 'Fondue made of indian pav bhaji laced with butter &amp; cheese, served with masala pav &amp; potato wedges',
                        ),
                        1 =>
                        array(
                            'name' => 'Traditional Cheese Fondue ',
                            'price' => '455',
                            'desc' => 'Fondue made of three cheese, accompanied with Bread Croutons, French fries, Vegetables, Potato Nudgets',
                        ),
                    ),
                ),
                array(
                    'name' => 'Make Your Own Pasta!',
                    'image' => 'https://firebasestorage.googleapis.com/v0/b/navjivan-group.appspot.com/o/pasta.jpg?alt=media&token=955442e7-9f85-4de8-8048-cedae1f461d1',
                    'data' => array(
                        0 =>
                        array(
                            'name' => 'Al Alfredo Sauce ',
                            'price' => '325',
                            'desc' => NULL,
                        ),
                        1 =>
                        array(
                            'name' => 'Al Arrabiata Sauce ',
                            'price' => '325',
                            'desc' => 'Spicy tomato sauce flavored with Basil ',
                        ),
                        2 =>
                        array(
                            'name' => 'Del Barone Sauce ',
                            'price' => '325',
                            'desc' => 'A classic sauce with béchamel, tomato sauce, red chilly &amp; parmesan cheese ',
                        ),
                        3 =>
                        array(
                            'name' => 'Aglio Olio ',
                            'price' => '325',
                            'desc' => 'Pasta tossed with garlic, chilly flakes &amp; sun-dried tomato in olive oil, topped with parmesan cheese &amp; parsley',
                        ),
                        4 =>
                        array(
                            'name' => 'Al Formaggio ',
                            'price' => '340',
                            'desc' => 'White sauce with Mustard Sauce, Cheese Sauce &amp; Vegetables.',
                        ),
                        5 =>
                        array(
                            'name' => 'Basil Pesto ',
                            'price' => '340',
                            'desc' => 'Classic pesto sauce made with fresh basil, walnuts &amp; garlic',
                        ),
                        6 =>
                        array(
                            'name' => 'Basil Formaggio ',
                            'price' => '345',
                            'desc' => 'Alfredo Sauce, Basil Pesto &amp; Formaggio sauce with Vegetables',
                        ),
                    ),
                ),
                array(
                    'name' => 'Pizzas',
                    'image' => 'https://firebasestorage.googleapis.com/v0/b/navjivan-group.appspot.com/o/pizza.jpg?alt=media&token=d3e4ba28-c0f6-4666-b985-b8a91f2da743',
                    'data' => array(
                        0 =>
                        array(
                            'name' => 'Classic Margarita ',
                            'price' => '300',
                            'desc' => 'Tomato , cheese, basil ',
                        ),
                        1 =>
                        array(
                            'name' => 'Vegetable Verdure	 ',
                            'price' => '315',
                            'desc' => 'Grilled vegetables, mozzarella cheese ',
                        ),
                        2 =>
                        array(
                            'name' => 'Smokey Fusion ',
                            'price' => '315',
                            'desc' => 'Bbq paneer, onion, capsicum, cheese ',
                        ),
                        3 =>
                        array(
                            'name' => 'Palak, Corn  &amp; Cheese ',
                            'price' => '315',
                            'desc' => 'Palak sauce with Corn , cheese balls ',
                        ),
                        4 =>
                        array(
                            'name' => 'Pepper Paneer ',
                            'price' => '325',
                            'desc' => 'Paneer, Bell pepper, Spicy tomato sauce, Pizza Seasoning ',
                        ),
                        5 =>
                        array(
                            'name' => 'Fusion Indiana ',
                            'price' => '325',
                            'desc' => 'Pasta, BBQ Paneer, Manchurian ',
                        ),
                        6 =>
                        array(
                            'name' => 'Paradise ',
                            'price' => '325',
                            'desc' => 'Broccoli, Corn, Olive, Mushroom ',
                        ),
                        7 =>
                        array(
                            'name' => 'Pizza Napoli ',
                            'price' => '325',
                            'desc' => 'Fresh tomato sauce, mozzarella, onion, capsicum, sun-dried tomato &amp; jalapeno marinated in chilly &amp; olive oil',
                        ),
                        8 =>
                        array(
                            'name' => 'Mexicana ',
                            'price' => '325',
                            'desc' => 'Spicy tomato sauce, capsicum, refried beans, onion, mozzarella ',
                        ),
                        9 =>
                        array(
                            'name' => 'Traffic Jam ',
                            'price' => '355',
                            'desc' => 'Onion, Capsicum, Tomato, Corn, Baby Corn, Bell Pepper, Jalapeno, Olives Pizza sauce, mozzarella',
                        ),
                    ),
                ),
                array(
                    'name' => 'Indian Sizzlers',
                    'image' => 'https://firebasestorage.googleapis.com/v0/b/navjivan-group.appspot.com/o/chinesesizler.jpg?alt=media&token=6b5c39e7-d815-41be-9500-90f03f17a9a2',
                    'data' => array(
                        0 =>
                        array(
                            'name' => 'Pahadi Paneer Tikka ',
                            'price' => '485',
                            'desc' => 'Hyderabadi tikka served with green peas rice, vegetables &amp; fries,topped with indian curry',
                        ),
                        1 =>
                        array(
                            'name' => 'Five Spice ',
                            'price' => '485',
                            'desc' => 'Broccoli, baby corn, bell peppers, zucchini &amp; soya chunks tossed with indian spices, served on a bed of green peas rice with fries, a bit spicy',
                        ),
                        2 =>
                        array(
                            'name' => 'Chelo Kebab ',
                            'price' => '505',
                            'desc' => 'Assorted veggies, Saffron rice topped with Roasted Onion gravy served with Kebab &amp; fries',
                        ),
                        3 =>
                        array(
                            'name' => 'Sizzling Taj	 ',
                            'price' => '505',
                            'desc' => 'Tandoori Assorted Kebeb on Bed of Green peas rice top with Makhani Gravy with fries &amp; veggies ',
                        ),
                    ),
                ),



                array(
                    'name' => 'Oriental Sizzlers',
                    'image' => 'https://firebasestorage.googleapis.com/v0/b/navjivan-group.appspot.com/o/oriental_sizzler-min.jpg?alt=media&token=3d221cbb-a4b1-4db1-b4c1-81855593bc30',
                    'data' => array(
                        0 =>
                        array(
                            'name' => 'Vegetable Garlic Balls ',
                            'price' => '455',
                            'desc' => 'Vegetable balls tossed in spicy sichuan sauce served on bed of fried rice with garlic sauce, veggies &amp; fries',
                        ),
                        1 =>
                        array(
                            'name' => 'Chow Chow	 ',
                            'price' => '475',
                            'desc' => 'Baby Potato &amp; Manchurian balls tossed in spicy sichuan sauce served on bed of Noodles &amp; Fried rice with garlic sauce, veggies &amp; fries',
                        ),
                        2 =>
                        array(
                            'name' => 'Bar Be Que ',
                            'price' => '475',
                            'desc' => 'Cubes of Paneer &amp; vegetable tossed in Bar Be Que Sauce served on bed of fried rice with garlic sauce, veggies &amp; fries',
                        ),
                        3 =>
                        array(
                            'name' => 'Paneer Chilly ',
                            'price' => '485',
                            'desc' => 'Paneer chilly served on a bed of fried rice with assorted vegetables, fries &amp; topped with sauce',
                        ),
                    ),
                ),

                array(
                    'name' => 'Continental Sizzlers',
                    'image' => 'https://firebasestorage.googleapis.com/v0/b/navjivan-group.appspot.com/o/sizler%20(2).jpg?alt=media&token=e753fc24-3fa9-4653-bd7c-d7d9a5989731',
                    'data' => array(
                        0 =>
                        array(
                            'name' => 'Exotica ',
                            'price' => '425',
                            'desc' => 'Tossed exotic vegetables with choice of seasoning – pepper, cilantro, sweet chilly, Bbq, peri peri. Choose any one seasoning.',
                        ),
                        1 =>
                        array(
                            'name' => 'Corn Cheese Cutlet Sizzler ',
                            'price' => '455',
                            'desc' => 'Corn cheese cutlet on a bed of fried rice with assorted vegetables, topped with pepper &amp; italian sauce, mashed potato &amp; cheese',
                        ),
                        2 =>
                        array(
                            'name' => 'Mexican Sheriff ',
                            'price' => '485',
                            'desc' => 'Baked beans, kidney beans, corn &amp; jalapeno in spicy tomato salsa served on mexican rice with assorted veggies, topped with tortilla chips',
                        ),
                        3 =>
                        array(
                            'name' => 'Pasta N Pasta ',
                            'price' => '490',
                            'desc' => 'Two types of pasta tossed in two different sauces, served with mashed potato &amp; garlic bread',
                        ),
                        4 =>
                        array(
                            'name' => 'Vegetable Steak ',
                            'price' => '495',
                            'desc' => 'Sauted vegetable &amp; Aromatice Rice Topped with Spicy Tomato Pican Sauce, garlic Sauce, Pepper Sauce served with Veg Cutlet',
                        ),
                        5 =>
                        array(
                            'name' => 'Peri Peri Square  ',
                            'price' => '500',
                            'desc' => 'Cubes of cottage cheese &amp; bell peppers tossed in a spicy peri peri sauce served on a bed of fried rice with assorted veggies &amp; fries',
                        ),
                        6 =>
                        array(
                            'name' => 'Paneer Satellite ',
                            'price' => '495',
                            'desc' => 'Paneer, babycorn &amp; mushroom on bed of fried rice topped with pepper &amp; italian sauce, cheese, cutlet &amp; fries',
                        ),
                        7 =>
                        array(
                            'name' => 'Jamaican Jerk ',
                            'price' => '535',
                            'desc' => 'Caribbean flovour grilled Cottage cheese, bell pepper &amp; Spices on bed of Aromatic rice, grilled pineapple topped with Jerk Sauce',
                        ),
                    ),
                ),

                array(
                    'name' => 'Indian Main Course',
                    'image' => 'https://firebasestorage.googleapis.com/v0/b/navjivan-group.appspot.com/o/maincourse.jpg?alt=media&token=ef31b4fe-6629-4f4b-9613-e1cb5864b49d',
                    'data' => array(
                        0 =>
                        array(
                            'name' => 'Dal Fry ',
                            'price' => '195',
                            'desc' => NULL,
                        ),
                        1 =>
                        array(
                            'name' => 'Dal Tadkewali ',
                            'price' => '205',
                            'desc' => NULL,
                        ),
                        2 =>
                        array(
                            'name' => 'Dal Makhani ',
                            'price' => '245',
                            'desc' => NULL,
                        ),
                        3 =>
                        array(
                            'name' => 'Pakodewali Dahi Kadi ',
                            'price' => '245',
                            'desc' => NULL,
                        ),
                        4 =>
                        array(
                            'name' => 'Chana Masala ',
                            'price' => '255',
                            'desc' => 'Kabuli Chana Cooked in Red Gravy, Spicy ',
                        ),
                        5 =>
                        array(
                            'name' => 'Kadai Chhole ',
                            'price' => '265',
                            'desc' => 'Kabuli Chana Cooked in Spicy Kadai Masala in Red Gravy ',
                        ),
                        6 =>
                        array(
                            'name' => 'Jeera Aloo ',
                            'price' => '255',
                            'desc' => 'Aloo cooked with Jeera &amp; Coriander ',
                        ),
                        7 =>
                        array(
                            'name' => 'Lasooni Aloo Bhindi ',
                            'price' => '255',
                            'desc' => 'Aloo, Bhindi cooked with Lasooni Thick Brown Gravy, Semi Dry ',
                        ),
                        8 =>
                        array(
                            'name' => 'Dum Aloo Kashmiri ',
                            'price' => '255',
                            'desc' => 'Aloo cooked in Spicy Kashmiri Red Gravy',
                        ),
                        9 =>
                        array(
                            'name' => 'Aloo Chutney wala ',
                            'price' => '260',
                            'desc' => 'Aloo cookes in Chatpata Palak Gravy ',
                        ),
                        10 =>
                        array(
                            'name' => 'Bhindi Masala ',
                            'price' => '255',
                            'desc' => 'Bhindi Cooked in Thick Red Gravy, Semi Dry',
                        ),
                        11 =>
                        array(
                            'name' => 'Palak Lasooni ',
                            'price' => '275',
                            'desc' => 'Palak Cooked with Garlic, Served with Garlic Tadka',
                        ),
                        12 =>
                        array(
                            'name' => 'Subzi Bemisaal ',
                            'price' => '275',
                            'desc' => 'Mix Veg in Mild Yellow Gravy',
                        ),
                        13 =>
                        array(
                            'name' => 'Subzi Chilly Milly ',
                            'price' => '275',
                            'desc' => 'Spicy Vegetable Pakoda Served with Spicy Red Gravy',
                        ),
                        14 =>
                        array(
                            'name' => 'Subzi Desi Tawa ',
                            'price' => '275',
                            'desc' => 'Mix Veg cooked in yellow Tawa Gravy, Medium Spicy',
                        ),
                        15 =>
                        array(
                            'name' => 'Subzi Hara Dhaniya ',
                            'price' => '275',
                            'desc' => 'Mix veg cooked in Hara Dhaniya in Lite Green Gravy, Medium Soicy',
                        ),
                        16 =>
                        array(
                            'name' => 'Methi Mutter Malai ',
                            'price' => '280',
                            'desc' => 'Methi Leaves &amp; Mutter Cooked in Cashew Gravy, mild sweet ',
                        ),
                        17 =>
                        array(
                            'name' => 'Subzi Amaravati ',
                            'price' => '280',
                            'desc' => 'Mix Veg on Red Gravy with Curry Patta &amp; Rai Tadka, Spicy ',
                        ),
                        18 =>
                        array(
                            'name' => 'Subzi Dehati Masala ',
                            'price' => '280',
                            'desc' => 'Mix Veg with Chopped Palak &amp; Methi leaves, spicy green Gravy, Semi Dry ',
                        ),
                        19 =>
                        array(
                            'name' => 'Subzi Diwani Handi ',
                            'price' => '280',
                            'desc' => 'Mutter, Corn, Mushroom Cooked in Palak Gravy, medium spicy ',
                        ),
                        20 =>
                        array(
                            'name' => 'Subzi Kadai ',
                            'price' => '280',
                            'desc' => 'Mix Veg cooked in Spicy Kadai red Gravy',
                        ),
                        21 =>
                        array(
                            'name' => 'Subzi Kheema Kolhapuri	 ',
                            'price' => '280',
                            'desc' => 'Subzi Kheema Kolhapuri	',
                        ),
                        22 =>
                        array(
                            'name' => 'Subzi Makhani ',
                            'price' => '280',
                            'desc' => 'Mix veg Cooked in Tomato &amp; Cashew Gravy, Mild Sweet ',
                        ),
                        23 =>
                        array(
                            'name' => 'Subzi Navratna Korma ',
                            'price' => '280',
                            'desc' => 'Mix Veg, Fruit &amp; Dry Fruit cooked in Cashew Gravy, Sweet ',
                        ),
                        24 =>
                        array(
                            'name' => 'Subzi Chop Masala ',
                            'price' => '280',
                            'desc' => 'Finally Chopped Vegetable cooked in Yellow Gravy, Mild taste ',
                        ),
                        25 =>
                        array(
                            'name' => 'Subzi Hariyali ',
                            'price' => '280',
                            'desc' => 'Mix Veg &amp; Cube of Paneer, cooked in Green Gravy, medium spicy',
                        ),
                        26 =>
                        array(
                            'name' => 'Subzi Rogani ',
                            'price' => '280',
                            'desc' => 'Mix Veg Cooked in Spicy Rogani Gravy',
                        ),
                        27 =>
                        array(
                            'name' => 'Subzi Tawa Lajawab ',
                            'price' => '280',
                            'desc' => 'Subzi Tawa Lajawab',
                        ),
                        28 =>
                        array(
                            'name' => 'Subzi Lucknowi ',
                            'price' => '280',
                            'desc' => 'Onion, Capsicum, Baby corn, vegetable cooked in Spicy red Lucknowi Gravy',
                        ),
                        29 =>
                        array(
                            'name' => 'Subzi Lahori Kofta ',
                            'price' => '280',
                            'desc' => 'Veg Kofta Cooked in Palak Gravy ',
                        ),
                        30 =>
                        array(
                            'name' => 'Cheese Kofta ',
                            'price' => '295',
                            'desc' => 'Cheese kofta served with Yellow Gravy, Medium Spicy ',
                        ),
                        31 =>
                        array(
                            'name' => 'Saagwala Cheese Kofta ',
                            'price' => '295',
                            'desc' => 'Cheese Kofta served with Mild Palak Gravy ',
                        ),
                        32 =>
                        array(
                            'name' => 'Tirangi Mirch Mushroom	 ',
                            'price' => '295',
                            'desc' => 'Mushroom, Capsicum, Bell Pepper cooked in Spicy Red Gravy  ',
                        ),
                        33 =>
                        array(
                            'name' => 'Dhingri Daniya Korma ',
                            'price' => '295',
                            'desc' => 'Mushroom cooked in Coriander Flavour green Gravy  ',
                        ),
                        34 =>
                        array(
                            'name' => 'Palak Paneer ',
                            'price' => '290',
                            'desc' => 'Paneer cooked in palak Gravy ',
                        ),
                        35 =>
                        array(
                            'name' => 'Paneer Makhani ',
                            'price' => '290',
                            'desc' => 'Paneer Cube cooked in Tomato &amp; Cashew Gravy, Mild Sweet ',
                        ),
                        36 =>
                        array(
                            'name' => 'Paneer Lajjatdar ',
                            'price' => '290',
                            'desc' => 'Finger Cut Paneer Cooked in Yellow Gravy with julienne of Capsicum, Mild',
                        ),
                        37 =>
                        array(
                            'name' => 'Paneer Patiala Shahi ',
                            'price' => '290',
                            'desc' => 'Finger Cut Paneer Cooked in Red Patiala Gravy, Medium Spicy ',
                        ),
                        38 =>
                        array(
                            'name' => 'Paneer Desi Handi ',
                            'price' => '290',
                            'desc' => 'Paneer Cube, Onion, Tomato &amp; Capsicum Cooked in red Gravy, Medium Spicy ',
                        ),
                        39 =>
                        array(
                            'name' => 'Paneer Kadai ',
                            'price' => '295',
                            'desc' => 'Paneer Cube cooked in Spicy red Kadai Gravy ',
                        ),
                        40 =>
                        array(
                            'name' => 'Amritsari Paneer Bhurji ',
                            'price' => '295',
                            'desc' => 'Traditional Paneer Bhurji, Medium Spicy ',
                        ),
                        41 =>
                        array(
                            'name' => 'Bhune Paneer Ka Saalan ',
                            'price' => '295',
                            'desc' => 'Finger Cut Paneer cooked in Brown Gravy with fried Onion, Spicy ',
                        ),
                        42 =>
                        array(
                            'name' => 'Paneer Butter Masala ',
                            'price' => '295',
                            'desc' => 'Paneer Cube cooked in yellow gravy with Butter, Mild ',
                        ),
                        43 =>
                        array(
                            'name' => 'Paneer Hyderabadi Masala ',
                            'price' => '295',
                            'desc' => 'Paneer Tikka cooked with Spicy Hydrabadi Brown Gravy ',
                        ),
                        44 =>
                        array(
                            'name' => 'Paneer Lucknawi Tawa ',
                            'price' => '295',
                            'desc' => 'Paneer Cube, Onion &amp; Capsicum cooked in Red Gravy with Lucknawi Masala, Spicy ',
                        ),
                        45 =>
                        array(
                            'name' => 'Paneer Tikka Masala ',
                            'price' => '295',
                            'desc' => 'Paneer tikka, Onion, Tomato &amp; Capsicum cooked in Spicy Red Gravy',
                        ),
                        46 =>
                        array(
                            'name' => 'Paneer Khurchan ',
                            'price' => '300',
                            'desc' => 'Shredded Paneer cooked in Red Gravy with Curry Patta &amp; Rai Tadka, Medium Spicy ',
                        ),
                        47 =>
                        array(
                            'name' => 'Paneer Lasooni ',
                            'price' => '300',
                            'desc' => 'Paneer Cube Cooked in Yellow Gravy with lot of garlic, Medium Spicy',
                        ),
                        48 =>
                        array(
                            'name' => 'Paneer Khada Masala ',
                            'price' => '300',
                            'desc' => 'Paneer Cube Cooked with Bayleaf, Butter &amp; Garlic in Yellow Gravy, Spicy ',
                        ),
                    ),
                ),

                array(
                    'name' => 'Our In – House Specials',
                    'image' => 'https://firebasestorage.googleapis.com/v0/b/navjivan-group.appspot.com/o/lakeviewnew%2FOur%20In-house%20Specialities.jpg?alt=media&token=d422e9a9-fc7b-45f0-8de6-79d85d1d281b',
                    'data' => array(
                        0 =>
                        array(
                            'name' => 'Subzi Meloni ',
                            'price' => '345',
                            'desc' => 'Exotic Vegetable &amp; Mix Veg cooked in rich Tomato &amp; cashew gravy with special desi tadka.  ',
                        ),
                        1 =>
                        array(
                            'name' => 'Rajwadi Handi ',
                            'price' => '345',
                            'desc' => 'Vegetable, Corn, Cashew cooked in Onion &amp; Tomato Gravy with Rajwadi Masala ',
                        ),
                        2 =>
                        array(
                            'name' => 'Subzion Ka Mela ',
                            'price' => '345',
                            'desc' => 'Vegetable, potato, baby corn, cherry tomato cooked in onion gravy with touch of cashew gravy. ',
                        ),
                        3 =>
                        array(
                            'name' => 'Subzi Peshawari ',
                            'price' => '345',
                            'desc' => 'Diced vegetables cooked in spicy tomato onion gravy ',
                        ),
                        4 =>
                        array(
                            'name' => 'Subzi Begum Bahar ',
                            'price' => '365',
                            'desc' => 'A combination of two on the same platter ',
                        ),
                        5 =>
                        array(
                            'name' => 'Kebab Masala ',
                            'price' => '345',
                            'desc' => 'Sheekh kebab made of mashed potato and paneer cooked with white &amp; onion gravy  ',
                        ),
                        6 =>
                        array(
                            'name' => 'Paneer Pahadi Masala ',
                            'price' => '360',
                            'desc' => 'Roasted Paneer Cooked in Special Pahadi Masala Gravy ',
                        ),
                        7 =>
                        array(
                            'name' => 'Paneer Ke Sholey	 ',
                            'price' => '370',
                            'desc' => 'Daimond cut marinated paneer cooked with tandoori masala in tomato &amp; cashew gravy ',
                        ),
                        8 =>
                        array(
                            'name' => 'Paneer Amritsari	 ',
                            'price' => '370',
                            'desc' => 'Cubes &amp; grated paneer cooked in tomato &amp; onion gravy ',
                        ),
                        9 =>
                        array(
                            'name' => 'Paneer Dum lababdar ',
                            'price' => '370',
                            'desc' => 'Grilled Paneer finger cooked in Rich Tomato and Cashew Gravy  ',
                        ),
                        10 =>
                        array(
                            'name' => 'Paneer Makhmali ',
                            'price' => '370',
                            'desc' => 'Small Diamond cut Paneer cooked in Smooth Rich Red Gravy  ',
                        ),
                        11 =>
                        array(
                            'name' => 'Rajwadi Paneer Tikka Masala ',
                            'price' => '370',
                            'desc' => 'Tandoori Paneer Tikka Cooked with Rajwadi Masala, Onion &amp; Tomato Gravy  ',
                        ),
                        12 =>
                        array(
                            'name' => 'Nawabi Kaju Paneer ',
                            'price' => '380',
                            'desc' => 'Fried cashew nuts &amp; Paneer cooked with spiced yellow gravy, laced with butter &amp; cream ',
                        ),
                        13 =>
                        array(
                            'name' => 'Kaju Masala ',
                            'price' => '380',
                            'desc' => 'Fried cashew nuts cooked in a tomato &amp; onion gravy with spice. ',
                        ),
                        14 =>
                        array(
                            'name' => 'Cheese Labaab ',
                            'price' => '380',
                            'desc' => 'Cubes of cheese cooked in spiced tomato gravy  ',
                        ),
                    ),
                ),

                array(
                    'name' => 'Breads',
                    'image' => 'https://firebasestorage.googleapis.com/v0/b/navjivan-group.appspot.com/o/naan.jpg?alt=media&token=fafed49a-c6a3-4c68-ae50-7d65b60574c3',
                    'data' => array(
                        0 =>
                        array(
                            'name' => 'Roti ',
                            'price' => '45',
                            'desc' => NULL,
                        ),
                        1 =>
                        array(
                            'name' => 'Kulcha ',
                            'price' => '60',
                            'desc' => NULL,
                        ),
                        2 =>
                        array(
                            'name' => 'Paratha ',
                            'price' => '60',
                            'desc' => NULL,
                        ),
                        3 =>
                        array(
                            'name' => 'Methi Roti ',
                            'price' => '65',
                            'desc' => NULL,
                        ),
                        4 =>
                        array(
                            'name' => 'Pudina Roti ',
                            'price' => '65',
                            'desc' => NULL,
                        ),
                        5 =>
                        array(
                            'name' => 'Palak Roti ',
                            'price' => '65',
                            'desc' => NULL,
                        ),
                        6 =>
                        array(
                            'name' => 'Garlic Roti ',
                            'price' => '70',
                            'desc' => NULL,
                        ),
                        7 =>
                        array(
                            'name' => 'Missi Roti ',
                            'price' => '70',
                            'desc' => NULL,
                        ),
                        8 =>
                        array(
                            'name' => 'Naan ',
                            'price' => '70',
                            'desc' => NULL,
                        ),
                        9 =>
                        array(
                            'name' => 'Rogani Naan ',
                            'price' => '80',
                            'desc' => NULL,
                        ),
                        10 =>
                        array(
                            'name' => 'Butter Roomali Roti ',
                            'price' => '85',
                            'desc' => NULL,
                        ),
                        11 =>
                        array(
                            'name' => 'Kashmiri Naan ',
                            'price' => '105',
                            'desc' => NULL,
                        ),
                        12 =>
                        array(
                            'name' => 'Garlic Naan ',
                            'price' => '105',
                            'desc' => NULL,
                        ),
                        13 =>
                        array(
                            'name' => 'Masala Kulcha ',
                            'price' => '105',
                            'desc' => NULL,
                        ),
                        14 =>
                        array(
                            'name' => 'Stuffed Paratha ',
                            'price' => '105',
                            'desc' => NULL,
                        ),
                        15 =>
                        array(
                            'name' => 'Cheese Naan ',
                            'price' => '115',
                            'desc' => NULL,
                        ),
                        16 =>
                        array(
                            'name' => 'Roti Ki Tokri ',
                            'price' => '405',
                            'desc' => NULL,
                        ),
                    ),
                ),

                array(
                    'name' => 'Rice',
                    'image' => 'https://firebasestorage.googleapis.com/v0/b/navjivan-group.appspot.com/o/rice%20(1).jpg?alt=media&token=80df306f-6e48-4d0a-a687-3301de887fd9',
                    'data' => array(
                        0 =>
                        array(
                            'name' => 'Steam Rice ',
                            'price' => '195',
                            'desc' => NULL,
                        ),
                        1 =>
                        array(
                            'name' => 'Jeera Rice ',
                            'price' => '205',
                            'desc' => NULL,
                        ),
                        2 =>
                        array(
                            'name' => 'Ghee Bhat	 ',
                            'price' => '215',
                            'desc' => NULL,
                        ),
                        3 =>
                        array(
                            'name' => 'Green Peas Rice ',
                            'price' => '215',
                            'desc' => NULL,
                        ),
                        4 =>
                        array(
                            'name' => 'Curd Rice ',
                            'price' => '215',
                            'desc' => NULL,
                        ),
                        5 =>
                        array(
                            'name' => 'Dal Khichdi ',
                            'price' => '215',
                            'desc' => NULL,
                        ),
                        6 =>
                        array(
                            'name' => 'Dal Khichdi with Garlic Tadka ',
                            'price' => '225',
                            'desc' => NULL,
                        ),
                        7 =>
                        array(
                            'name' => 'Palak Khichdi ',
                            'price' => '225',
                            'desc' => NULL,
                        ),
                        8 =>
                        array(
                            'name' => 'Subzi Masala Khichdi ',
                            'price' => '240',
                            'desc' => NULL,
                        ),
                        9 =>
                        array(
                            'name' => 'Vegetable Pulao ',
                            'price' => '225',
                            'desc' => NULL,
                        ),
                        10 =>
                        array(
                            'name' => 'Cheese Masala Pulao ',
                            'price' => '255',
                            'desc' => NULL,
                        ),
                        11 =>
                        array(
                            'name' => 'Kashmiri Pulao ',
                            'price' => '255',
                            'desc' => NULL,
                        ),
                        12 =>
                        array(
                            'name' => 'Vegetable Biryani ',
                            'price' => '285',
                            'desc' => NULL,
                        ),
                        13 =>
                        array(
                            'name' => 'Hyderabadi Biryani  ',
                            'price' => '295',
                            'desc' => NULL,
                        ),
                        14 =>
                        array(
                            'name' => 'Paneer Tikka Biryani ',
                            'price' => '325',
                            'desc' => NULL,
                        ),
                    ),
                ),

                array(
                    'name' => 'Chinese Main Course',
                    'image' => 'https://firebasestorage.googleapis.com/v0/b/navjivan-group.appspot.com/o/lakeviewnew%2Fchinese%20starter.jpg?alt=media&token=440506bc-62e1-41f1-9610-231b0983d05f',
                    'data' => array(
                        0 =>
                        array(
                            'name' => 'Vegetables in Hong Kong Sauce ',
                            'price' => '265',
                            'desc' => NULL,
                        ),
                        1 =>
                        array(
                            'name' => 'Vegetables in Sweet &amp; Sour Sauce ',
                            'price' => '265',
                            'desc' => NULL,
                        ),
                        2 =>
                        array(
                            'name' => 'Vegetables in Thai Green Curry ',
                            'price' => '265',
                            'desc' => NULL,
                        ),
                        3 =>
                        array(
                            'name' => 'Vegetables in Sichuan Pepper Sauce ',
                            'price' => '265',
                            'desc' => NULL,
                        ),
                        4 =>
                        array(
                            'name' => 'Vegetables in Hot Garlic Sauce ',
                            'price' => '265',
                            'desc' => NULL,
                        ),
                        5 =>
                        array(
                            'name' => 'Paneer in Red Thai Curry 	 ',
                            'price' => '290',
                            'desc' => NULL,
                        ),
                        6 =>
                        array(
                            'name' => 'Paneer in Hunan Sauce  ',
                            'price' => '285',
                            'desc' => NULL,
                        ),
                        7 =>
                        array(
                            'name' => 'Paneer in Hot Garlic Sauce  ',
                            'price' => '285',
                            'desc' => NULL,
                        ),
                    ),
                ),

                array(
                    'name' => 'Chinese Rice',
                    'image' => 'https://firebasestorage.googleapis.com/v0/b/navjivan-group.appspot.com/o/rice%20(1).jpg?alt=media&token=80df306f-6e48-4d0a-a687-3301de887fd9',
                    'data' => array(
                        0 =>
                        array(
                            'name' => 'Vegetable Fried Rice ',
                            'price' => '245',
                            'desc' => NULL,
                        ),
                        1 =>
                        array(
                            'name' => 'Burnt Garlic Rice ',
                            'price' => '255',
                            'desc' => NULL,
                        ),
                        2 =>
                        array(
                            'name' => 'Sichuan Rice ',
                            'price' => '265',
                            'desc' => NULL,
                        ),
                        3 =>
                        array(
                            'name' => 'Lemon Ginger Rice ',
                            'price' => '265',
                            'desc' => NULL,
                        ),
                        4 =>
                        array(
                            'name' => 'Basil Rice ',
                            'price' => '265',
                            'desc' => NULL,
                        ),
                        5 =>
                        array(
                            'name' => 'Spicy Thai Rice ',
                            'price' => '265',
                            'desc' => NULL,
                        ),
                        6 =>
                        array(
                            'name' => 'Fortune Fried Rice ',
                            'price' => '265',
                            'desc' => NULL,
                        ),
                        7 =>
                        array(
                            'name' => 'Mushroom Fried Rice ',
                            'price' => '275',
                            'desc' => NULL,
                        ),
                        8 =>
                        array(
                            'name' => 'Cilantro Rice with Garden Skillet veg ',
                            'price' => '285',
                            'desc' => NULL,
                        ),
                        9 =>
                        array(
                            'name' => 'Vegetable Fu Chi Rice ',
                            'price' => '285',
                            'desc' => NULL,
                        ),
                        10 =>
                        array(
                            'name' => 'Triple Sichuan Fried Rice ',
                            'price' => '295',
                            'desc' => NULL,
                        ),
                    ),
                ),

                array(
                    'name' => 'Noodles',
                    'image' => 'https://firebasestorage.googleapis.com/v0/b/navjivan-group.appspot.com/o/noodles.jpg?alt=media&token=2354a724-47a3-467d-98a8-4698f696f235',
                    'data' => array(
                        0 =>
                        array(
                            'name' => 'Hakka Noodles ',
                            'price' => '245',
                            'desc' => NULL,
                        ),
                        1 =>
                        array(
                            'name' => 'Chilly Garlic Noodles ',
                            'price' => '255',
                            'desc' => NULL,
                        ),
                        2 =>
                        array(
                            'name' => 'Sichuan Noodles ',
                            'price' => '265',
                            'desc' => NULL,
                        ),
                        3 =>
                        array(
                            'name' => 'Singapore Noodles ',
                            'price' => '265',
                            'desc' => NULL,
                        ),
                        4 =>
                        array(
                            'name' => 'Spicy Basil Noodles ',
                            'price' => '265',
                            'desc' => NULL,
                        ),
                        5 =>
                        array(
                            'name' => 'Manthai Noodles ',
                            'price' => '275',
                            'desc' => NULL,
                        ),
                        6 =>
                        array(
                            'name' => 'Pot Noodles ',
                            'price' => '275',
                            'desc' => NULL,
                        ),
                        7 =>
                        array(
                            'name' => 'Sizzling Noodles ',
                            'price' => '275',
                            'desc' => NULL,
                        ),
                        8 =>
                        array(
                            'name' => 'Sesami Peanut Noodles ',
                            'price' => '275',
                            'desc' => NULL,
                        ),
                        9 =>
                        array(
                            'name' => 'American Chopsuey ',
                            'price' => '285',
                            'desc' => NULL,
                        ),
                    ),
                ),

                array(
                    'name' => 'International Rice',
                    'image' => 'https://firebasestorage.googleapis.com/v0/b/navjivan-group.appspot.com/o/internationalrice.jpg?alt=media&token=06979de0-969c-4175-a094-c03e5e7c7ab1',
                    'data' => array(
                        0 =>
                        array(
                            'name' => 'Mexican Chilly Bean ',
                            'price' => '295',
                            'desc' => 'Rice tossed with Corn, Kidney Beans &amp; Veggies',
                        ),
                        1 =>
                        array(
                            'name' => 'Pimento ',
                            'price' => '295',
                            'desc' => 'Rice tossed with Bell peppers, Parmesan Cheese &amp; Parsley',
                        ),
                        2 =>
                        array(
                            'name' => 'Peppered American Rice ',
                            'price' => '295',
                            'desc' => 'Rice tossed with Beans, Corn, Bell peppers, Chilly &amp; spices',
                        ),
                        3 =>
                        array(
                            'name' => 'Indonesian Rice ',
                            'price' => '295',
                            'desc' => 'Rice tossed with Herbs, Lemon, Onions, Basil, Veggies &amp; Spices, garnished with',
                        ),
                        4 =>
                        array(
                            'name' => 'Lebanese Biryani ',
                            'price' => '385',
                            'desc' => NULL,
                        ),
                    ),
                ),

                array(
                    'name' => 'Desserts',
                    'image' => 'https://firebasestorage.googleapis.com/v0/b/navjivan-group.appspot.com/o/lakeviewnew%2FDessert.jpg?alt=media&token=72062ec3-1ecc-408f-9e75-871c7d073ae2',
                    'data' => array(
                        0 =>
                        array(
                            'name' => 'Choice of Creams ',
                            'price' => '70',
                            'desc' => 'Vanilla / Chocolate / Strawberry / Butterscotch / Kesar Pista',
                        ),
                        1 =>
                        array(
                            'name' => 'Gulab Jamun ',
                            'price' => '105',
                            'desc' => NULL,
                        ),
                        2 =>
                        array(
                            'name' => 'Gulab Jamun with Vanilla Ice Cream ',
                            'price' => '135',
                            'desc' => NULL,
                        ),
                        3 =>
                        array(
                            'name' => 'Vanilla Ice Cream with Hot Chocolate Sauce ',
                            'price' => '165',
                            'desc' => NULL,
                        ),
                        4 =>
                        array(
                            'name' => 'Hot Fudge Nut Sundae ',
                            'price' => '185',
                            'desc' => 'Vanilla &amp; Chocolate Ice Cream, Hot Chocolate Sauce &amp; Nuts',
                        ),
                        5 =>
                        array(
                            'name' => 'Sizzling Brownie ',
                            'price' => '245',
                            'desc' => 'Brownie topped with Vanilla Ice Cream &amp; Hot Chocolate Sauce',
                        ),
                        6 =>
                        array(
                            'name' => 'Eiffel Tower ',
                            'price' => '245',
                            'desc' => 'A tower made of different ice-cream with fruits crush and Chocolate nuts',
                        ),
                        7 =>
                        array(
                            'name' => 'Banana Split ',
                            'price' => '245',
                            'desc' => 'Fresh Cut Banana with three Diffrant scoop of Ice Cream topped with Caramel &amp; Crushes &amp; chocolate Nuts   ',
                        ),
                        8 =>
                        array(
                            'name' => 'Death by Chocolate ',
                            'price' => '295',
                            'desc' => 'Rich Chocolate cake topped with Chocolate Ice cream, Chocolate sauce, Choco Chips',
                        ),
                        9 =>
                        array(
                            'name' => 'Mud Pie ',
                            'price' => '295',
                            'desc' => 'Classic Hot Chocolate Pie Served with Vanilla Ice cream',
                        ),
                        10 =>
                        array(
                            'name' => 'Chocolate Fondue ',
                            'price' => '415',
                            'desc' => 'Chocolate fondue served with chocosticks, wafer biscuits, brownie &amp; Assorted seasonal fresh fruits.',
                        ),
                    ),
                ),
                array(
                    'name' => 'Cold Beverages',
                    'image' => 'https://firebasestorage.googleapis.com/v0/b/navjivan-group.appspot.com/o/lakeviewnew%2FLime_Valley.jpg?alt=media&token=6d0c5eaa-7f07-456f-8262-986b38e97f7b',
                    'data' => array(
                        0 =>
                        array(
                            'name' => 'Mineral Water ',
                            'price' => '30',
                            'desc' => NULL,
                        ),
                        1 =>
                        array(
                            'name' => 'Aerated Drinks ',
                            'price' => '50',
                            'desc' => NULL,
                        ),
                        2 =>
                        array(
                            'name' => 'Nimbu Pani / Soda ',
                            'price' => '85',
                            'desc' => NULL,
                        ),
                        3 =>
                        array(
                            'name' => 'Kokam Sherbet ',
                            'price' => '85',
                            'desc' => NULL,
                        ),
                        4 =>
                        array(
                            'name' => 'Chaas ',
                            'price' => '75',
                            'desc' => NULL,
                        ),
                        5 =>
                        array(
                            'name' => 'Masala Cola ',
                            'price' => '95',
                            'desc' => NULL,
                        ),
                        6 =>
                        array(
                            'name' => 'Salted / Sweet Lassi ',
                            'price' => '105',
                            'desc' => NULL,
                        ),
                        7 =>
                        array(
                            'name' => 'Cold Coffee with Ice Cream ',
                            'price' => '165',
                            'desc' => NULL,
                        ),
                        8 =>
                        array(
                            'name' => 'Hot Coffee ',
                            'price' => '100',
                            'desc' => NULL,
                        ),
                    ),
                ),
            );
            $hin = [
                "मॉकटेल",
                "महाद्वीपीय सूप",
                "चीनी सूप",
                "संगत",
                "भारतीय शुरुआत",
                "चीनी शुरुआत",
                "तंदूरी स्टार्टर्स",
                "महाद्वीपीय शुरुआत",
                "फोंड्यू",
                "अपना खुद का पास्ता बनाओ!",
                "पके हुए व्यंजन",
                "पिज़ा",
                "भारतीय सिज़लर",
                "ओरिएंटल सिज़लर",
                "कॉन्टिनेंटल सिज़लर",
                "भारतीय मुख्य पाठ्यक्रम",
                "हमारे इन-हाउस स्पेशल",
                "ब्रेड",
                "चावल",
                "चीनी मुख्य पाठ्यक्रम",
                "चीनी चावल",
                "नूडल्स",
                "अंतर्राष्ट्रीय चावल",
                "डेसर्ट",
                "ठंडे पेय पदार्थ"
            ];
            $guj = [
                "મોકટેલ્સ",
                "કોન્ટિનેન્ટલ સૂપ",
                "ચાઇનીઝ સૂપ",
                "સાથોસાથ",
                "ભારતીય શરૂઆત",
                "ચિની શરૂઆત",
                "તંદૂરી સ્ટાર્ટર્સ",
                "કોન્ટિનેન્ટલ સ્ટાર્ટર્સ",
                "ફોન્ડ્યુ",
                "તમારા પોતાના પાસ્તા બનાવો!",
                "બેકડ ડીશ",
                "પિઝા",
                "ભારતીય સિઝલર્સ",
                "ઓરિએન્ટલ સિઝલર્સ",
                "કોન્ટિનેંટલ સિઝલર્સ",
                "ભારતીય મુખ્ય અભ્યાસક્રમ",
                "અમારી ઇન-હાઉસ વિશેષતા",
                "બ્રેડ્સ",
                "ચોખા",
                "ચાઇનીઝ મુખ્ય કોર્સ",
                "ચાઇનીઝ ચોખા",
                "નૂડલ્સ",
                "આંતરરાષ્ટ્રીય ચોખા",
                "મીઠાઈઓ",
                "ઠંડા પીણાં"
            ];
            $newD = [];
            $lang_gu = [
                array(
                    0 =>
                    array(
                        'name' => 'લોંગ આઇલેન્ડ આઇસ્ડ ટી',
                        'desc' => 'સ્વાદની પસંદગી લીંબુ, પીચ, કાળી કિસમિસ',
                    ),
                    1 =>
                    array(
                        'name' => 'લાઈમ મોજીટો',
                        'desc' => 'મિન્ટ, લાઈમ, સ્પ્રાઈટ',
                    ),
                    2 =>
                    array(
                        'name' => 'પેશન ફ્રૂટ મોજીટો',
                        'desc' => 'પેશન ફ્રુટ સીરપ, ફુદીનો, ચૂનો, સોડા',
                    ),
                    3 =>
                    array(
                        'name' => 'નારંગી મીમોસા',
                        'desc' => 'નારંગીનો રસ, તાજો ફુદીનો, લીંબુનો રસ, સ્પ્રાઈટ',
                    ),
                    4 =>
                    array(
                        'name' => 'બ્લુ બ્રિઝર',
                        'desc' => 'બ્લુ કારાકોઆ, લીચી ક્રશ, ગ્રેનાડીન સીરપ, લાઈમ જ્યુસ, સ્પ્રાઈટ',
                    ),
                    5 =>
                    array(
                        'name' => 'તાજું થાઈ કોકોનટ',
                        'desc' => 'કોકોનટ સીરપ, તુલસીના પાન, ચૂનોનો રસ, સ્પ્રાઈટ સાથે ટોચ પર',
                    ),
                    6 =>
                    array(
                        'name' => 'પીના કોલાડા',
                        'desc' => 'પાઈનેપલ જ્યુસ, કોકોનટ મિલ્ક, આઈસ્ક્રીમ',
                    ),
                    7 =>
                    array(
                        'name' => 'ટોમ એન્ડ જેરી',
                        'desc' => 'એપલ જ્યુસ, ઓરેન્જ જ્યુસ, ગ્રેનેડાઈન સીરપ',
                    ),
                    8 =>
                    array(
                        'name' => 'સ્ટ્રોબેરી સાઇટ્રસ',
                        'desc' => 'સ્ટ્રોબેરી ક્રશ, બ્લેકબેરી સીરપ, લીંબુનો રસ, સોડા',
                    ),
                    9 =>
                    array(
                        'name' => 'માઈ થાઈ',
                        'desc' => 'પાઈનેપલ જ્યુસ, પાઈનેપલ ક્રશ અને ક્રેનબેરી જ્યુસ',
                    ),
                    10 =>
                    array(
                        'name' => 'ઇટાલિયન ક્રીમ સોડા',
                        'desc' => 'પેશન ફ્રૂટ, વોટર તરબૂચ, સ્પ્રાઈટ, સોડા, ક્રીમ',
                    ),
                    11 =>
                    array(
                        'name' => 'સ્પાર્કલિંગ મિક્સ ફ્રૂટ શગરિયા',
                        'desc' => 'મડલ્ડ ફ્રેશ ફ્રુટ, ફ્રુટ ક્રશ સોડા અને સ્પ્રાઈટ સાથે ટોચ પર છે',
                    ),
                    12 =>
                    array(
                        'name' => 'લીચી કોકોનટ મોકર',
                        'desc' => 'દૂધ, લીચી, નારિયેળનું દૂધ, આઈસ્ક્રીમ',
                    ),
                    13 =>
                    array(
                        'name' => 'ચમકતો બુલ',
                        'desc' => 'પાણી તરબૂચ, સ્ટ્રોબેરી, ક્રેનબેરી, ગ્રેનેડિન સીરપ, રેડ બુલ',
                    ),
                    14 =>
                    array(
                        'name' => 'બુલ રેડ',
                        'desc' => 'રાસ્પબેરી સીરપ, મધ, ચૂનો, રેડ બુલ',
                    ),
                ),
                array(
                    0 =>
                    array(
                        'name' => 'ટામેટાની ક્રીમ',
                        'desc' => NULL,
                    ),
                    1 =>
                    array(
                        'name' => 'મશરૂમ ક્રીમ',
                        'desc' => NULL,
                    ),
                    2 =>
                    array(
                        'name' => 'શાકભાજીની ક્રીમ',
                        'desc' => NULL,
                    ),
                    3 =>
                    array(
                        'name' => 'લસણની ક્રીમ',
                        'desc' => NULL,
                    ),
                    4 =>
                    array(
                        'name' => 'મિનેસ્ટ્રોન  ',
                        'desc' => NULL,
                    ),
                    5 =>
                    array(
                        'name' => 'મેક્સીકન ટોર્ટિલા',
                        'desc' => NULL,
                    ),
                    6 =>
                    array(
                        'name' => 'બ્રોકોલી બદામ',
                        'desc' => NULL,
                    ),
                ),
                array(
                    0 =>
                    array(
                        'name' => 'શાકભાજી સાફ',
                        'desc' => NULL,
                    ),
                    1 =>
                    array(
                        'name' => 'મીઠી મકાઈ',
                        'desc' => NULL,
                    ),
                    2 =>
                    array(
                        'name' => 'તુમ યમ',
                        'desc' => NULL,
                    ),
                    3 =>
                    array(
                        'name' => 'ગરમ અને ખાટી  ',
                        'desc' => NULL,
                    ),
                    4 =>
                    array(
                        'name' => 'લીંબુ કોથમીર	 ',
                        'desc' => NULL,
                    ),
                    5 =>
                    array(
                        'name' => 'માંચો',
                        'desc' => NULL,
                    ),
                    6 =>
                    array(
                        'name' => 'શાંઘાઈ સૂપ',
                        'desc' => NULL,
                    ),
                    7 =>
                    array(
                        'name' => 'સ્પિનચ નૂડલ્સ સૂપ',
                        'desc' => NULL,
                    ),
                    8 =>
                    array(
                        'name' => 'બર્મીઝ સૂપ',
                        'desc' => NULL,
                    ),
                    9 =>
                    array(
                        'name' => 'હવા હવાઈ સૂપ',
                        'desc' => NULL,
                    ),
                    10 =>
                    array(
                        'name' => 'ગ્રીન એશિયન સૂપ',
                        'desc' => NULL,
                    ),
                ),
                array(
                    0 =>
                    array(
                        'name' => 'શેકેલા પાપડ',
                        'desc' => NULL,
                    ),
                    1 =>
                    array(
                        'name' => 'તળેલા પાપડ',
                        'desc' => NULL,
                    ),
                    2 =>
                    array(
                        'name' => 'મસાલા પાપડ',
                        'desc' => NULL,
                    ),
                    3 =>
                    array(
                        'name' => 'ચીઝ મસાલા પાપડ',
                        'desc' => NULL,
                    ),
                    4 =>
                    array(
                        'name' => 'કરારી રૂમલી',
                        'desc' => NULL,
                    ),
                    5 =>
                    array(
                        'name' => 'લીલો સલાડ',
                        'desc' => NULL,
                    ),
                    6 =>
                    array(
                        'name' => 'મસાલા ડુંગળી',
                        'desc' => NULL,
                    ),
                    7 =>
                    array(
                        'name' => 'ફેંકી સલાડ',
                        'desc' => NULL,
                    ),
                    8 =>
                    array(
                        'name' => 'જર્મન પોટેટો સલાડ',
                        'desc' => NULL,
                    ),
                    9 =>
                    array(
                        'name' => 'બટાટા હારા',
                        'desc' => NULL,
                    ),
                    10 =>
                    array(
                        'name' => 'રશિયન સલાડ',
                        'desc' => NULL,
                    ),
                    11 =>
                    array(
                        'name' => 'કોકટેલ સલાડ',
                        'desc' => NULL,
                    ),
                    12 =>
                    array(
                        'name' => 'બૂંદી રાયતા',
                        'desc' => NULL,
                    ),
                    13 =>
                    array(
                        'name' => 'શાક રાયતા',
                        'desc' => NULL,
                    ),
                    14 =>
                    array(
                        'name' => 'પુદીના પ્યાઝ કા રાયતા',
                        'desc' => NULL,
                    ),
                    15 =>
                    array(
                        'name' => 'પાઈનેપલ રાયતા',
                        'desc' => NULL,
                    ),
                    16 =>
                    array(
                        'name' => 'ચીઝ સાથે કરારી રૂમલી',
                        'desc' => NULL,
                    ),
                ),
                array(
                    0 =>
                    array(
                        'name' => 'હરા ભારા કબાબ',
                        'desc' => NULL,
                    ),
                    1 =>
                    array(
                        'name' => 'રજવાડી રોલ',
                        'desc' => NULL,
                    ),
                    2 =>
                    array(
                        'name' => 'વેજ બુલેટ્સ',
                        'desc' => NULL,
                    ),
                ),
                array(
                    0 =>
                    array(
                        'name' => 'ચાઈનીઝ ભેલ (ઠંડી)',
                        'desc' => NULL,
                    ),
                    1 =>
                    array(
                        'name' => 'સિઝલિંગ ચિલી પોટેટો',
                        'desc' => NULL,
                    ),
                    2 =>
                    array(
                        'name' => 'શાકભાજી પીસેલા મરચાં',
                        'desc' => NULL,
                    ),
                    3 =>
                    array(
                        'name' => 'ક્રિસ્પી વેજીટેબલ્સ',
                        'desc' => NULL,
                    ),
                    4 =>
                    array(
                        'name' => 'હમ્પ્ટી ડમ્પ્ટી',
                        'desc' => NULL,
                    ),
                    5 =>
                    array(
                        'name' => 'શાકભાજી લીલા બોલ્સ',
                        'desc' => NULL,
                    ),
                    6 =>
                    array(
                        'name' => 'વેજીટેબલ લોલીપોપ',
                        'desc' => NULL,
                    ),
                    7 =>
                    array(
                        'name' => 'વેજીટેબલ મંચુરિયન ડ્રાય',
                        'desc' => NULL,
                    ),
                    8 =>
                    array(
                        'name' => 'શાકભાજી "65"',
                        'desc' => NULL,
                    ),
                    9 =>
                    array(
                        'name' => 'પેરી પેરી બોલ્સ',
                        'desc' => NULL,
                    ),
                    10 =>
                    array(
                        'name' => 'કુંગ પાઓ વેજ',
                        'desc' => NULL,
                    ),
                    11 =>
                    array(
                        'name' => 'બેબીકોર્ન બટર મરી	 ',
                        'desc' => NULL,
                    ),
                    12 =>
                    array(
                        'name' => 'મશરૂમ મસાલેદાર કોથમીર',
                        'desc' => NULL,
                    ),
                    13 =>
                    array(
                        'name' => 'ચિલી ગાર્લિક સોસમાં મશરૂમ',
                        'desc' => NULL,
                    ),
                    14 =>
                    array(
                        'name' => 'બેબીકોર્ન મશરૂમ લાલ મરચું મરી',
                        'desc' => NULL,
                    ),
                    15 =>
                    array(
                        'name' => 'મીઠું અને મરી પનીર',
                        'desc' => NULL,
                    ),
                    16 =>
                    array(
                        'name' => 'ટેન્ગી પનીર',
                        'desc' => NULL,
                    ),
                    17 =>
                    array(
                        'name' => 'બેસિલ ચિલી પનીર	 ',
                        'desc' => NULL,
                    ),
                    18 =>
                    array(
                        'name' => 'મરચું પનીર',
                        'desc' => NULL,
                    ),
                    19 =>
                    array(
                        'name' => 'શાંઘાઈ પનીર',
                        'desc' => NULL,
                    ),
                    20 =>
                    array(
                        'name' => 'હની ચિલી પનીર',
                        'desc' => NULL,
                    ),
                    21 =>
                    array(
                        'name' => 'થાઈ ચી પનીર',
                        'desc' => NULL,
                    ),
                    22 =>
                    array(
                        'name' => 'પનીર લાલ મરચાં મરી',
                        'desc' => NULL,
                    ),
                    23 =>
                    array(
                        'name' => 'પનીર લેમન બટર',
                        'desc' => NULL,
                    ),
                    24 =>
                    array(
                        'name' => 'મલેશિયન પનીર',
                        'desc' => NULL,
                    ),
                    25 =>
                    array(
                        'name' => 'પનીર પાટિયા',
                        'desc' => NULL,
                    ),
                    26 =>
                    array(
                        'name' => 'મૂન મેન પનીર	 ',
                        'desc' => NULL,
                    ),
                    27 =>
                    array(
                        'name' => 'પનીર BBQ	 ',
                        'desc' => NULL,
                    ),
                    28 =>
                    array(
                        'name' => 'બેલ્જિયન કોટેજ ચીઝ',
                        'desc' => NULL,
                    ),
                    29 =>
                    array(
                        'name' => 'ચીઝી સિગાર',
                        'desc' => NULL,
                    ),
                    30 =>
                    array(
                        'name' => 'સિચુઆન ચીઝી ગોલ્ડીઝ',
                        'desc' => NULL,
                    ),
                ),
                array(
                    0 =>
                    array(
                        'name' => 'મલાઈ બ્રોકોલી',
                        'desc' => NULL,
                    ),
                    1 =>
                    array(
                        'name' => 'ચટપટી બ્રોકોલી	 ',
                        'desc' => NULL,
                    ),
                    2 =>
                    array(
                        'name' => 'નિઝામી આલૂ',
                        'desc' => NULL,
                    ),
                    3 =>
                    array(
                        'name' => 'બનારસી સબઝિયોં કી સીખ',
                        'desc' => NULL,
                    ),
                    4 =>
                    array(
                        'name' => 'દેહતી સીખ',
                        'desc' => NULL,
                    ),
                    5 =>
                    array(
                        'name' => 'છોલે કી શેખ',
                        'desc' => NULL,
                    ),
                    6 =>
                    array(
                        'name' => 'કાજુ પનીર કી સીખ',
                        'desc' => NULL,
                    ),
                    7 =>
                    array(
                        'name' => 'તંદૂરી મંચુરિયન',
                        'desc' => NULL,
                    ),
                    8 =>
                    array(
                        'name' => 'ટિક્કા પનીર',
                        'desc' => NULL,
                    ),
                    9 =>
                    array(
                        'name' => 'અંગારા પનીર ટિક્કા',
                        'desc' => NULL,
                    ),
                    10 =>
                    array(
                        'name' => 'બંજારા પનીર ટિક્કા',
                        'desc' => NULL,
                    ),
                    11 =>
                    array(
                        'name' => 'કડાઈ પનીર ટિક્કા',
                        'desc' => NULL,
                    ),
                    12 =>
                    array(
                        'name' => 'ભુના પનીર ટિક્કા',
                        'desc' => NULL,
                    ),
                    13 =>
                    array(
                        'name' => 'હૈદરાબાદી પનીર ટિક્કા',
                        'desc' => NULL,
                    ),
                    14 =>
                    array(
                        'name' => 'શાહજહાની પનીર ટિક્કા',
                        'desc' => NULL,
                    ),
                    15 =>
                    array(
                        'name' => 'મરચાં મલાઈ પનીર ટિક્કા',
                        'desc' => NULL,
                    ),
                    16 =>
                    array(
                        'name' => 'પાંચ મસાલા પનીર ટિક્કા',
                        'desc' => NULL,
                    ),
                    17 =>
                    array(
                        'name' => 'લસુનિયા પનીર ટિક્કા',
                        'desc' => NULL,
                    ),
                    18 =>
                    array(
                        'name' => 'સાગવાલા પનીર ટિક્કા',
                        'desc' => NULL,
                    ),
                    19 =>
                    array(
                        'name' => 'મશરૂમ ટિક્કા',
                        'desc' => NULL,
                    ),
                    20 =>
                    array(
                        'name' => 'મુલતાની મશરૂમ',
                        'desc' => NULL,
                    ),
                    21 =>
                    array(
                        'name' => 'ડબલ રોલ પનીર ટિક્કા',
                        'desc' => NULL,
                    ),
                    22 =>
                    array(
                        'name' => 'ચીઝી પનીર ટિક્કા',
                        'desc' => NULL,
                    ),
                    23 =>
                    array(
                        'name' => 'હૈદરાબાદી ચીઝી ટિક્કા',
                        'desc' => NULL,
                    ),
                    24 =>
                    array(
                        'name' => 'સ્ટફ્ડ પનીર ટિક્કા',
                        'desc' => NULL,
                    ),
                    25 =>
                    array(
                        'name' => 'તંદૂરી થાળી',
                        'desc' => NULL,
                    ),
                ),
                array(
                    0 =>
                    array(
                        'name' => 'ફ્રેન્ચ ફ્રાઈસ / બનાના ફ્રાઈસ',
                        'desc' => 'બટાકા / કેળાના ડીપ તળેલા ફિંગર કટ',
                    ),
                    1 =>
                    array(
                        'name' => 'ચીઝ ડીપ ફ્રેન્ચ ફ્રાઈસ',
                        'desc' => 'ચીઝ ડીપ સાથે ફિંગર કટ બટેટા',
                    ),
                    2 =>
                    array(
                        'name' => 'જડીબુટ્ટીઓ અને ચીઝ સાથે ખેડૂતની બ્રેડ',
                        'desc' => 'લસણ, માખણ, ચીઝ અને જડીબુટ્ટીઓ સાથે ફ્રેન્ચ બ્રેડ',
                    ),
                    3 =>
                    array(
                        'name' => 'બ્રુશેટા',
                        'desc' => 'કાતરી લસણ બ્રેડ ઘંટડી મરી, જડીબુટ્ટીઓ, ચીઝ અને બેક સાથે ટોચ પર',
                    ),
                    4 =>
                    array(
                        'name' => 'ટસ્કની બ્રેડ',
                        'desc' => 'બેસિલ પેસ્ટો, મકાઈ, બેલ મરી, ટામેટા, સમારેલી સ્પિનચ, ચીઝ અને બેક સાથે ટોચની ફ્રેન્ચ રોટલી',
                    ),
                    5 =>
                    array(
                        'name' => 'જબરદસ્ત નાચોસ',
                        'desc' => 'ક્રિસ્પી કોર્ન ટોર્ટિલા ચિપ્સ ચીઝ સોસ અને રેફ્રીડ બીન્સ સાથે ટોચ પર છે',
                    ),
                    6 =>
                    array(
                        'name' => 'ઓવર લોડેડ નાચોસ',
                        'desc' => 'ક્રિસ્પી કોર્ન ટોર્ટિલા ચિપ્સ સાલસા સોસ, ચીઝ સોસ અને રેફ્રીડ બીન્સથી ભરેલી છે',
                    ),
                    7 =>
                    array(
                        'name' => 'વિચિત્ર ટેકોઝ	 ',
                        'desc' => 'લેટીસ, રેફ્રીડ બીન્સ અને જુલિયન કટ વેજીસ અને ચીઝથી ભરેલા ક્રિસ્પી ટોર્ટિલા શેલ્સ',
                    ),
                    8 =>
                    array(
                        'name' => 'ક્વેસાડિલા  ',
                        'desc' => 'કઠોળ, મકાઈ અને પનીર, શેકેલા અને સાલસા ડીપ સાથે ક્રિસ્પી પીરસવામાં આવેલ સોફ્ટ ટોર્ટિલા',
                    ),
                    9 =>
                    array(
                        'name' => 'ચિમીચાંગા',
                        'desc' => 'કઠોળ, મકાઈ અને ચીઝના ભરણ સાથે સોફ્ટ ટોર્ટિલાથી બનેલું પોકેટ, ક્લાસિક ટમેટાની ચટણી અને મોઝેરેલા સાથે ટોચ પર છે અને બેક કરવામાં આવે છે.',
                    ),
                    10 =>
                    array(
                        'name' => 'ઇટાલિયન ચીઝી વોન્ટન',
                        'desc' => 'પનીર, મકાઈ, ઘંટડી મરીથી ભરેલા વોન્ટન પાઉચ અને ઠંડા તળેલા કોકટેલ ડીપ સાથે પીરસવામાં આવે છે',
                    ),
                    11 =>
                    array(
                        'name' => 'વેજીટેબલ પોપર્સ',
                        'desc' => 'મસાલેદાર શાકભાજીની ગોળીઓ કોકટેલ સોસ સાથે પીરસવામાં આવે છે',
                    ),
                    12 =>
                    array(
                        'name' => 'ચીઝ કોર્ન બોલ્સ',
                        'desc' => 'ચીઝ, પનીર, મકાઈ અને શાકથી ભરેલા બોલ્સ',
                    ),
                    13 =>
                    array(
                        'name' => 'સ્પિનચ ચીઝ રોલ્સ',
                        'desc' => 'સ્પિનચ, મકાઈ, ચીઝ અને ડીપ ફ્રાઈડથી ભરેલા રોલ્સ કોકટેલ સોસ સાથે પીરસવામાં આવે છે',
                    ),
                    14 =>
                    array(
                        'name' => 'શાકભાજીને સાંતળો',
                        'desc' => 'સ્વાદની પસંદગી સાથે ઓલિવ તેલમાં તળેલા વિદેશી શાકભાજી - સરળ ઓરેગાનો, લસણનું માખણ અથવા મસાલેદાર પેરી પેરી.',
                    ),
                    15 =>
                    array(
                        'name' => 'પનીર Titbit',
                        'desc' => 'ત્રિકોણ આકારનું પનીર, ઊંડા તળેલું અને ટામેટાંના કંકાસ સાથે ફેંકી દેવું.',
                    ),
                    16 =>
                    array(
                        'name' => 'લસણ બટર પાન ફ્રાઇડ મશરૂમ',
                        'desc' => 'ડાઇસ મશરૂમ, બેલ મરી અને ડુંગળી લસણમાં માખણ સાથે નાખો',
                    ),
                    17 =>
                    array(
                        'name' => 'પનીર સ્ટીક',
                        'desc' => 'મેરીનેટેડ પનીર સ્ટીક માખણ ફેંકી સ્પેગેટી સાથે પીરસવામાં આવે છે',
                    ),
                    18 =>
                    array(
                        'name' => 'પનીર પરમેજીઆનો',
                        'desc' => 'ડુંગળી, પનીર અને જડીબુટ્ટીઓથી ભરેલું પનીર, પેન્કો સાથે કોટેડ, તવા પર શેકેલું, મોઝરેલા સાથે શેકેલું',
                    ),
                ),
                array(
                    0 =>
                    array(
                        'name' => 'પાવભાજી ફોન્ડ્યુ',
                        'desc' => 'માખણ અને ચીઝથી સજ્જ ભારતીય પાવ ભાજીમાંથી બનાવેલ ફોન્ડ્યુ, મસાલા પાવ અને બટાકાની ફાચર સાથે પીરસવામાં આવે છે',
                    ),
                    1 =>
                    array(
                        'name' => 'પરંપરાગત ચીઝ Fondue',
                        'desc' => 'બ્રેડ ક્રાઉટન્સ, ફ્રેન્ચ ફ્રાઈસ, વેજીટેબલ્સ, પોટેટો નજેટ્સ સાથે ત્રણ ચીઝમાંથી બનાવેલ ફોન્ડ્યુ',
                    ),
                ),
                array(
                    0 =>
                    array(
                        'name' => 'અલ આલ્ફ્રેડો સોસ',
                        'desc' => NULL,
                    ),
                    1 =>
                    array(
                        'name' => 'અલ અરેબિયાટા સોસ',
                        'desc' => 'બેસિલ સાથે સ્વાદવાળી મસાલેદાર ટમેટાની ચટણી',
                    ),
                    2 =>
                    array(
                        'name' => 'ડેલ બેરોન સોસ',
                        'desc' => 'બેચેમેલ, ટામેટાની ચટણી, લાલ મરચું અને પરમેસન ચીઝ સાથે ક્લાસિક ચટણી',
                    ),
                    3 =>
                    array(
                        'name' => 'એગ્લિઓ ઓલિયો',
                        'desc' => 'ઓલિવ તેલમાં લસણ, મરચાંના ટુકડા અને તડકામાં સૂકવેલા ટામેટાં સાથે ફેંકવામાં આવેલ પાસ્તા, પરમેસન ચીઝ અને પાર્સલી સાથે ટોચ પર',
                    ),
                    4 =>
                    array(
                        'name' => 'અલ Formaggio',
                        'desc' => 'મસ્ટર્ડ સોસ, ચીઝ સોસ અને શાકભાજી સાથે સફેદ ચટણી.',
                    ),
                    5 =>
                    array(
                        'name' => 'બેસિલ પેસ્ટો',
                        'desc' => 'તાજા તુલસી, અખરોટ અને લસણ વડે બનાવેલ ઉત્તમ પેસ્ટો સોસ',
                    ),
                    6 =>
                    array(
                        'name' => 'તુલસીનો છોડ Formaggio',
                        'desc' => 'શાકભાજી સાથે આલ્ફ્રેડો સોસ, બેસિલ પેસ્ટો અને ફોર્મેજિયો સોસ',
                    ),
                ),
                array(
                    0 =>
                    array(
                        'name' => 'ઉત્તમ નમૂનાના માર્ગારીટા',
                        'desc' => 'ટામેટા, ચીઝ, તુલસીનો છોડ',
                    ),
                    1 =>
                    array(
                        'name' => 'શાકભાજી વર્ડ્યુર	 ',
                        'desc' => 'શેકેલા શાકભાજી, મોઝેરેલા ચીઝ',
                    ),
                    2 =>
                    array(
                        'name' => 'સ્મોકી ફ્યુઝન',
                        'desc' => 'Bbq પનીર, ડુંગળી, કેપ્સીકમ, ચીઝ',
                    ),
                    3 =>
                    array(
                        'name' => 'પાલક, કોર્ન અને ચીઝ',
                        'desc' => 'મકાઈ, ચીઝ બોલ્સ સાથે પાલકની ચટણી',
                    ),
                    4 =>
                    array(
                        'name' => 'મરી પનીર',
                        'desc' => 'પનીર, બેલ મરી, મસાલેદાર ટમેટાની ચટણી, પિઝા સીઝનીંગ',
                    ),
                    5 =>
                    array(
                        'name' => 'ફ્યુઝન ઇન્ડિયાના',
                        'desc' => 'પાસ્તા, BBQ પનીર, મંચુરિયન',
                    ),
                    6 =>
                    array(
                        'name' => 'સ્વર્ગ',
                        'desc' => 'બ્રોકોલી, મકાઈ, ઓલિવ, મશરૂમ',
                    ),
                    7 =>
                    array(
                        'name' => 'પિઝા નેપોલી',
                        'desc' => 'તાજી ટમેટાની ચટણી, મોઝેરેલા, ડુંગળી, કેપ્સિકમ, તડકામાં સૂકવેલા ટામેટા અને જલાપેનોને મરચાં અને ઓલિવ તેલમાં મેરીનેટ કરેલું',
                    ),
                    8 =>
                    array(
                        'name' => 'મેક્સિકાના',
                        'desc' => 'મસાલેદાર ટમેટાની ચટણી, કેપ્સિકમ, રેફ્રીડ બીન્સ, ડુંગળી, મોઝેરેલા',
                    ),
                    9 =>
                    array(
                        'name' => 'ટ્રાફીક થવો',
                        'desc' => 'ડુંગળી, કેપ્સિકમ, ટામેટા, મકાઈ, બેબી કોર્ન, બેલ મરી, જલાપેનો, ઓલિવ પિઝા સોસ, મોઝેરેલા',
                    ),
                ),
                array(
                    0 =>
                    array(
                        'name' => 'પહાડી પનીર ટિક્કા',
                        'desc' => 'હૈદરાબાદી ટિક્કા લીલા વટાણાના ચોખા, શાકભાજી અને ફ્રાઈસ સાથે પીરસવામાં આવે છે, જે ભારતીય કરી સાથે ટોચ પર છે',
                    ),
                    1 =>
                    array(
                        'name' => 'પાંચ મસાલા',
                        'desc' => 'બ્રોકોલી, બેબી કોર્ન, ઘંટડી મરી, ઝુચિની અને સોયાના ટુકડાને ભારતીય મસાલાઓ સાથે ફેંકી દેવામાં આવે છે, ફ્રાઈસ સાથે લીલા વટાણાના ચોખાના પલંગ પર પીરસવામાં આ   વે છે, થોડી મસાલેદાર',
                    ),
                    2 =>
                    array(
                        'name' => 'ચેલો કબાબ',
                        'desc' => 'મિશ્રિત શાકભાજી, કેસર ચોખા ટોચ પર શેકેલી ડુંગળી ગ્રેવી સાથે કબાબ અને ફ્રાઈસ સાથે પીરસવામાં આવે છે',
                    ),
                    3 =>
                    array(
                        'name' => 'સિઝલિંગ તાજ	 ',
                        'desc' => 'ફ્રાઈસ અને શાકભાજી સાથે મખાની ગ્રેવી સાથે લીલા વટાણાના ચોખાના ટોપ પર તંદૂરી મિશ્રિત કેબેબ',
                    ),
                ),
                array(
                    0 =>
                    array(
                        'name' => 'શાકભાજી લસણ બોલ્સ',
                        'desc' => 'મસાલેદાર સિચુઆન સોસમાં ફેંકવામાં આવેલા વેજીટેબલ બોલ્સને લસણની ચટણી, શાકભાજી અને ફ્રાઈસ સાથે ફ્રાઈડ રાઇસના પલંગ પર પીરસવામાં આવે છે',
                    ),
                    1 =>
                    array(
                        'name' => 'ચાઉ ચાઉ	 ',
                        'desc' => 'બેબી પોટેટો અને મંચુરિયન બોલ્સને મસાલેદાર સિચુઆન સોસમાં ફેંકવામાં આવે છે જે નૂડલ્સ અને ફ્રાઇડ રાઇસના પલંગ પર લસણની ચટણી, શાકભાજી અને ફ્રાઈસ સાથે પીરસવામાં આવે છે',
                    ),
                    2 =>
                    array(
                        'name' => 'બાર Be Que',
                        'desc' => 'પનીર અને શાકભાજીના ક્યુબ્સ બાર બી ક્યુ સોસમાં ફેંકવામાં આવે છે જે લસણની ચટણી, શાકભાજી અને ફ્રાઈસ સાથે ફ્રાઈડ રાઇસના પલંગ પર પીરસવામાં આવે છે',
                    ),
                    3 =>
                    array(
                        'name' => 'પનીર મરચું',
                        'desc' => 'પનીર મરચું ફ્રાઈડ રાઇસના પલંગ પર વિવિધ શાકભાજી, ફ્રાઈસ અને ચટણી સાથે પીરસવામાં આવે છે',
                    ),
                ),
                array(
                    0 =>
                    array(
                        'name' => 'એક્ઝોટિકા',
                        'desc' => 'મસાલાની પસંદગી સાથે ફેંકેલા વિદેશી શાકભાજી - મરી, કોથમીર, મીઠી મરચું, Bbq, પેરી પેરી. ',
                    ),
                    1 =>
                    array(
                        'name' => 'કોર્ન ચીઝ કટલેટ સિઝલર',
                        'desc' => 'વિવિધ શાકભાજી સાથે તળેલા ચોખાના પલંગ પર કોર્ન ચીઝ કટલેટ, મરી અને ઇટાલિયન ચટણી, છૂંદેલા બટેટા અને ચીઝ સાથે ટોચ પર',
                    ),
                    2 =>
                    array(
                        'name' => 'મેક્સીકન શેરિફ',
                        'desc' => 'મસાલેદાર ટામેટાં સાલસામાં બેકડ બીન્સ, રાજમા, મકાઈ અને જલાપેનો વિવિધ શાકભાજી સાથે મેક્સીકન ચોખા પર પીરસવામાં આવે છે, જેમાં ટોચ પર ટોર્ટિલા ચિપ્સ છે',
                    ),
                    3 =>
                    array(
                        'name' => 'પાસ્તા એન પાસ્તા',
                        'desc' => 'બે પ્રકારના પાસ્તા બે અલગ અલગ ચટણીઓમાં ફેંકવામાં આવે છે, છૂંદેલા બટેટા અને લસણની બ્રેડ સાથે પીરસવામાં આવે છે',
                    ),
                    4 =>
                    array(
                        'name' => 'વેજીટેબલ સ્ટીક',
                        'desc' => 'મસાલેદાર ટામેટાં પિકન સોસ, લસણની ચટણી, મરીની ચટણી સાથે તળેલા શાકભાજી અને સુગંધિત ભાત વેજ કટલેટ સાથે સર્વ કરવામાં આવે છે',
                    ),
                    5 =>
                    array(
                        'name' => 'પેરી પેરી સ્ક્વેર  ',
                        'desc' => 'મસાલેદાર પેરી પેરી સોસમાં કોટેજ ચીઝ અને ઘંટડી મરીના ક્યુબ્સને વિવિધ શાકભાજી અને ફ્રાઈસ સાથે તળેલા ચોખાના પલંગ પર પીરસવામાં આવે છે',
                    ),
                    6 =>
                    array(
                        'name' => 'પનીર સેટેલાઇટ',
                        'desc' => 'તળેલા ચોખાના પલંગ પર પનીર, બેબીકોર્ન અને મશરૂમ મરી અને ઇટાલિયન ચટણી, ચીઝ, કટલેટ અને ફ્રાઈસ સાથે ટોચ પર',
                    ),
                    7 =>
                    array(
                        'name' => 'જમૈકન આંચકો',
                        'desc' => 'સુગંધિત ચોખાના પલંગ પર કેરેબિયન ફ્લાવર ગ્રિલ્ડ કોટેજ ચીઝ, ઘંટડી મરી અને મસાલા, જર્ક સોસ સાથે ટોચ પર શેકેલા અનેનાસ',
                    ),
                ),
                array(
                    0 =>
                    array(
                        'name' => 'દાળ ફ્રાય',
                        'desc' => NULL,
                    ),
                    1 =>
                    array(
                        'name' => 'દાલ તડકેવાલી',
                        'desc' => NULL,
                    ),
                    2 =>
                    array(
                        'name' => 'દાલ મખાણી',
                        'desc' => NULL,
                    ),
                    3 =>
                    array(
                        'name' => 'પકોડેવાળી દહી કડી',
                        'desc' => NULL,
                    ),
                    4 =>
                    array(
                        'name' => 'ચણા મસાલા',
                        'desc' => 'કાબુલી ચણા રેડ ગ્રેવીમાં રાંધેલા, મસાલેદાર',
                    ),
                    5 =>
                    array(
                        'name' => 'કડાઈ છોલે',
                        'desc' => 'કાબુલી ચણા રેડ ગ્રેવીમાં મસાલેદાર કડાઈ મસાલામાં રાંધવામાં આવે છે',
                    ),
                    6 =>
                    array(
                        'name' => 'જીરા આલૂ',
                        'desc' => 'જીરા અને કોથમીર સાથે રાંધેલ આલુ',
                    ),
                    7 =>
                    array(
                        'name' => 'લસૂની આલૂ ભીંડી',
                        'desc' => 'આલુ, ભીંડી લસૂની થીક બ્રાઉન ગ્રેવી, સેમી ડ્રાય સાથે રાંધવામાં આવે છે',
                    ),
                    8 =>
                    array(
                        'name' => 'દમ આલૂ કાશ્મીરી',
                        'desc' => 'આલુ મસાલેદાર કાશ્મીરી રેડ ગ્રેવીમાં રાંધવામાં આવે છે',
                    ),
                    9 =>
                    array(
                        'name' => 'આલૂ ચટની વાલા',
                        'desc' => 'આલૂ ચાટપાટા પાલક ગ્રેવીમાં બનાવે છે',
                    ),
                    10 =>
                    array(
                        'name' => 'ભીંડી મસાલો',
                        'desc' => 'ભીંડી જાડી લાલ ગ્રેવીમાં રાંધેલી, અર્ધ સૂકી',
                    ),
                    11 =>
                    array(
                        'name' => 'પલક લસૂની',
                        'desc' => 'પાલક લસણ સાથે રાંધવામાં આવે છે, લસણ તડકા સાથે પીરસવામાં આવે છે',
                    ),
                    12 =>
                    array(
                        'name' => 'સબઝી બેમિસાલ',
                        'desc' => 'હળવી પીળી ગ્રેવીમાં વેજ મિક્સ કરો',
                    ),
                    13 =>
                    array(
                        'name' => 'સબઝી ચિલી મિલી',
                        'desc' => 'સ્પાઈસી વેજીટેબલ પકોડા સ્પાઈસી રેડ ગ્રેવી સાથે પીરસવામાં આવે છે',
                    ),
                    14 =>
                    array(
                        'name' => 'સબઝી દેશી તવા',
                        'desc' => 'પીળા તવા ગ્રેવીમાં રાંધેલું મિક્સ વેજ, મીડીયમ સ્પાઈસી',
                    ),
                    15 =>
                    array(
                        'name' => 'સબઝી હારા ધનિયા',
                        'desc' => 'હળવા ગ્રીન ગ્રેવી, મીડીયમ સોઈસીમાં હરા ધનિયામાં રાંધેલું મિક્સ વેજ',
                    ),
                    16 =>
                    array(
                        'name' => 'મેથી મટર મલાઈ',
                        'desc' => 'કાજુ ગ્રેવીમાં રાંધેલા મેથીના પાન અને મટર, હળવી મીઠી',
                    ),
                    17 =>
                    array(
                        'name' => 'સબઝી અમરાવતી',
                        'desc' => 'કઢી પત્તા અને રાય તડકા, મસાલેદાર સાથે રેડ ગ્રેવી પર વેજ મિક્સ કરો',
                    ),
                    18 =>
                    array(
                        'name' => 'સબઝી દેહતી મસાલા',
                        'desc' => 'સમારેલી પાલક અને મેથીના પાન, મસાલેદાર લીલી ગ્રેવી, સેમી ડ્રાય સાથે વેજ મિક્સ કરો',
                    ),
                    19 =>
                    array(
                        'name' => 'સબઝી દિવાની હાંડી',
                        'desc' => 'મટર, મકાઈ, પાલક ગ્રેવીમાં રાંધેલા મશરૂમ, મધ્યમ મસાલેદાર',
                    ),
                    20 =>
                    array(
                        'name' => 'સબઝી કડાઈ',
                        'desc' => 'મસાલેદાર કડાઈની લાલ ગ્રેવીમાં રાંધેલું મિક્સ વેજ',
                    ),
                    21 =>
                    array(
                        'name' => 'સબઝી ખીમા કોલ્હાપુરી	 ',
                        'desc' => 'સબઝી ખીમા કોલ્હાપુરી	',
                    ),
                    22 =>
                    array(
                        'name' => 'સબઝી મખાણી',
                        'desc' => 'ટામેટા અને કાજુ ગ્રેવીમાં રાંધેલું મિક્સ વેજ, હળવું સ્વીટ',
                    ),
                    23 =>
                    array(
                        'name' => 'સબઝી નવરત્ન કોરમા',
                        'desc' => 'મિક્સ વેજ, ફ્રુટ અને ડ્રાય ફ્રુટ કાજુ ગ્રેવીમાં રાંધેલા, સ્વીટ',
                    ),
                    24 =>
                    array(
                        'name' => 'સબઝી ચોપ મસાલા',
                        'desc' => 'છેલ્લે પીળી ગ્રેવીમાં રાંધેલા સમારેલા શાકભાજી, હળવા સ્વાદ',
                    ),
                    25 =>
                    array(
                        'name' => 'સબઝી હરિયાળી',
                        'desc' => 'લીલી ગ્રેવીમાં રાંધેલા પનીરનું વેજ અને ક્યુબ મિક્સ કરો, મધ્યમ મસાલેદાર',
                    ),
                    26 =>
                    array(
                        'name' => 'સબઝી રોગાની',
                        'desc' => 'મસાલેદાર રોગાની ગ્રેવીમાં રાંધેલું મિક્સ વેજ',
                    ),
                    27 =>
                    array(
                        'name' => 'સબઝી તવા લજવાબ',
                        'desc' => 'સબઝી તવા લજવાબ',
                    ),
                    28 =>
                    array(
                        'name' => 'સબઝી લખનૌવી',
                        'desc' => 'ડુંગળી, કેપ્સીકમ, બેબી કોર્ન, મસાલેદાર લાલ લખનવી ગ્રેવીમાં રાંધેલા શાકભાજી',
                    ),
                    29 =>
                    array(
                        'name' => 'સબઝી લાહોરી કોફ્તા',
                        'desc' => 'વેજ કોફતા પાલક ગ્રેવીમાં રાંધે છે',
                    ),
                    30 =>
                    array(
                        'name' => 'ચીઝ કોફ્તા',
                        'desc' => 'ચીઝ કોફતા પીળી ગ્રેવી, મીડીયમ સ્પાઈસી સાથે સર્વ કરો',
                    ),
                    31 =>
                    array(
                        'name' => 'સાગવાલા ચીઝ કોફ્તા',
                        'desc' => 'પનીર કોફ્તા માઈલ્ડ પાલક ગ્રેવી સાથે સર્વ કરો',
                    ),
                    32 =>
                    array(
                        'name' => 'તિરંગી મિર્ચ મશરૂમ	 ',
                        'desc' => 'મશરૂમ, કેપ્સિકમ, બેલ મરી મસાલેદાર લાલ ગ્રેવીમાં રાંધવામાં આવે છે  ',
                    ),
                    33 =>
                    array(
                        'name' => 'ઢીંગરી દાણીયા કોરમા',
                        'desc' => 'કોથમીર ફ્લેવર ગ્રીન ગ્રેવીમાં રાંધેલા મશરૂમ  ',
                    ),
                    34 =>
                    array(
                        'name' => 'પલક પનીર',
                        'desc' => 'પનીર પાલક ગ્રેવીમાં રાંધે છે',
                    ),
                    35 =>
                    array(
                        'name' => 'પનીર મખાણી',
                        'desc' => 'પનીર ક્યુબ ટામેટા અને કાજુ ગ્રેવીમાં રાંધવામાં આવે છે, હળવી મીઠી',
                    ),
                    36 =>
                    array(
                        'name' => 'પનીર લજ્જતદાર',
                        'desc' => 'ફિંગર કટ પનીરને પીળી ગ્રેવીમાં કેપ્સિકમના જુલીન સાથે રાંધવામાં આવે છે, હળવું',
                    ),
                    37 =>
                    array(
                        'name' => 'પનીર પટિયાલા શાહી',
                        'desc' => 'ફિંગર કટ પનીર લાલ પટિયાલા ગ્રેવીમાં રાંધેલું, મધ્યમ મસાલેદાર',
                    ),
                    38 =>
                    array(
                        'name' => 'પનીર દેશી હાંડી',
                        'desc' => 'પનીર ક્યુબ, ડુંગળી, ટામેટા અને કેપ્સિકમ લાલ ગ્રેવીમાં રાંધેલા, મધ્યમ મસાલેદાર',
                    ),
                    39 =>
                    array(
                        'name' => 'પનીર કડાઈ',
                        'desc' => 'મસાલેદાર લાલ કડાઈ ગ્રેવીમાં રાંધેલા પનીર ક્યુબ',
                    ),
                    40 =>
                    array(
                        'name' => 'અમૃતસરી પનીર ભુર્જી',
                        'desc' => 'પરંપરાગત પનીર ભુરજી, મધ્યમ મસાલેદાર',
                    ),
                    41 =>
                    array(
                        'name' => 'ભુને પનીર કા સાલન',
                        'desc' => 'ફિંગર કટ પનીર તળેલી ડુંગળી સાથે બ્રાઉન ગ્રેવીમાં રાંધેલું, મસાલેદાર',
                    ),
                    42 =>
                    array(
                        'name' => 'પનીર બટર મસાલા',
                        'desc' => 'પનીર ક્યુબ પીળી ગ્રેવીમાં બટર, માઈલ્ડ સાથે રાંધવામાં આવે છે',
                    ),
                    43 =>
                    array(
                        'name' => 'પનીર હૈદરાબાદી મસાલા',
                        'desc' => 'મસાલેદાર હાઇદ્રાબાદી બ્રાઉન ગ્રેવી સાથે રાંધેલા પનીર ટિક્કા',
                    ),
                    44 =>
                    array(
                        'name' => 'પનીર લખનવી તવા',
                        'desc' => 'પનીર ક્યુબ, ડુંગળી અને કેપ્સિકમ લાલ ગ્રેવીમાં લખનવી મસાલા સાથે રાંધેલા, મસાલેદાર',
                    ),
                    45 =>
                    array(
                        'name' => 'પનીર ટિક્કા મસાલા',
                        'desc' => 'મસાલેદાર લાલ ગ્રેવીમાં રાંધેલા પનીર ટિક્કા, ડુંગળી, ટામેટા અને કેપ્સિકમ',
                    ),
                    46 =>
                    array(
                        'name' => 'પનીર ખુર્ચન',
                        'desc' => 'કઢી પટ્ટા અને રાય તડકા, મધ્યમ મસાલેદાર સાથે લાલ ગ્રેવીમાં રાંધવામાં આવેલું પનીર',
                    ),
                    47 =>
                    array(
                        'name' => 'પનીર લસૂની',
                        'desc' => 'પનીર ક્યુબ પીળી ગ્રેવીમાં ઘણાં લસણ સાથે રાંધવામાં આવે છે, મધ્યમ મસાલેદાર',
                    ),
                    48 =>
                    array(
                        'name' => 'પનીર ખાડા મસાલા',
                        'desc' => 'પીળી ગ્રેવીમાં બેલીફ, માખણ અને લસણ સાથે રાંધેલા પનીર ક્યુબ, મસાલેદાર',
                    ),
                ),
                array(
                    0 =>
                    array(
                        'name' => 'સબઝી મેલોની',
                        'desc' => 'ખાસ દેશી તડકા સાથે ભરપૂર ટામેટા અને કાજુ ગ્રેવીમાં રાંધેલા વિદેશી શાકભાજી અને મિક્સ વેજ.  ',
                    ),
                    1 =>
                    array(
                        'name' => 'રજવાડી હાંડી',
                        'desc' => 'રજવાડી મસાલા સાથે ડુંગળી અને ટામેટા ગ્રેવીમાં રાંધેલા શાકભાજી, મકાઈ, કાજુ',
                    ),
                    2 =>
                    array(
                        'name' => 'સબઝિઓન કા મેળા',
                        'desc' => 'શાકભાજી, બટેટા, બેબી કોર્ન, ચેરી ટામેટા કાજુ ગ્રેવીના સ્પર્શ સાથે ડુંગળીની ગ્રેવીમાં રાંધવામાં આવે છે.',
                    ),
                    3 =>
                    array(
                        'name' => 'સબઝી પેશાવરી',
                        'desc' => 'મસાલેદાર ટમેટા ડુંગળી ગ્રેવીમાં રાંધેલા પાસાદાર શાકભાજી',
                    ),
                    4 =>
                    array(
                        'name' => 'સબઝી બેગમ બહાર',
                        'desc' => 'એક જ થાળી પર બેનું મિશ્રણ',
                    ),
                    5 =>
                    array(
                        'name' => 'કબાબ મસાલા',
                        'desc' => 'છૂંદેલા બટેટા અને પનીરમાંથી બનાવેલ શેખ કબાબ સફેદ અને ડુંગળીની ગ્રેવી સાથે રાંધવામાં આવે છે  ',
                    ),
                    6 =>
                    array(
                        'name' => 'પનીર પહાડી મસાલા',
                        'desc' => 'સ્પેશિયલ પહાડી મસાલા ગ્રેવીમાં શેકેલું પનીર',
                    ),
                    7 =>
                    array(
                        'name' => 'પનીર કે શોલે	 ',
                        'desc' => 'ટામેટા અને કાજુ ગ્રેવીમાં તંદૂરી મસાલા સાથે રાંધેલા ડાયમંડ કટ મેરીનેટેડ પનીર',
                    ),
                    8 =>
                    array(
                        'name' => 'પનીર અમૃતસરી	 ',
                        'desc' => 'ટામેટાં અને ડુંગળીની ગ્રેવીમાં રાંધેલા ક્યુબ્સ અને છીણેલું પનીર',
                    ),
                    9 =>
                    array(
                        'name' => 'પનીર દમ લબદાર',
                        'desc' => 'ગ્રીલ્ડ પનીર ફિંગર રિચ ટામેટા અને કાજુ ગ્રેવીમાં રાંધે છે  ',
                    ),
                    10 =>
                    array(
                        'name' => 'પનીર મખમલી',
                        'desc' => 'સ્મૂથ રિચ રેડ ગ્રેવીમાં રાંધેલું સ્મોલ ડાયમંડ કટ પનીર  ',
                    ),
                    11 =>
                    array(
                        'name' => 'રજવાડી પનીર ટીક્કા મસાલા',
                        'desc' => 'રજવાડી મસાલા, ડુંગળી અને ટામેટાની ગ્રેવી સાથે રાંધેલા તંદૂરી પનીર ટિક્કા  ',
                    ),
                    12 =>
                    array(
                        'name' => 'નવાબી કાજુ પનીર',
                        'desc' => 'તળેલા કાજુ અને પનીર મસાલેદાર પીળી ગ્રેવી સાથે રાંધેલા, માખણ અને ક્રીમથી લેસ',
                    ),
                    13 =>
                    array(
                        'name' => 'કાજુ મસાલા',
                        'desc' => 'ટામેટામાં રાંધેલા કાજુ અને મસાલા સાથે ડુંગળીની ગ્રેવી.',
                    ),
                    14 =>
                    array(
                        'name' => 'ચીઝ લબાબ',
                        'desc' => 'મસાલાવાળા ટામેટાની ગ્રેવીમાં રાંધેલા ચીઝના ક્યુબ્સ  ',
                    ),
                ),
                array(
                    0 =>
                    array(
                        'name' => 'રોટી',
                        'desc' => NULL,
                    ),
                    1 =>
                    array(
                        'name' => 'કુલચા',
                        'desc' => NULL,
                    ),
                    2 =>
                    array(
                        'name' => 'પરાઠા',
                        'desc' => NULL,
                    ),
                    3 =>
                    array(
                        'name' => 'મેથી રોટી',
                        'desc' => NULL,
                    ),
                    4 =>
                    array(
                        'name' => 'પુદીના રોટી',
                        'desc' => NULL,
                    ),
                    5 =>
                    array(
                        'name' => 'પાલક રોટી',
                        'desc' => NULL,
                    ),
                    6 =>
                    array(
                        'name' => 'લસણની રોટલી',
                        'desc' => NULL,
                    ),
                    7 =>
                    array(
                        'name' => 'મિસી રોટી',
                        'desc' => NULL,
                    ),
                    8 =>
                    array(
                        'name' => 'નાન',
                        'desc' => NULL,
                    ),
                    9 =>
                    array(
                        'name' => 'રોગાની નાન',
                        'desc' => NULL,
                    ),
                    10 =>
                    array(
                        'name' => 'બટર રૂમલી રોટલી',
                        'desc' => NULL,
                    ),
                    11 =>
                    array(
                        'name' => 'કાશ્મીરી નાન',
                        'desc' => NULL,
                    ),
                    12 =>
                    array(
                        'name' => 'લસણ નાન',
                        'desc' => NULL,
                    ),
                    13 =>
                    array(
                        'name' => 'મસાલા કુલચા',
                        'desc' => NULL,
                    ),
                    14 =>
                    array(
                        'name' => 'સ્ટફ્ડ પરાઠા',
                        'desc' => NULL,
                    ),
                    15 =>
                    array(
                        'name' => 'ચીઝ નાન',
                        'desc' => NULL,
                    ),
                    16 =>
                    array(
                        'name' => 'રોટી કી ટોકરી',
                        'desc' => NULL,
                    ),
                ),
                array(
                    0 =>
                    array(
                        'name' => 'વરાળ ચોખા',
                        'desc' => NULL,
                    ),
                    1 =>
                    array(
                        'name' => 'જીરા ચોખા',
                        'desc' => NULL,
                    ),
                    2 =>
                    array(
                        'name' => 'ઘી ભાટ	 ',
                        'desc' => NULL,
                    ),
                    3 =>
                    array(
                        'name' => 'લીલા વટાણા ચોખા',
                        'desc' => NULL,
                    ),
                    4 =>
                    array(
                        'name' => 'દહીં ચોખા',
                        'desc' => NULL,
                    ),
                    5 =>
                    array(
                        'name' => 'દાલ ખીચડી',
                        'desc' => NULL,
                    ),
                    6 =>
                    array(
                        'name' => 'લસણ તડકા સાથે દાલ ખીચડી',
                        'desc' => NULL,
                    ),
                    7 =>
                    array(
                        'name' => 'પાલક ખીચડી',
                        'desc' => NULL,
                    ),
                    8 =>
                    array(
                        'name' => 'સબઝી મસાલા ખીચડી',
                        'desc' => NULL,
                    ),
                    9 =>
                    array(
                        'name' => 'શાક પુલાવ',
                        'desc' => NULL,
                    ),
                    10 =>
                    array(
                        'name' => 'ચીઝ મસાલા પુલાઓ',
                        'desc' => NULL,
                    ),
                    11 =>
                    array(
                        'name' => 'કાશ્મીરી પુલાવ',
                        'desc' => NULL,
                    ),
                    12 =>
                    array(
                        'name' => 'વેજીટેબલ બિરયાની',
                        'desc' => NULL,
                    ),
                    13 =>
                    array(
                        'name' => 'હૈદરાબાદી બિરયાની  ',
                        'desc' => NULL,
                    ),
                    14 =>
                    array(
                        'name' => 'પનીર ટિક્કા બિરયાની',
                        'desc' => NULL,
                    ),
                ),
                array(
                    0 =>
                    array(
                        'name' => 'હોંગકોંગ સોસમાં શાકભાજી',
                        'desc' => NULL,
                    ),
                    1 =>
                    array(
                        'name' => 'મીઠી અને ખાટી ચટણીમાં શાકભાજી',
                        'desc' => NULL,
                    ),
                    2 =>
                    array(
                        'name' => 'થાઈ ગ્રીન કરી માં શાકભાજી',
                        'desc' => NULL,
                    ),
                    3 =>
                    array(
                        'name' => 'સિચુઆન મરીની ચટણીમાં શાકભાજી',
                        'desc' => NULL,
                    ),
                    4 =>
                    array(
                        'name' => 'ગરમ લસણની ચટણીમાં શાકભાજી',
                        'desc' => NULL,
                    ),
                    5 =>
                    array(
                        'name' => 'લાલ થાઈ કરી માં પનીર 	 ',
                        'desc' => NULL,
                    ),
                    6 =>
                    array(
                        'name' => 'હુનાન સોસમાં પનીર  ',
                        'desc' => NULL,
                    ),
                    7 =>
                    array(
                        'name' => 'ગરમ લસણની ચટણીમાં પનીર  ',
                        'desc' => NULL,
                    ),
                ),
                array(
                    0 =>
                    array(
                        'name' => 'વેજીટેબલ ફ્રાઈડ રાઈસ',
                        'desc' => NULL,
                    ),
                    1 =>
                    array(
                        'name' => 'બર્ન લસણ ચોખા',
                        'desc' => NULL,
                    ),
                    2 =>
                    array(
                        'name' => 'સિચુઆન ચોખા',
                        'desc' => NULL,
                    ),
                    3 =>
                    array(
                        'name' => 'લીંબુ આદુ ચોખા',
                        'desc' => NULL,
                    ),
                    4 =>
                    array(
                        'name' => 'તુલસીનો ચોખા',
                        'desc' => NULL,
                    ),
                    5 =>
                    array(
                        'name' => 'મસાલેદાર થાઈ ચોખા',
                        'desc' => NULL,
                    ),
                    6 =>
                    array(
                        'name' => 'ફોર્ચ્યુન ફ્રાઇડ રાઇસ',
                        'desc' => NULL,
                    ),
                    7 =>
                    array(
                        'name' => 'મશરૂમ ફ્રાઇડ રાઇસ',
                        'desc' => NULL,
                    ),
                    8 =>
                    array(
                        'name' => 'ગાર્ડન સ્કીલેટ શાક સાથે પીસેલા ચોખા',
                        'desc' => NULL,
                    ),
                    9 =>
                    array(
                        'name' => 'શાકભાજી ફુ ચી ચોખા',
                        'desc' => NULL,
                    ),
                    10 =>
                    array(
                        'name' => 'ટ્રિપલ સિચુઆન ફ્રાઇડ રાઇસ',
                        'desc' => NULL,
                    ),
                ),
                array(
                    0 =>
                    array(
                        'name' => 'હક્કા નૂડલ્સ',
                        'desc' => NULL,
                    ),
                    1 =>
                    array(
                        'name' => 'ચિલી ગાર્લિક નૂડલ્સ',
                        'desc' => NULL,
                    ),
                    2 =>
                    array(
                        'name' => 'સિચુઆન નૂડલ્સ',
                        'desc' => NULL,
                    ),
                    3 =>
                    array(
                        'name' => 'સિંગાપોર નૂડલ્સ',
                        'desc' => NULL,
                    ),
                    4 =>
                    array(
                        'name' => 'મસાલેદાર બેસિલ નૂડલ્સ',
                        'desc' => NULL,
                    ),
                    5 =>
                    array(
                        'name' => 'મંથાઈ નૂડલ્સ',
                        'desc' => NULL,
                    ),
                    6 =>
                    array(
                        'name' => 'પોટ નૂડલ્સ',
                        'desc' => NULL,
                    ),
                    7 =>
                    array(
                        'name' => 'સિઝલિંગ નૂડલ્સ',
                        'desc' => NULL,
                    ),
                    8 =>
                    array(
                        'name' => 'સેસામી પીનટ નૂડલ્સ',
                        'desc' => NULL,
                    ),
                    9 =>
                    array(
                        'name' => 'અમેરિકન Chopsuey',
                        'desc' => NULL,
                    ),
                ),
                array(
                    0 =>
                    array(
                        'name' => 'મેક્સીકન ચિલી બીન',
                        'desc' => 'મકાઈ, કઠોળ અને શાકભાજી સાથે ચોખા ફેંકવામાં આવે છે',
                    ),
                    1 =>
                    array(
                        'name' => 'પિમેન્ટો',
                        'desc' => 'બેલ મરી, પરમેસન ચીઝ અને પાર્સલી સાથે ઉછાળેલા ચોખા',
                    ),
                    2 =>
                    array(
                        'name' => 'મરીના અમેરિકન ચોખા',
                        'desc' => 'કઠોળ, મકાઈ, ઘંટડી મરી, મરચાં અને મસાલાઓ સાથે ઉછાળેલા ચોખા',
                    ),
                    3 =>
                    array(
                        'name' => 'ઇન્ડોનેશિયન ચોખા',
                        'desc' => 'જડીબુટ્ટીઓ, લીંબુ, ડુંગળી, તુલસીનો છોડ, શાકભાજી અને મસાલાઓથી સુશોભિત ચોખા',
                    ),
                    4 =>
                    array(
                        'name' => 'લેબનીઝ બિરયાની',
                        'desc' => NULL,
                    ),
                ),
                array(
                    0 =>
                    array(
                        'name' => 'ક્રીમની પસંદગી',
                        'desc' => 'વેનીલા / ચોકલેટ / સ્ટ્રોબેરી / બટરસ્કોચ / કેસર પિસ્તા',
                    ),
                    1 =>
                    array(
                        'name' => 'ગુલાબ જામુન',
                        'desc' => NULL,
                    ),
                    2 =>
                    array(
                        'name' => 'વેનીલા આઈસ્ક્રીમ સાથે ગુલાબ જામુન',
                        'desc' => NULL,
                    ),
                    3 =>
                    array(
                        'name' => 'હોટ ચોકલેટ સોસ સાથે વેનીલા આઈસ્ક્રીમ',
                        'desc' => NULL,
                    ),
                    4 =>
                    array(
                        'name' => 'હોટ લવારો અખરોટ Sundae',
                        'desc' => 'વેનીલા અને ચોકલેટ આઈસ્ક્રીમ, હોટ ચોકલેટ સોસ અને નટ્સ',
                    ),
                    5 =>
                    array(
                        'name' => 'સિઝલિંગ બ્રાઉની',
                        'desc' => 'વેનીલા આઈસ્ક્રીમ અને હોટ ચોકલેટ સોસ સાથે બ્રાઉની ટોચ પર છે',
                    ),
                    6 =>
                    array(
                        'name' => 'એફિલ ટાવર',
                        'desc' => 'ફળોના ક્રશ અને ચોકલેટ નટ્સ સાથે વિવિધ આઈસ્ક્રીમથી બનેલો ટાવર',
                    ),
                    7 =>
                    array(
                        'name' => 'બનાના સ્પ્લિટ',
                        'desc' => 'આઇસક્રીમના ત્રણ અલગ-અલગ સ્કૂપ સાથે ફ્રેશ કટ બનાના, કારમેલ અને ક્રશ અને ચોકલેટ નટ્સ સાથે ટોચ પર છે   ',
                    ),
                    8 =>
                    array(
                        'name' => 'ચોકલેટ દ્વારા મૃત્યુ',
                        'desc' => 'ચોકલેટ આઈસ્ક્રીમ, ચોકલેટ સોસ, ચોકો ચિપ્સ સાથે ટોચની ચોકલેટ કેક',
                    ),
                    9 =>
                    array(
                        'name' => 'મડ પાઇ',
                        'desc' => 'ક્લાસિક હોટ ચોકલેટ પાઇ વેનીલા આઈસ્ક્રીમ સાથે પીરસવામાં આવે છે',
                    ),
                    10 =>
                    array(
                        'name' => 'ચોકલેટ ફોન્ડ્યુ',
                        'desc' => 'ચોકલેટ ફોન્ડ્યુને ચોકોસ્ટિક્સ, વેફર બિસ્કિટ, બ્રાઉની અને વિવિધ મોસમી તાજા ફળો સાથે પીરસવામાં આવે છે.',
                    ),
                ),
                array(
                    0 =>
                    array(
                        'name' => 'શુદ્ધ પાણી',
                        'desc' => NULL,
                    ),
                    1 =>
                    array(
                        'name' => 'વાયુયુક્ત પીણાં',
                        'desc' => NULL,
                    ),
                    2 =>
                    array(
                        'name' => 'નિમ્બુ પાણી/સોડા',
                        'desc' => NULL,
                    ),
                    3 =>
                    array(
                        'name' => 'કોકમ શરબત',
                        'desc' => NULL,
                    ),
                    4 =>
                    array(
                        'name' => 'ચાસ',
                        'desc' => NULL,
                    ),
                    5 =>
                    array(
                        'name' => 'મસાલા કોલા',
                        'desc' => NULL,
                    ),
                    6 =>
                    array(
                        'name' => 'મીઠું ચડાવેલું / મીઠી લસ્સી',
                        'desc' => NULL,
                    ),
                    7 =>
                    array(
                        'name' => 'આઈસ્ક્રીમ સાથે કોલ્ડ કોફી',
                        'desc' => NULL,
                    ),
                    8 =>
                    array(
                        'name' => 'હોટ કોફી',
                        'desc' => NULL,
                    ),
                ),
            ];
            $lang_hi = [
                array(
                    0 =>
                    array(
                        'name' => 'एक प्रकार की मिश्रित शराब',
                        'desc' => 'स्वाद का विकल्प नींबू, आड़ू, काला करंट',
                    ),
                    1 =>
                    array(
                        'name' => 'लाइम मोजिटो',
                        'desc' => 'टकसाल, चूना, स्प्राइट',
                    ),
                    2 =>
                    array(
                        'name' => 'जुनून फल Mojito',
                        'desc' => 'पैशन फ्रूट सिरप, मिंट, लाइम, सोडा',
                    ),
                    3 =>
                    array(
                        'name' => 'ऑरेंज मिमोसा',
                        'desc' => 'संतरे का रस, ताजा पुदीना, नींबू का रस, स्प्राइट',
                    ),
                    4 =>
                    array(
                        'name' => 'ब्लू ब्रीजर',
                        'desc' => 'ब्लू काराकोआ, लीची क्रश, ग्रेनाडीन सिरप, नीबू का रस, स्प्राइट',
                    ),
                    5 =>
                    array(
                        'name' => 'ताज़ा थाई नारियल',
                        'desc' => 'नारियल सिरप, तुलसी के पत्ते, नीबू का रस, स्प्राइट के साथ शीर्ष पर',
                    ),
                    6 =>
                    array(
                        'name' => 'पीना कोलाडा',
                        'desc' => 'अनानास का रस, नारियल का दूध, आइसक्रीम',
                    ),
                    7 =>
                    array(
                        'name' => 'टॉम जेरी',
                        'desc' => 'सेब का रस, संतरे का रस, ग्रेनाडीन सिरप',
                    ),
                    8 =>
                    array(
                        'name' => 'स्ट्रॉबेरी साइट्रस',
                        'desc' => 'स्ट्राबेरी क्रश, ब्लैकबेरी सिरप, नीबू का रस, सोडा',
                    ),
                    9 =>
                    array(
                        'name' => 'माई थाई',
                        'desc' => 'अनानस का रस, अनानस क्रश और क्रैनबेरी रस',
                    ),
                    10 =>
                    array(
                        'name' => 'इतालवी क्रीम सोडा',
                        'desc' => 'जुनून फल, तरबूज, स्प्राइट, सोडा, क्रीम',
                    ),
                    11 =>
                    array(
                        'name' => 'स्पार्कलिंग मिक्स फ्रूट शगरिया',
                        'desc' => 'मैला ताजा फल, फलों के क्रश सोडा और स्प्राइट के साथ सबसे ऊपर है',
                    ),
                    12 =>
                    array(
                        'name' => 'लीची नारियल मॉकर',
                        'desc' => 'दूध, लीची, नारियल का दूध, आइसक्रीम',
                    ),
                    13 =>
                    array(
                        'name' => 'शाइनिंग बुल',
                        'desc' => 'तरबूज, स्ट्रॉबेरी, क्रैनबेरी, ग्रेनाडीन सिरप, रेड बुल',
                    ),
                    14 =>
                    array(
                        'name' => 'खतरनाक लाल',
                        'desc' => 'रास्पबेरी सिरप, शहद, चूना, रेड बुल',
                    ),
                ),
                array(
                    0 =>
                    array(
                        'name' => 'टमाटर की मलाई',
                        'desc' => NULL,
                    ),
                    1 =>
                    array(
                        'name' => 'क्रीम ऑफ मशरूम',
                        'desc' => NULL,
                    ),
                    2 =>
                    array(
                        'name' => 'सब्जी की क्रीम',
                        'desc' => NULL,
                    ),
                    3 =>
                    array(
                        'name' => 'लहसुन की मलाई',
                        'desc' => NULL,
                    ),
                    4 =>
                    array(
                        'name' => 'इटली का सब्जी और पासता वाला सूप  ',
                        'desc' => NULL,
                    ),
                    5 =>
                    array(
                        'name' => 'मैक्सिकन टॉर्टिला',
                        'desc' => NULL,
                    ),
                    6 =>
                    array(
                        'name' => 'ब्रोकोली बादाम',
                        'desc' => NULL,
                    ),
                ),
                array(
                    0 =>
                    array(
                        'name' => 'सब्जी साफ़',
                        'desc' => NULL,
                    ),
                    1 =>
                    array(
                        'name' => 'स्वीट कॉर्न',
                        'desc' => NULL,
                    ),
                    2 =>
                    array(
                        'name' => 'तुम युमो',
                        'desc' => NULL,
                    ),
                    3 =>
                    array(
                        'name' => 'गरम और खट्टा  ',
                        'desc' => NULL,
                    ),
                    4 =>
                    array(
                        'name' => 'नींबू धनिया	 ',
                        'desc' => NULL,
                    ),
                    5 =>
                    array(
                        'name' => 'मंचो',
                        'desc' => NULL,
                    ),
                    6 =>
                    array(
                        'name' => 'शंघाई सूप',
                        'desc' => NULL,
                    ),
                    7 =>
                    array(
                        'name' => 'पालक नूडल्स सूप',
                        'desc' => NULL,
                    ),
                    8 =>
                    array(
                        'name' => 'बर्मी सूप',
                        'desc' => NULL,
                    ),
                    9 =>
                    array(
                        'name' => 'हवा हवाई सूप',
                        'desc' => NULL,
                    ),
                    10 =>
                    array(
                        'name' => 'हरा एशियाई सूप',
                        'desc' => NULL,
                    ),
                ),
                array(
                    0 =>
                    array(
                        'name' => 'भुना हुआ पापड़',
                        'desc' => NULL,
                    ),
                    1 =>
                    array(
                        'name' => 'तले हुए पापड़',
                        'desc' => NULL,
                    ),
                    2 =>
                    array(
                        'name' => 'मसाला पापड़ी',
                        'desc' => NULL,
                    ),
                    3 =>
                    array(
                        'name' => 'पनीर मसाला पापड़ो',
                        'desc' => NULL,
                    ),
                    4 =>
                    array(
                        'name' => 'करारी रूमाली',
                        'desc' => NULL,
                    ),
                    5 =>
                    array(
                        'name' => 'हरा सलाद',
                        'desc' => NULL,
                    ),
                    6 =>
                    array(
                        'name' => 'मसाला प्याज',
                        'desc' => NULL,
                    ),
                    7 =>
                    array(
                        'name' => 'टास कियाहुवा सलाद',
                        'desc' => NULL,
                    ),
                    8 =>
                    array(
                        'name' => 'जर्मन आलू की सलाद',
                        'desc' => NULL,
                    ),
                    9 =>
                    array(
                        'name' => 'बटाटा हरा',
                        'desc' => NULL,
                    ),
                    10 =>
                    array(
                        'name' => 'रूसी सलाद',
                        'desc' => NULL,
                    ),
                    11 =>
                    array(
                        'name' => 'कॉकटेल सलाद',
                        'desc' => NULL,
                    ),
                    12 =>
                    array(
                        'name' => 'बूंदी रायता',
                        'desc' => NULL,
                    ),
                    13 =>
                    array(
                        'name' => 'सब्जी रायता',
                        'desc' => NULL,
                    ),
                    14 =>
                    array(
                        'name' => 'पुदीना प्याज का रायता',
                        'desc' => NULL,
                    ),
                    15 =>
                    array(
                        'name' => 'अनानास रायता',
                        'desc' => NULL,
                    ),
                    16 =>
                    array(
                        'name' => 'पनीर के साथ करारी रूमाली',
                        'desc' => NULL,
                    ),
                ),
                array(
                    0 =>
                    array(
                        'name' => 'हरा भरा कबाब',
                        'desc' => NULL,
                    ),
                    1 =>
                    array(
                        'name' => 'राजवाड़ी रोल',
                        'desc' => NULL,
                    ),
                    2 =>
                    array(
                        'name' => 'शाकाहारी बुलेट',
                        'desc' => NULL,
                    ),
                ),
                array(
                    0 =>
                    array(
                        'name' => 'चीनी भेल (ठंडा)',
                        'desc' => NULL,
                    ),
                    1 =>
                    array(
                        'name' => 'चिलचिलाती मिर्च आलू',
                        'desc' => NULL,
                    ),
                    2 =>
                    array(
                        'name' => 'सब्जी धनिया मिर्च',
                        'desc' => NULL,
                    ),
                    3 =>
                    array(
                        'name' => 'खस्ता सब्जियां',
                        'desc' => NULL,
                    ),
                    4 =>
                    array(
                        'name' => 'हम्प्टी डम्प्टी',
                        'desc' => NULL,
                    ),
                    5 =>
                    array(
                        'name' => 'वेजिटेबल ग्रीन बॉल्स',
                        'desc' => NULL,
                    ),
                    6 =>
                    array(
                        'name' => 'सब्जी लॉलीपॉप',
                        'desc' => NULL,
                    ),
                    7 =>
                    array(
                        'name' => 'सब्जी मंचूरियन सूखी',
                        'desc' => NULL,
                    ),
                    8 =>
                    array(
                        'name' => 'सब्जी "65"',
                        'desc' => NULL,
                    ),
                    9 =>
                    array(
                        'name' => 'पेरी पेरी बॉल्स',
                        'desc' => NULL,
                    ),
                    10 =>
                    array(
                        'name' => 'कुंग पाओ शाकाहारी',
                        'desc' => NULL,
                    ),
                    11 =>
                    array(
                        'name' => 'बेबीकॉर्न बटर पेपर	 ',
                        'desc' => NULL,
                    ),
                    12 =>
                    array(
                        'name' => 'मशरूम मसालेदार धनिया',
                        'desc' => NULL,
                    ),
                    13 =>
                    array(
                        'name' => 'मिर्च लहसुन की चटनी में मशरूम',
                        'desc' => NULL,
                    ),
                    14 =>
                    array(
                        'name' => 'बेबीकॉर्न मशरूम लाल मिर्च मिर्च',
                        'desc' => NULL,
                    ),
                    15 =>
                    array(
                        'name' => 'नमक और काली मिर्च पनीर',
                        'desc' => NULL,
                    ),
                    16 =>
                    array(
                        'name' => 'टैंगी पनीर',
                        'desc' => NULL,
                    ),
                    17 =>
                    array(
                        'name' => 'तुलसी मिर्च पनीर	 ',
                        'desc' => NULL,
                    ),
                    18 =>
                    array(
                        'name' => 'मिर्च पनीर',
                        'desc' => NULL,
                    ),
                    19 =>
                    array(
                        'name' => 'शंघाई पनीर',
                        'desc' => NULL,
                    ),
                    20 =>
                    array(
                        'name' => 'शहद मिर्च पनीर',
                        'desc' => NULL,
                    ),
                    21 =>
                    array(
                        'name' => 'थाई ची पनीर',
                        'desc' => NULL,
                    ),
                    22 =>
                    array(
                        'name' => 'पनीर लाल मिर्च मिर्च',
                        'desc' => NULL,
                    ),
                    23 =>
                    array(
                        'name' => 'पनीर लेमन बटर',
                        'desc' => NULL,
                    ),
                    24 =>
                    array(
                        'name' => 'मलेशियाई पनीर',
                        'desc' => NULL,
                    ),
                    25 =>
                    array(
                        'name' => 'पनीर पाटिया',
                        'desc' => NULL,
                    ),
                    26 =>
                    array(
                        'name' => 'मून मैन पनीर	 ',
                        'desc' => NULL,
                    ),
                    27 =>
                    array(
                        'name' => 'पनीर बारबेक्यू	 ',
                        'desc' => NULL,
                    ),
                    28 =>
                    array(
                        'name' => 'बेल्जियम कॉटेज पनीर',
                        'desc' => NULL,
                    ),
                    29 =>
                    array(
                        'name' => 'पनीर सिगार',
                        'desc' => NULL,
                    ),
                    30 =>
                    array(
                        'name' => 'सिचुआन चीज़ गोल्डीज़',
                        'desc' => NULL,
                    ),
                ),
                array(
                    0 =>
                    array(
                        'name' => 'मलाई ब्रोकोली',
                        'desc' => NULL,
                    ),
                    1 =>
                    array(
                        'name' => 'चटपटी ब्रोकोली	 ',
                        'desc' => NULL,
                    ),
                    2 =>
                    array(
                        'name' => 'निज़ामी आलू',
                        'desc' => NULL,
                    ),
                    3 =>
                    array(
                        'name' => 'बनारसी सब्जियों की सीख',
                        'desc' => NULL,
                    ),
                    4 =>
                    array(
                        'name' => 'देहाती साधो',
                        'desc' => NULL,
                    ),
                    5 =>
                    array(
                        'name' => 'छोले की शेखो',
                        'desc' => NULL,
                    ),
                    6 =>
                    array(
                        'name' => 'काजू पनीर की सीख',
                        'desc' => NULL,
                    ),
                    7 =>
                    array(
                        'name' => 'तंदूरी मंचूरियन',
                        'desc' => NULL,
                    ),
                    8 =>
                    array(
                        'name' => 'टिक्का पनीर',
                        'desc' => NULL,
                    ),
                    9 =>
                    array(
                        'name' => 'अंगारा पनीर टिक्का',
                        'desc' => NULL,
                    ),
                    10 =>
                    array(
                        'name' => 'बंजारा पनीर टिक्का',
                        'desc' => NULL,
                    ),
                    11 =>
                    array(
                        'name' => 'कड़ाही पनीर टिक्का',
                        'desc' => NULL,
                    ),
                    12 =>
                    array(
                        'name' => 'भूना पनीर टिक्का',
                        'desc' => NULL,
                    ),
                    13 =>
                    array(
                        'name' => 'हैदराबादी पनीर टिक्का',
                        'desc' => NULL,
                    ),
                    14 =>
                    array(
                        'name' => 'शाहजहानी पनीर टिक्का',
                        'desc' => NULL,
                    ),
                    15 =>
                    array(
                        'name' => 'मिर्च मलाई पनीर टिक्का',
                        'desc' => NULL,
                    ),
                    16 =>
                    array(
                        'name' => 'फाइव स्पाइस पनीर टिक्का',
                        'desc' => NULL,
                    ),
                    17 =>
                    array(
                        'name' => 'लसुनिया पनीर टिक्का',
                        'desc' => NULL,
                    ),
                    18 =>
                    array(
                        'name' => 'सागवाला पनीर टिक्का',
                        'desc' => NULL,
                    ),
                    19 =>
                    array(
                        'name' => 'मशरूम टिक्का',
                        'desc' => NULL,
                    ),
                    20 =>
                    array(
                        'name' => 'मुल्तानी मशरूम',
                        'desc' => NULL,
                    ),
                    21 =>
                    array(
                        'name' => 'डबल रोल पनीर टिक्का',
                        'desc' => NULL,
                    ),
                    22 =>
                    array(
                        'name' => 'पनीर पनीर टिक्का',
                        'desc' => NULL,
                    ),
                    23 =>
                    array(
                        'name' => 'हैदराबादी चीज़ टिक्का',
                        'desc' => NULL,
                    ),
                    24 =>
                    array(
                        'name' => 'भरवां पनीर टिक्का',
                        'desc' => NULL,
                    ),
                    25 =>
                    array(
                        'name' => 'तंदूरी थाली',
                        'desc' => NULL,
                    ),
                ),
                array(
                    0 =>
                    array(
                        'name' => 'फ्रेंच फ्राइज़ / केला फ्राइज़',
                        'desc' => 'आलू / केले के डीप फ्राई फिंगर कट्स',
                    ),
                    1 =>
                    array(
                        'name' => 'पनीर डुबकी फ्रेंच फ्राइज़',
                        'desc' => 'पनीर डिप के साथ फिंगर कट आलू',
                    ),
                    2 =>
                    array(
                        'name' => 'जड़ी बूटियों और पनीर के साथ किसान की रोटी',
                        'desc' => 'लहसुन, मक्खन, पनीर और जड़ी बूटियों के साथ फ्रेंच ब्रेड',
                    ),
                    3 =>
                    array(
                        'name' => 'ब्रुस्केटा',
                        'desc' => 'कटा हुआ लहसुन की रोटी बेल मिर्च, जड़ी बूटियों, पनीर और बेक के साथ सबसे ऊपर है',
                    ),
                    4 =>
                    array(
                        'name' => 'टस्कनी ब्रेड',
                        'desc' => 'बेसिल पेस्टो, मकई, शिमला मिर्च, टमाटर, कटा हुआ पालक, पनीर और बेक किया हुआ फ्रेंच पाव रोटी',
                    ),
                    5 =>
                    array(
                        'name' => 'बहुत बढ़िया नाचोस',
                        'desc' => 'क्रिस्पी कॉर्न टॉर्टिला चिप्स चीज़ सॉस और रिफ़्राइड बीन्स के साथ सबसे ऊपर है',
                    ),
                    6 =>
                    array(
                        'name' => 'ओवर लोडेड नाचोस',
                        'desc' => 'क्रिस्पी कॉर्न टॉर्टिला चिप्स साल्सा सॉस, चीज़ सॉस और रिफ़्राइड बीन्स से भरे हुए हैं',
                    ),
                    7 =>
                    array(
                        'name' => 'विदेशी टैकोस	 ',
                        'desc' => 'लेट्यूस, रिफ़्राइड बीन्स और जूलियन कट वेजीज़ और चीज़ से भरे क्रिस्पी टॉर्टिला शेल्स',
                    ),
                    8 =>
                    array(
                        'name' => 'केसाडिला  ',
                        'desc' => 'बीन्स, कॉर्न और चीज़ से भरे सॉफ्ट टॉर्टिला, ग्रिल्ड और साल्सा डिप के साथ क्रिस्पी परोसते हैं',
                    ),
                    9 =>
                    array(
                        'name' => 'चिमिचांगा',
                        'desc' => 'सेम, मकई और पनीर के भरने के साथ नरम टोरिल्ला से बना पॉकेट, क्लासिक टमाटर सॉस और मोज़ेरेला के साथ शीर्ष पर और बेक किया हुआ।',
                    ),
                    10 =>
                    array(
                        'name' => 'इटालियन चीज़ी वोंटों',
                        'desc' => 'पनीर, मक्का, शिमला मिर्च और डीप फ्राई से भरा वॉन्टन पाउच कॉकटेल डिप के साथ परोसा जाता है',
                    ),
                    11 =>
                    array(
                        'name' => 'वेजिटेबल पॉपर्स',
                        'desc' => 'मसालेदार सब्जी बुलेट कॉकटेल सॉस के साथ परोसी',
                    ),
                    12 =>
                    array(
                        'name' => 'चीज़ कॉर्न बॉल्स',
                        'desc' => 'पनीर, पनीर, मकई और जड़ी बूटियों से भरे गोले',
                    ),
                    13 =>
                    array(
                        'name' => 'पालक पनीर रोल्स',
                        'desc' => 'पालक, मकई, पनीर से भरे रोल्स और डीप फ्राईड कॉकटेल सॉस के साथ परोसे',
                    ),
                    14 =>
                    array(
                        'name' => 'सौते सब्जी',
                        'desc' => 'स्वाद के विकल्प के साथ जैतून के तेल में तली हुई विदेशी सब्जियां - साधारण अजवायन, गार्लिक बटर या मसालेदार पेरी पेरी।',
                    ),
                    15 =>
                    array(
                        'name' => 'पनीर टिटबिट',
                        'desc' => 'त्रिभुज के आकार का पनीर, डीप फ्राई किया हुआ और टोमैटो कंसासेस के साथ टॉस किया हुआ।',
                    ),
                    16 =>
                    array(
                        'name' => 'गार्लिक बटर पैन फ्राइड मशरूम',
                        'desc' => 'पासा मशरूम, शिमला मिर्च और प्याज मक्खन के साथ लहसुन में फेंक दिया',
                    ),
                    17 =>
                    array(
                        'name' => 'पनीर स्टेक',
                        'desc' => 'मैरीनेट किया हुआ पनीर स्टेक मक्खन के साथ परोसा गया स्पेगेटी',
                    ),
                    18 =>
                    array(
                        'name' => 'पनीर परमेगियानो',
                        'desc' => 'प्याज़, पनीर और जड़ी बूटियों से भरा पनीर, पेन्को के साथ लेपित, तवा पर ग्रील्ड, मोजरेला के साथ पकाया जाता है',
                    ),
                ),
                array(
                    0 =>
                    array(
                        'name' => 'पाव भाजी फोंड्यू',
                        'desc' => 'मक्खन और पनीर के साथ भारतीय पाव भाजी से बना फोंड्यू, मसाला पाव और आलू के वेजेज के साथ परोसा जाता है',
                    ),
                    1 =>
                    array(
                        'name' => 'पारंपरिक पनीर फोंड्यू',
                        'desc' => 'ब्रेड क्राउटन, फ्रेंच फ्राइज़, सब्जियां, पोटैटो नगेट्स के साथ तीन पनीर से बना फोंड्यू',
                    ),
                ),
                array(
                    0 =>
                    array(
                        'name' => 'अल अल्फ्रेडो सॉस',
                        'desc' => NULL,
                    ),
                    1 =>
                    array(
                        'name' => 'अल अरबियाता सॉस',
                        'desc' => 'तुलसी के स्वाद वाली मसालेदार टमाटर की चटनी',
                    ),
                    2 =>
                    array(
                        'name' => 'डेल बैरोन सॉस',
                        'desc' => 'बेचमेल, टमाटर सॉस, लाल मिर्च और परमेसन चीज़ के साथ एक क्लासिक सॉस',
                    ),
                    3 =>
                    array(
                        'name' => 'एग्लियो ओलियो',
                        'desc' => 'जैतून के तेल में लहसुन, मिर्च के गुच्छे और धूप में सुखाए हुए टमाटर के साथ पास्ता, परमेसन चीज़ और अजमोद के साथ शीर्ष पर',
                    ),
                    4 =>
                    array(
                        'name' => 'अल फॉर्मैगियो',
                        'desc' => 'मस्टर्ड सॉस, चीज़ सॉस और सब्जियों के साथ व्हाइट सॉस।',
                    ),
                    5 =>
                    array(
                        'name' => 'तुलसी का सॉस',
                        'desc' => 'ताज़ी तुलसी, अखरोट और लहसुन से बनी क्लासिक पेस्टो सॉस',
                    ),
                    6 =>
                    array(
                        'name' => 'तुलसी फॉर्मैगियो',
                        'desc' => 'सब्जियों के साथ अल्फ्रेडो सॉस, तुलसी पेस्टो और फॉर्मैगियो सॉस',
                    ),
                ),
                array(
                    0 =>
                    array(
                        'name' => 'क्लासिक मार्गरीटा',
                        'desc' => 'टमाटर, पनीर, तुलसी',
                    ),
                    1 =>
                    array(
                        'name' => 'सब्जी Verdure	 ',
                        'desc' => 'ग्रील्ड सब्जियां, मोत्ज़ारेला पनीर',
                    ),
                    2 =>
                    array(
                        'name' => 'स्मोकी फ्यूजन',
                        'desc' => 'बीबीक्यू पनीर, प्याज, शिमला मिर्च, पनीर',
                    ),
                    3 =>
                    array(
                        'name' => 'पालक, मकई और पनीर',
                        'desc' => 'कॉर्न, चीज़ बॉल्स के साथ पालक की चटनी',
                    ),
                    4 =>
                    array(
                        'name' => 'काली मिर्च पनीर',
                        'desc' => 'पनीर, शिमला मिर्च, मसालेदार टमाटर सॉस, पिज्जा मसाला',
                    ),
                    5 =>
                    array(
                        'name' => 'फ्यूजन इंडियाना',
                        'desc' => 'पास्ता, बारबेक्यू पनीर, मंचूरियन',
                    ),
                    6 =>
                    array(
                        'name' => 'स्वर्ग',
                        'desc' => 'ब्रोकोली, मक्का, जैतून, मशरूम',
                    ),
                    7 =>
                    array(
                        'name' => 'पिज्जा नापोली',
                        'desc' => 'ताजा टमाटर सॉस, मोज़ेरेला, प्याज, शिमला मिर्च, धूप में सुखाया हुआ टमाटर और जलपीनो मिर्च और जैतून के तेल में मैरीनेट किया हुआ',
                    ),
                    8 =>
                    array(
                        'name' => 'मेक्सिकाना',
                        'desc' => 'मसालेदार टमाटर की चटनी, शिमला मिर्च, रिफाइंड बीन्स, प्याज, मोज़ेरेला',
                    ),
                    9 =>
                    array(
                        'name' => 'ट्रैफ़िक जाम',
                        'desc' => 'प्याज, शिमला मिर्च, टमाटर, मक्का, बेबी कॉर्न, बेल मिर्च, जलपीनो, जैतून पिज्जा सॉस, मोज़ेरेला',
                    ),
                ),
                array(
                    0 =>
                    array(
                        'name' => 'पहाड़ी पनीर टिक्का',
                        'desc' => 'हैदराबादी टिक्का हरी मटर के चावल, सब्जियों और फ्राई के साथ परोसा जाता है, भारतीय करी के साथ सबसे ऊपर है',
                    ),
                    1 =>
                    array(
                        'name' => 'पांच मसाला',
                        'desc' => 'ब्रॉकली, बेबी कॉर्न, शिमला मिर्च, तोरी और सोया चंक्स भारतीय मसालों के साथ, फ्राई के साथ हरी मटर चावल के बिस्तर पर परोसा जाता है, थोड़ा मसालेदार',
                    ),
                    2 =>
                    array(
                        'name' => 'चेलो कबाब',
                        'desc' => 'तरह-तरह की सब्जियां, केसर चावल के ऊपर भुनी हुई प्याज़ की ग्रेवी के साथ कबाब और फ्राई परोसा जाता है',
                    ),
                    3 =>
                    array(
                        'name' => 'जलती हुई ताज	 ',
                        'desc' => 'तंदूरी मिश्रित कबाब हरी मटर राइस टॉप के बिस्तर पर मखनी ग्रेवी के साथ फ्राइज़ और सब्जियों के साथ',
                    ),
                ),
                array(
                    0 =>
                    array(
                        'name' => 'सब्जी लहसुन बॉल्स',
                        'desc' => 'मसालेदार सिचुआन सॉस में फेंके गए वेजिटेबल बॉल्स को तले हुए चावल के बिस्तर पर लहसुन की चटनी, सब्जियों और फ्राई के साथ परोसा जाता है',
                    ),
                    1 =>
                    array(
                        'name' => 'चाउ चाउ	 ',
                        'desc' => 'बेबी पोटैटो और मंचूरियन बॉल्स को मसालेदार सिचुआन सॉस में डाला जाता है जिसे नूडल्स और फ्राइड राइस के साथ गार्लिक सॉस, सब्जियों और फ्राई के साथ परोसा जाता है',
                    ),
                    2 =>
                    array(
                        'name' => 'बार बी क्यू',
                        'desc' => 'बार बी क्यू सॉस में फेंके गए पनीर और सब्जी के क्यूब्स को लहसुन की चटनी, सब्जियों और फ्राई के साथ तले हुए चावल के बिस्तर पर परोसा जाता है',
                    ),
                    3 =>
                    array(
                        'name' => 'पनीर मिर्च',
                        'desc' => 'पनीर मिर्च तली हुई चावल के बिस्तर पर विभिन्न सब्जियों, फ्राई और सॉस के साथ शीर्ष पर परोसा जाता है',
                    ),
                ),
                array(
                    0 =>
                    array(
                        'name' => 'एक्सोटिका',
                        'desc' => 'मसाले के विकल्प के साथ फेंकी हुई विदेशी सब्जियां - काली मिर्च, सीताफल, मीठी मिर्च, बीबीक्यू, पेरी पेरी। ',
                    ),
                    1 =>
                    array(
                        'name' => 'कॉर्न चीज़ कटलेट सिज़लर',
                        'desc' => 'मिश्रित सब्जियों के साथ तले हुए चावल के बिस्तर पर मकई पनीर कटलेट, काली मिर्च और इतालवी सॉस, मैश किए हुए आलू और पनीर के साथ शीर्ष पर',
                    ),
                    2 =>
                    array(
                        'name' => 'मैक्सिकन शेरिफ',
                        'desc' => 'मसालेदार टमाटर साल्सा में बेक्ड बीन्स, किडनी बीन्स, मकई और जालपीनो मैक्सिकन चावल पर मिश्रित सब्जियों के साथ परोसा जाता है, टॉर्टिला चिप्स के साथ सबसे ऊपर है',
                    ),
                    3 =>
                    array(
                        'name' => 'पास्ता एन पास्ता',
                        'desc' => 'दो तरह के पास्ता को दो अलग-अलग सॉस में मिलाया जाता है, मसले हुए आलू और गार्लिक ब्रेड के साथ परोसा जाता है',
                    ),
                    4 =>
                    array(
                        'name' => 'सब्जी स्टेक',
                        'desc' => 'मसालेदार टमाटर पिकान सॉस, गार्लिक सॉस, पेपर सॉस के साथ तली हुई सब्जी और सुगंधित चावल वेज कटलेट के साथ परोसे',
                    ),
                    5 =>
                    array(
                        'name' => 'पेरी पेरी स्क्वायर  ',
                        'desc' => 'पनीर और शिमला मिर्च के टुकड़ो को मसालेदार पेरी पेरी सॉस में मिला कर तले हुए चावल के बिस्तर पर विभिन्न सब्जियों और फ्राई के साथ परोसा जाता है',
                    ),
                    6 =>
                    array(
                        'name' => 'पनीर उपग्रह',
                        'desc' => 'तले हुए चावल के बिस्तर पर पनीर, बेबीकॉर्न और मशरूम काली मिर्च और इतालवी सॉस, पनीर, कटलेट और फ्राइज़ के साथ शीर्ष पर',
                    ),
                    7 =>
                    array(
                        'name' => 'जमैका जेर्को',
                        'desc' => 'कैरेबियन फ्लेवर ग्रिल्ड कॉटेज पनीर, बेल मिर्च और मसाले सुगंधित चावल के बिस्तर पर, ग्रिल्ड अनानास जर्क सॉस के साथ सबसे ऊपर है',
                    ),
                ),
                array(
                    0 =>
                    array(
                        'name' => 'दाल फ्राई',
                        'desc' => NULL,
                    ),
                    1 =>
                    array(
                        'name' => 'दाल तड़केवाली',
                        'desc' => NULL,
                    ),
                    2 =>
                    array(
                        'name' => 'दाल मखनी',
                        'desc' => NULL,
                    ),
                    3 =>
                    array(
                        'name' => 'पकोड़ेवाली दही कडी',
                        'desc' => NULL,
                    ),
                    4 =>
                    array(
                        'name' => 'चना मसाला',
                        'desc' => 'काबुली चना लाल ग्रेवी में पकाया जाता है, मसालेदार',
                    ),
                    5 =>
                    array(
                        'name' => 'कड़ाही छोले',
                        'desc' => 'काबुली चना लाल ग्रेवी में मसालेदार कड़ाही मसाला में पकाया जाता है',
                    ),
                    6 =>
                    array(
                        'name' => 'जीरा आलू',
                        'desc' => 'जीरा और धनिया के साथ पका हुआ आलू',
                    ),
                    7 =>
                    array(
                        'name' => 'लसूनी आलू भिंडी',
                        'desc' => 'आलू, भिंडी लसूनी गाढ़ी ब्राउन ग्रेवी के साथ पकाई गई, अर्ध सूखी',
                    ),
                    8 =>
                    array(
                        'name' => 'दम आलू कश्मीरी',
                        'desc' => 'मसालेदार कश्मीरी लाल ग्रेवी में पका हुआ आलू',
                    ),
                    9 =>
                    array(
                        'name' => 'आलू चटनी वाला',
                        'desc' => 'चटपटा पालक ग्रेवी में आलू पकता है',
                    ),
                    10 =>
                    array(
                        'name' => 'भिंडी मसाला',
                        'desc' => 'भिन्डी गाढ़ी लाल ग्रेवी में पकी हुई, आधी सूखी',
                    ),
                    11 =>
                    array(
                        'name' => 'पलक लसूनी',
                        'desc' => 'पालक लहसुन के साथ पकाया जाता है, लहसुन तड़का के साथ परोसा जाता है',
                    ),
                    12 =>
                    array(
                        'name' => 'सब्जी बेमिसाल',
                        'desc' => 'हल्की पीली ग्रेवी में वेज मिलाएं',
                    ),
                    13 =>
                    array(
                        'name' => 'सब्ज़ी मिर्च मिली',
                        'desc' => 'तीखी लाल ग्रेवी के साथ परोसे गए चटपटे वेजिटेबल पकोड़े',
                    ),
                    14 =>
                    array(
                        'name' => 'सब्जी देसी तवा',
                        'desc' => 'मिक्स वेज पीली तवा ग्रेवी में पकाई हुई, मीडियम स्पाइसी',
                    ),
                    15 =>
                    array(
                        'name' => 'सब्जी हरा धनिया',
                        'desc' => 'हरा धनिया में पकी हुई सब्जी को हल्की हरी ग्रेवी, मीडियम सोयासी में मिलाएं',
                    ),
                    16 =>
                    array(
                        'name' => 'मेथी मटर मलाई',
                        'desc' => 'मेथी के पत्ते और मटर काजू की ग्रेवी में पकाया जाता है, हल्का मीठा',
                    ),
                    17 =>
                    array(
                        'name' => 'सब्जी अमरावती',
                        'desc' => 'लाल ग्रेवी पर सब्जी को करी पत्ता और राय तड़का के साथ मिलाएं, मसालेदार',
                    ),
                    18 =>
                    array(
                        'name' => 'सब्जी देहाती मसाला',
                        'desc' => 'कटी हुई पालक और मेथी के पत्ते, मसालेदार हरी ग्रेवी, अर्ध सूखी सब्जी के साथ मिलाएं',
                    ),
                    19 =>
                    array(
                        'name' => 'सब्जी दीवानी हांडी',
                        'desc' => 'मटर, मकई, मशरूम पालक की ग्रेवी में पकाया जाता है, मध्यम मसालेदार',
                    ),
                    20 =>
                    array(
                        'name' => 'सब्जी कड़ाही',
                        'desc' => 'मसालेदार कड़ाही लाल ग्रेवी में पकाई हुई सब्जियाँ मिलाएँ',
                    ),
                    21 =>
                    array(
                        'name' => 'सब्जी खीमा कोल्हापुरी	 ',
                        'desc' => 'सब्जी खीमा कोल्हापुरी	',
                    ),
                    22 =>
                    array(
                        'name' => 'सब्जी मखनी',
                        'desc' => 'टमाटर और काजू की ग्रेवी में पकाई हुई सब्जी मिक्स करें, हल्का मीठा',
                    ),
                    23 =>
                    array(
                        'name' => 'सब्जी नवरत्न कोरमा',
                        'desc' => 'मिक्स वेज, फ्रूट और ड्राई फ्रूट काजू ग्रेवी में पका हुआ, मीठा',
                    ),
                    24 =>
                    array(
                        'name' => 'सब्जी चोप मसाला',
                        'desc' => 'अंत में पीली ग्रेवी में पकी हुई कटी हुई सब्जी, हल्का स्वाद',
                    ),
                    25 =>
                    array(
                        'name' => 'सब्जी हरियाली',
                        'desc' => 'मिक्स वेज और क्यूब पनीर, हरी ग्रेवी में पका हुआ, मध्यम मसालेदार',
                    ),
                    26 =>
                    array(
                        'name' => 'सब्जी रोगानी',
                        'desc' => 'मसालेदार रोगनी ग्रेवी में पकाई हुई सब्जियां मिलाएं',
                    ),
                    27 =>
                    array(
                        'name' => 'सब्जी तवा लाजबाब',
                        'desc' => 'सब्जी तवा लाजबाब',
                    ),
                    28 =>
                    array(
                        'name' => 'सब्जी लखनऊ',
                        'desc' => 'प्याज, शिमला मिर्च, बेबी कॉर्न, मसालेदार लाल लखनवी ग्रेवी में पकाई सब्जी',
                    ),
                    29 =>
                    array(
                        'name' => 'सब्जी लाहौरी कोफ्ता',
                        'desc' => 'पालक ग्रेवी में पकाए गए वेज कोफ्ते',
                    ),
                    30 =>
                    array(
                        'name' => 'पनीर कोफ्ता',
                        'desc' => 'पनीर कोफ्ता पीली ग्रेवी के साथ परोसा जाता है, मध्यम मसालेदार',
                    ),
                    31 =>
                    array(
                        'name' => 'सागवाला पनीर कोफ्ता',
                        'desc' => 'पनीर कोफ्ता हल्की पालक ग्रेवी के साथ परोसा जाता है',
                    ),
                    32 =>
                    array(
                        'name' => 'तिरंगी मिर्च मशरूम	 ',
                        'desc' => 'मसालेदार लाल ग्रेवी में पकाई गई मशरूम, शिमला मिर्च, शिमला मिर्च  ',
                    ),
                    33 =>
                    array(
                        'name' => 'ढींगरी दनिया कोरमा',
                        'desc' => 'धनिये के स्वाद वाली हरी ग्रेवी में पका हुआ मशरूम  ',
                    ),
                    34 =>
                    array(
                        'name' => 'पालक पनीर',
                        'desc' => 'पालक की ग्रेवी में पका हुआ पनीर',
                    ),
                    35 =>
                    array(
                        'name' => 'पनीर मखनी',
                        'desc' => 'पनीर क्यूब टमाटर और काजू ग्रेवी में पकाया जाता है, हल्का मीठा',
                    ),
                    36 =>
                    array(
                        'name' => 'पनीर लज्जतदार',
                        'desc' => 'शिमला मिर्च के जुलिएन के साथ पीली ग्रेवी में पका हुआ फिंगर कट पनीर, हल्का',
                    ),
                    37 =>
                    array(
                        'name' => 'पनीर पटियाला शाही',
                        'desc' => 'फिंगर कट पनीर लाल पटियाला ग्रेवी में पकाया जाता है, मध्यम मसालेदार',
                    ),
                    38 =>
                    array(
                        'name' => 'पनीर देसी हांडी',
                        'desc' => 'पनीर क्यूब, प्याज, टमाटर और शिमला मिर्च लाल ग्रेवी में पका हुआ, मध्यम मसालेदार',
                    ),
                    39 =>
                    array(
                        'name' => 'पनीर कड़ाही',
                        'desc' => 'पनीर क्यूब मसालेदार लाल कढाई ग्रेवी में पकाया जाता है',
                    ),
                    40 =>
                    array(
                        'name' => 'अमृतसरी पनीर भुर्जी',
                        'desc' => 'पारंपरिक पनीर भुर्जी, मध्यम मसालेदार',
                    ),
                    41 =>
                    array(
                        'name' => 'भुने पनीर का साला',
                        'desc' => 'फिंगर कट पनीर ब्राउन ग्रेवी में तले हुए प्याज के साथ पकाया जाता है, मसालेदार',
                    ),
                    42 =>
                    array(
                        'name' => 'पनीर मक्खन मसाला',
                        'desc' => 'पनीर क्यूब को पीली ग्रेवी में मक्खन के साथ पकाया गया, हल्का',
                    ),
                    43 =>
                    array(
                        'name' => 'पनीर हैदराबादी मसाला',
                        'desc' => 'पनीर टिक्का मसालेदार हैदराबादी ब्राउन ग्रेवी के साथ पकाया जाता है',
                    ),
                    44 =>
                    array(
                        'name' => 'पनीर लखनवी तवा',
                        'desc' => 'पनीर क्यूब, प्याज और शिमला मिर्च लाल ग्रेवी में लखनवी मसाला के साथ पकाया जाता है, मसालेदार',
                    ),
                    45 =>
                    array(
                        'name' => 'पनीर टिक्का मसाला',
                        'desc' => 'पनीर टिक्का, प्याज, टमाटर और शिमला मिर्च मसालेदार लाल ग्रेवी में पकाया जाता है',
                    ),
                    46 =>
                    array(
                        'name' => 'पनीर खुरचान',
                        'desc' => 'कटा हुआ पनीर लाल ग्रेवी में करी पत्ता और राय तड़का के साथ पकाया जाता है, मध्यम मसालेदार',
                    ),
                    47 =>
                    array(
                        'name' => 'पनीर लसूनी',
                        'desc' => 'पनीर क्यूब पीली ग्रेवी में बहुत सारे लहसुन के साथ पकाया जाता है, मध्यम मसालेदार',
                    ),
                    48 =>
                    array(
                        'name' => 'पनीर खड़ा मसाला',
                        'desc' => 'पनीर क्यूब को तेजपत्ता, मक्खन और लहसुन के साथ पीली ग्रेवी में पकाया जाता है, मसालेदार',
                    ),
                ),
                array(
                    0 =>
                    array(
                        'name' => 'सब्ज़ी मेलोनी',
                        'desc' => 'खास देसी तड़के के साथ टमाटर और काजू की ग्रेवी में पकाई गई विदेशी सब्जी और मिक्स वेज।  ',
                    ),
                    1 =>
                    array(
                        'name' => 'राजवाड़ी हांडी',
                        'desc' => 'राजवाड़ी मसाले के साथ प्याज और टमाटर की ग्रेवी में पकाए गए सब्जी, मक्का, काजू',
                    ),
                    2 =>
                    array(
                        'name' => 'सब्ज़ियों का मेला',
                        'desc' => 'सब्जी, आलू, बेबी कॉर्न, चेरी टमाटर प्याज की ग्रेवी में काजू की ग्रेवी के स्पर्श के साथ पकाया जाता है।',
                    ),
                    3 =>
                    array(
                        'name' => 'सब्जी पेशावरी',
                        'desc' => 'मसालेदार टमाटर प्याज की ग्रेवी में पकी हुई कटी सब्जियां',
                    ),
                    4 =>
                    array(
                        'name' => 'सब्जी बेगम बहारी',
                        'desc' => 'एक ही थाली में दो का संयोजन',
                    ),
                    5 =>
                    array(
                        'name' => 'कबाब मसाला',
                        'desc' => 'मैश किए हुए आलू और पनीर से बना शेख कबाब सफेद और प्याज की ग्रेवी के साथ पकाया जाता है  ',
                    ),
                    6 =>
                    array(
                        'name' => 'पनीर पहाड़ी मसाला',
                        'desc' => 'भुना हुआ पनीर स्पेशल पहाड़ी मसाला ग्रेवी में पकाया जाता है',
                    ),
                    7 =>
                    array(
                        'name' => 'पनीर के शोले	 ',
                        'desc' => 'टमाटर और काजू की ग्रेवी में तंदूरी मसाले के साथ पका हुआ डाइमंड कटा हुआ मैरीनेट किया हुआ पनीर',
                    ),
                    8 =>
                    array(
                        'name' => 'पनीर अमृतसरी	 ',
                        'desc' => 'क्यूब्स और कद्दूकस किया हुआ पनीर टमाटर और प्याज की ग्रेवी में पकाया जाता है',
                    ),
                    9 =>
                    array(
                        'name' => 'पनीर दम लबबदार',
                        'desc' => 'टमाटर और काजू की ग्रेवी में पका हुआ ग्रिल्ड पनीर फिंगर  ',
                    ),
                    10 =>
                    array(
                        'name' => 'पनीर मखमली',
                        'desc' => 'स्मूद रिच रेड ग्रेवी में पका हुआ छोटा डायमंड कट पनीर  ',
                    ),
                    11 =>
                    array(
                        'name' => 'राजवाड़ी पनीर टिक्का मसाला',
                        'desc' => 'तंदूरी पनीर टिक्का राजवाड़ी मसाला, प्याज और टमाटर की ग्रेवी के साथ पकाया जाता है  ',
                    ),
                    12 =>
                    array(
                        'name' => 'नवाबी काजू पनीर',
                        'desc' => 'तली हुई काजू और पनीर मसालेदार पीली ग्रेवी के साथ पकाया जाता है, मक्खन और क्रीम से सना हुआ',
                    ),
                    13 =>
                    array(
                        'name' => 'काजू मसाला',
                        'desc' => 'तले हुए काजू को टमाटर और प्याज़ की ग्रेवी में मसाले के साथ पकाया जाता है.',
                    ),
                    14 =>
                    array(
                        'name' => 'पनीर लाबाबी',
                        'desc' => 'मसालेदार टमाटर की ग्रेवी में पका हुआ पनीर के टुकड़े  ',
                    ),
                ),
                array(
                    0 =>
                    array(
                        'name' => 'रोटी',
                        'desc' => NULL,
                    ),
                    1 =>
                    array(
                        'name' => 'कुलचा',
                        'desc' => NULL,
                    ),
                    2 =>
                    array(
                        'name' => 'पराठा',
                        'desc' => NULL,
                    ),
                    3 =>
                    array(
                        'name' => 'मेथी रोटी',
                        'desc' => NULL,
                    ),
                    4 =>
                    array(
                        'name' => 'पुदीना रोटी',
                        'desc' => NULL,
                    ),
                    5 =>
                    array(
                        'name' => 'पालक रोटी',
                        'desc' => NULL,
                    ),
                    6 =>
                    array(
                        'name' => 'लहसुन की रोटी',
                        'desc' => NULL,
                    ),
                    7 =>
                    array(
                        'name' => 'मिस्सी रोटी',
                        'desc' => NULL,
                    ),
                    8 =>
                    array(
                        'name' => 'नान',
                        'desc' => NULL,
                    ),
                    9 =>
                    array(
                        'name' => 'रोगानी नान',
                        'desc' => NULL,
                    ),
                    10 =>
                    array(
                        'name' => 'बटर रूमाली रोटी',
                        'desc' => NULL,
                    ),
                    11 =>
                    array(
                        'name' => 'कश्मीरी नानी',
                        'desc' => NULL,
                    ),
                    12 =>
                    array(
                        'name' => 'लहसुन नान',
                        'desc' => NULL,
                    ),
                    13 =>
                    array(
                        'name' => 'मसाला कुलचा',
                        'desc' => NULL,
                    ),
                    14 =>
                    array(
                        'name' => 'भरवां पराठा',
                        'desc' => NULL,
                    ),
                    15 =>
                    array(
                        'name' => 'पनीर नानो',
                        'desc' => NULL,
                    ),
                    16 =>
                    array(
                        'name' => 'रोटी की टोकरी',
                        'desc' => NULL,
                    ),
                ),
                array(
                    0 =>
                    array(
                        'name' => 'भाप चावल',
                        'desc' => NULL,
                    ),
                    1 =>
                    array(
                        'name' => 'जीरा राइस',
                        'desc' => NULL,
                    ),
                    2 =>
                    array(
                        'name' => 'घी भाटी	 ',
                        'desc' => NULL,
                    ),
                    3 =>
                    array(
                        'name' => 'हरी मटर चावल',
                        'desc' => NULL,
                    ),
                    4 =>
                    array(
                        'name' => 'दही चावल',
                        'desc' => NULL,
                    ),
                    5 =>
                    array(
                        'name' => 'दाल खिचड़ी',
                        'desc' => NULL,
                    ),
                    6 =>
                    array(
                        'name' => 'लहसुन के तड़के के साथ दाल खिचड़ी',
                        'desc' => NULL,
                    ),
                    7 =>
                    array(
                        'name' => 'पलक खिचड़ी',
                        'desc' => NULL,
                    ),
                    8 =>
                    array(
                        'name' => 'सब्जी मसाला खिचड़ी',
                        'desc' => NULL,
                    ),
                    9 =>
                    array(
                        'name' => 'सब्जी पुलाव',
                        'desc' => NULL,
                    ),
                    10 =>
                    array(
                        'name' => 'पनीर मसाला पुलाव',
                        'desc' => NULL,
                    ),
                    11 =>
                    array(
                        'name' => 'कश्मीरी पुलाव',
                        'desc' => NULL,
                    ),
                    12 =>
                    array(
                        'name' => 'सब्जी बिरयानी',
                        'desc' => NULL,
                    ),
                    13 =>
                    array(
                        'name' => 'हैदराबादी बिरयानी  ',
                        'desc' => NULL,
                    ),
                    14 =>
                    array(
                        'name' => 'पनीर टिक्का बिरयानी',
                        'desc' => NULL,
                    ),
                ),
                array(
                    0 =>
                    array(
                        'name' => 'हांगकांग सॉस में सब्जियां',
                        'desc' => NULL,
                    ),
                    1 =>
                    array(
                        'name' => 'मीठी और खट्टी चटनी में सब्जियां',
                        'desc' => NULL,
                    ),
                    2 =>
                    array(
                        'name' => 'थाई हरी करी में सब्जियां',
                        'desc' => NULL,
                    ),
                    3 =>
                    array(
                        'name' => 'सिचुआन काली मिर्च सॉस में सब्जियां',
                        'desc' => NULL,
                    ),
                    4 =>
                    array(
                        'name' => 'गर्म लहसुन की चटनी में सब्जियां',
                        'desc' => NULL,
                    ),
                    5 =>
                    array(
                        'name' => 'लाल थाई करी में पनीर 	 ',
                        'desc' => NULL,
                    ),
                    6 =>
                    array(
                        'name' => 'हुनान सॉस में पनीर  ',
                        'desc' => NULL,
                    ),
                    7 =>
                    array(
                        'name' => 'गरमा गरम लहसुन की चटनी में पनीर  ',
                        'desc' => NULL,
                    ),
                ),
                array(
                    0 =>
                    array(
                        'name' => 'सब्जी तला हुआ चावल',
                        'desc' => NULL,
                    ),
                    1 =>
                    array(
                        'name' => 'जले हुए लहसुन चावल',
                        'desc' => NULL,
                    ),
                    2 =>
                    array(
                        'name' => 'सिचुआन चावल',
                        'desc' => NULL,
                    ),
                    3 =>
                    array(
                        'name' => 'नींबू अदरक चावल',
                        'desc' => NULL,
                    ),
                    4 =>
                    array(
                        'name' => 'तुलसी चावल',
                        'desc' => NULL,
                    ),
                    5 =>
                    array(
                        'name' => 'मसालेदार थाई चावल',
                        'desc' => NULL,
                    ),
                    6 =>
                    array(
                        'name' => 'फॉर्च्यून फ्राइड राइस',
                        'desc' => NULL,
                    ),
                    7 =>
                    array(
                        'name' => 'मशरूम फ्राइड राइस',
                        'desc' => NULL,
                    ),
                    8 =>
                    array(
                        'name' => 'गार्डन स्किललेट वेज के साथ सीलेंट्रो राइस',
                        'desc' => NULL,
                    ),
                    9 =>
                    array(
                        'name' => 'सब्जी फू ची चावल',
                        'desc' => NULL,
                    ),
                    10 =>
                    array(
                        'name' => 'ट्रिपल सिचुआन फ्राइड राइस',
                        'desc' => NULL,
                    ),
                ),
                array(
                    0 =>
                    array(
                        'name' => 'हक्का नूडल्स',
                        'desc' => NULL,
                    ),
                    1 =>
                    array(
                        'name' => 'मिर्च लहसुन नूडल्स',
                        'desc' => NULL,
                    ),
                    2 =>
                    array(
                        'name' => 'सिचुआन नूडल्स',
                        'desc' => NULL,
                    ),
                    3 =>
                    array(
                        'name' => 'सिंगापुर नूडल्स',
                        'desc' => NULL,
                    ),
                    4 =>
                    array(
                        'name' => 'मसालेदार तुलसी नूडल्स',
                        'desc' => NULL,
                    ),
                    5 =>
                    array(
                        'name' => 'मंथाई नूडल्स',
                        'desc' => NULL,
                    ),
                    6 =>
                    array(
                        'name' => 'पॉट नूडल्स',
                        'desc' => NULL,
                    ),
                    7 =>
                    array(
                        'name' => 'सिज़लिंग नूडल्स',
                        'desc' => NULL,
                    ),
                    8 =>
                    array(
                        'name' => 'सेसमी मूंगफली नूडल्स',
                        'desc' => NULL,
                    ),
                    9 =>
                    array(
                        'name' => 'अमेरिकी चॉपसुई',
                        'desc' => NULL,
                    ),
                ),
                array(
                    0 =>
                    array(
                        'name' => 'मैक्सिकन चिली बीन',
                        'desc' => 'मकई, राजमा और सब्जियों के साथ चावल फेंके',
                    ),
                    1 =>
                    array(
                        'name' => 'पिमेंटो',
                        'desc' => 'बेल मिर्च, परमेसन चीज़ और अजमोद के साथ चावल फेंके',
                    ),
                    2 =>
                    array(
                        'name' => 'पेप्परड अमेरिकन राइस',
                        'desc' => 'बीन्स, मकई, शिमला मिर्च, मिर्च और मसालों के साथ चावल फेंके',
                    ),
                    3 =>
                    array(
                        'name' => 'इंडोनेशियाई चावल',
                        'desc' => 'चावल को जड़ी-बूटियों, नींबू, प्याज, तुलसी, सब्जियों और मसालों से सजाया गया है',
                    ),
                    4 =>
                    array(
                        'name' => 'लेबनीज बिरयानी',
                        'desc' => NULL,
                    ),
                ),
                array(
                    0 =>
                    array(
                        'name' => 'क्रीम का चुनाव',
                        'desc' => 'वेनिला / चॉकलेट / स्ट्रॉबेरी / बटरस्कॉच / केसर पिस्ता',
                    ),
                    1 =>
                    array(
                        'name' => 'गुलाब जामुन',
                        'desc' => NULL,
                    ),
                    2 =>
                    array(
                        'name' => 'वेनिला आइसक्रीम के साथ गुलाब जामुन',
                        'desc' => NULL,
                    ),
                    3 =>
                    array(
                        'name' => 'हॉट चॉकलेट सॉस के साथ वेनिला आइसक्रीम',
                        'desc' => NULL,
                    ),
                    4 =>
                    array(
                        'name' => 'गर्म ठगना नट सुन्डे',
                        'desc' => 'वेनिला और चॉकलेट आइसक्रीम, हॉट चॉकलेट सॉस और नट्स',
                    ),
                    5 =>
                    array(
                        'name' => 'सिज़लिंग ब्राउनी',
                        'desc' => 'वनीला आइसक्रीम और हॉट चॉकलेट सॉस के साथ ब्राउनी सबसे ऊपर है',
                    ),
                    6 =>
                    array(
                        'name' => 'एफिल टॉवर',
                        'desc' => 'फ्रूट क्रश और चॉकलेट नट्स के साथ अलग-अलग आइसक्रीम से बना टावर',
                    ),
                    7 =>
                    array(
                        'name' => 'बनाना स्प्लिट',
                        'desc' => 'आइसक्रीम के तीन डिफ़्रंट स्कूप के साथ ताज़ा कटे केले के साथ कारमेल और क्रश और चॉकलेट नट्स   ',
                    ),
                    8 =>
                    array(
                        'name' => 'चॉकलेट से मौत',
                        'desc' => 'रिच चॉकलेट केक चॉकलेट आइसक्रीम, चॉकलेट सॉस, चोको चिप्स के साथ सबसे ऊपर है',
                    ),
                    9 =>
                    array(
                        'name' => 'कीचड़ का ढेला',
                        'desc' => 'क्लासिक हॉट चॉकलेट पाई को वनीला आइसक्रीम के साथ परोसा गया',
                    ),
                    10 =>
                    array(
                        'name' => 'चॉकलेट के शौक़ीन',
                        'desc' => 'चॉकलेट फोंड्यू को चोकोस्टिक्स, वेफर बिस्कुट, ब्राउनी और मिश्रित मौसमी ताजे फलों के साथ परोसा जाता है।',
                    ),
                ),
                array(
                    0 =>
                    array(
                        'name' => 'शुद्ध पानी',
                        'desc' => NULL,
                    ),
                    1 =>
                    array(
                        'name' => 'वातित पेय',
                        'desc' => NULL,
                    ),
                    2 =>
                    array(
                        'name' => 'निम्बू पानी / सोडा',
                        'desc' => NULL,
                    ),
                    3 =>
                    array(
                        'name' => 'कोकम शर्बत',
                        'desc' => NULL,
                    ),
                    4 =>
                    array(
                        'name' => 'चासो',
                        'desc' => NULL,
                    ),
                    5 =>
                    array(
                        'name' => 'मसाला कोला',
                        'desc' => NULL,
                    ),
                    6 =>
                    array(
                        'name' => 'नमकीन / मीठी लस्सी',
                        'desc' => NULL,
                    ),
                    7 =>
                    array(
                        'name' => 'आइसक्रीम के साथ कोल्ड कॉफी',
                        'desc' => NULL,
                    ),
                    8 =>
                    array(
                        'name' => 'गर्म कॉफी',
                        'desc' => NULL,
                    ),
                )
            ];

            $key = ['gu' => '', 'hi' => ''];
            foreach (getAllLanguages() as $k => $v) {
                if (strtolower($v) == 'hindi') {
                    $key['hi'] = $k;
                }
                if (strtolower($v) == 'gujarati') {
                    $key['gu'] = $k;
                }
            }
            foreach ($data as $k => $d) {
                $d['lang']['hi'] = $hin[$k];
                $d['lang']['gu'] = $guj[$k];
                foreach ($d['data'] as $k1 => &$i) {
                    $i['lang_name'] = [$key['hi'] => $lang_hi[$k][$k1]['name'], $key['gu'] => $lang_gu[$k][$k1]['name']];
                    $i['lang_desc'] = [$key['hi'] => $lang_hi[$k][$k1]['desc'], $key['gu'] => $lang_gu[$k][$k1]['desc']];
                }
                $newD[] = $d;
            }



            $restaurant_id = 1;
            foreach ($newD as $cate_index => $d) {
                $inserts = [];
                foreach ($d['data'] as $inx_ => $item) {

                    $food = Food::where('name', '=', $item['name'])->update([

                        'lang_name' => $item['lang_name'],
                        'lang_description' => $item['lang_desc']
                    ]);
                }
            }
            echo "Categoiry added";
        } else {
            abort(404);
        }
    }
}
