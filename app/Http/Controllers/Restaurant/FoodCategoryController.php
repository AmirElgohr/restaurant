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

class FoodCategoryController extends Controller {

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
        if($user->hasRole('admin')){
            $params['restaurant_id'] = null;
        }else {
            $params['restaurant_id'] = $user->restaurant_id;
        }

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
        if(!auth()->user()->isRest()){
            abort(403);
        }
        return view('restaurant.food_categories.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(FoodCategoryRequest $request)
    {
        try
        {
            // dd($request->all());
            DB::beginTransaction();
            $input = $request->only('category_name', 'restaurant', 'restaurant_id', 'category_image', 'lang_category_name');
            $input['sort_order'] = FoodCategory::max('sort_order') + 1;
            FoodCategory::create($input);
            DB::commit();
            $request->session()->flash('Success', __('system.messages.saved', ['model' => __('system.food_categories.title')]));
        } catch(\Illuminate\Database\QueryException $ex)
        {
            DB::rollback();
            $request->session()->flash('Error', __('system.messages.operation_rejected'));

            return redirect()->back();
        }

        return redirect()->route('restaurant.food_categories.index');
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\FoodCategory $foodCategory
     * @return \Illuminate\Http\Response
     */
    public function show(FoodCategory $foodCategory)
    {
    }

    public function checkRestaurantIsValidFoodCategory($food_category_id, $user = null)
    {

        if(empty($user))
        {
            $user = auth()->user();
        }
        $user->load(['restaurant.food_categories' => function ($q) use ($food_category_id) {
            $q->where('id', $food_category_id);
        }]);
        if(!isset($user->restaurant) || count($user->restaurant->food_categories) == 0)
        {
            $back = request()->get('back', route('restaurant.food_categories.index'));
            request()->session()->flash('Error', __('system.messages.not_found', ['model' => __('system.food_categories.title')]));

            return $back;
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\FoodCategory $foodCategory
     * @return \Illuminate\Http\Response
     */
    public function edit(FoodCategory $foodCategory)
    {
        if(($redirect = $this->checkRestaurantIsValidFoodCategory($foodCategory->id)) != null)
        {
            return redirect($redirect);
        }

        return view('restaurant.food_categories.edit', ['foodCategory' => $foodCategory]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\FoodCategory $foodCategory
     * @return \Illuminate\Http\Response
     */
    public function update(FoodCategoryRequest $request, FoodCategory $foodCategory)
    {

        if(($redirect = $this->checkRestaurantIsValidFoodCategory($foodCategory->id)) != null)
        {
            return redirect($redirect);
        }
        $input = $request->only('category_name', 'category_image', 'lang_category_name');
        $foodCategory->fill($input)->save();

        $request->session()->flash('Success', __('system.messages.updated', ['model' => __('system.food_categories.title')]));
        if($request->back)
        {
            return redirect($request->back);
        }

        return redirect(route('restaurant.food_categories.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\FoodCategory $foodCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(FoodCategory $foodCategory)
    {
        $request = request();
        if(($redirect = $this->checkRestaurantIsValidFoodCategory($foodCategory->id)) != null)
        {
            return redirect($redirect);
        }
        $foodCategory->load('foods');
        $foods = $foodCategory->foods;
        $foodCategory->delete();
        $foods->loadCount('food_categories');
        foreach($foods as $food)
        {
            if($food->food_categories_count == 0)
            {
                $food->delete();
            }
        }
        $request->session()->flash('Success', __('system.messages.deleted', ['model' => __('system.food_categories.title')]));
        if($request->back)
        {
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

        if($user->isAdmin()){
            $food_categories = FoodCategory::all()->pluck('category_name', 'id');
        }else{
            $user->load(['restaurant.food_categories' => function ($q) {
                $q->orderBy('category_name', 'asc');
            }]);
            $food_categories = $user->restaurant->food_categories->mapWithKeys(function ($food_category, $key) {
                return [$food_category->id => $food_category->category_name];
            });
        }


        return ['' => __('system.fields.select_Category')] + $food_categories->toarray();
    }
}
