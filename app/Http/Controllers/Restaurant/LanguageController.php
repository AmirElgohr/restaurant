<?php

namespace App\Http\Controllers\Restaurant;

use App\Exports\LanguageDataSampleSheet;
use App\Models\User;
use App\Models\Language;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Spatie\Searchable\Search;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Lang;
use App\Repositories\Restaurant\FoodRepository;
use App\Http\Requests\Restaurant\LanguageRequest;
use App\Repositories\Restaurant\LanguageRepository;
use App\Repositories\Restaurant\FoodCategoryRepository;

class LanguageController extends Controller
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

        $params = $request->only('par_page', 'sort', 'direction', 'filter');
        $par_page = 10;
        if (in_array($request->par_page, [10, 25, 50, 100])) {
            $par_page = $request->par_page;
        }
        $params['par_page'] = $par_page;
        $languages = (new LanguageRepository)->getAllLanguagesData($params);
        return view('restaurant.languages.index', ['languages' => $languages]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('restaurant.languages.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreLanguageRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LanguageRequest $request)
    {
        $input = $request->only('name');
        DB::beginTransaction();
        $input['store_location_name'] = generateLanguageStoreDirName($input['name']);

        $language = Language::create($input);
        $path = lang_path('en');
        \File::copyDirectory($path, lang_path($input['store_location_name']));

        $request->session()->flash('Success', __('system.messages.saved', ['model' => __('system.languages.title')]));

        DB::commit();
        return redirect(route('restaurant.languages.edit', ['language' => $language->id]));

        // return redirect()->route('restaurant.languages.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Language  $language
     * @return \Illuminate\Http\Response
     */
    public function show(Language $language)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Language  $language
     * @return \Illuminate\Http\Response
     */
    public function edit(Language $language)
    {

        $request = request();
        $user = $request->user();
        $files = getAllLanguagesFiles();
        $file = current(array_flip($files));

        if (in_array($request->file, array_keys($files))) {
            $file = $request->file;
        }

        if (in_array($file, array_keys(getDynamicDataTables()))) {
            $params['restaurant_id'] = $user->restaurant_id;
            if ($file == "foods") {
                $foods = (new FoodRepository())->getUserRestaurantFoods($params);
                // dd($foods->first());
                $other = $english = [];
                foreach ($foods as $food) {
                    $english[$food->id] = [
                        "name" => $food->name,
                        "description" => $food->description,
                    ];
                    if ($language->store_location_name == 'en') {
                        $other[$food->id] = $english[$food->id];
                    } else {
                        $other[$food->id] = [
                            "name" => $food->lang_name[$language->store_location_name] ?? "",
                            "description" => $food->lang_description[$language->store_location_name] ?? "",
                        ];
                    }
                }
                $other =  Arr::dot($other);
                $english = Arr::dot($english);
                $names = [];
                // dd(Arr::dot($english));
                // $other = $foodCategories->pluck('lang_category_name.' . $language->store_location_name, 'id');
            } else {
                $foodCategories = (new FoodCategoryRepository)->getRestaurantFoodCategories($params);
                $english = $foodCategories->pluck('category_name', 'id');
                if ($language->store_location_name == 'en') {
                    $other = $english;
                } else {
                    $other = $foodCategories->pluck('lang_category_name.' . $language->store_location_name, 'id');
                }
            }
        } else {
            $english = getFileAllLanguagesData($file);
            if ($language->store_location_name == 'en') {
                $other = $english;
            } else {
                $other = getFileAllLanguagesData($file, $language->store_location_name);
            }
        }
        return view('restaurant.languages.languages-data.edit', ['language' => $language, 'english' => $english, 'other' => $other, 'file' => $file]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateLanguageRequest  $request
     * @param  \App\Models\Language  $language
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Language $language)
    {

        $files = getAllLanguagesFiles();
        $user = $request->user();
        if (!in_array($request->file, array_keys($files))) {
            request()->session()->flash('Error', __('system.messages.not_found', ['model' => __('system.languages.title')]));
        }


        if (in_array($request->file, array_keys(getDynamicDataTables()))) {
            $params['restaurant_id'] = $user->restaurant_id;
            if ($request->file == "foods") {
                $foods = (new FoodRepository())->getUserRestaurantFoods($params);
                // dd($foods->first());

                foreach ($foods as $food) {
                    $update_data = $request->other[$food->id] ?? [];
                    if (!empty($update_data)) {
                        $update = [];
                        if ($language->store_location_name  == 'en') {
                            $food->name = $update_data['name'];
                            $food->description = $update_data['description'];
                        } else {
                            // $food->lang_name[$language->store_location_name] = $update_data['name'];
                            $lang_name = $food->lang_name;
                            $lang_name[$language->store_location_name] = $update_data['name'];
                            $food->lang_name = $lang_name;

                            $lang_description = $food->lang_description;
                            $lang_description[$language->store_location_name] = $update_data['description'];
                            $food->lang_description = $lang_description;
                        }
                        $food->save();
                    }
                }
            } else {
                $foodCategories = (new FoodCategoryRepository)->getRestaurantFoodCategories($params);
                foreach ($foodCategories as $category) {
                    $update_data = $request->other[$category->id] ?? [];
                    if ($language->store_location_name == 'en') {
                        $category->category_name = $update_data;
                    } else {
                        $lang_name = $category->lang_category_name;
                        $lang_name[$language->store_location_name] = $update_data;
                        $category->lang_category_name = $lang_name;
                    }
                    $category->save();
                }
            }
        } else {

            $locations = $language->store_location_name;
            $filePath = lang_path($locations);
            File::isDirectory($filePath) or File::makeDirectory($filePath, 0777, true, true);
            File::put($filePath . "/" . $request->file . ".php", arrayToFileString($request->other));
        }

        $request->session()->flash('Success', __('system.messages.saved', ['model' => __('system.languages_data.title')]));

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Language  $language
     * @return \Illuminate\Http\Response
     */
    public function defaultLanguage($language)
    {
        $request = request();

        $language = Language::where('store_location_name', $language)->first();
        if (!$language) {
            request()->session()->flash('Error', __('system.messages.not_found', ['model' => __('system.languages.title')]));

            return redirect()->back();
        }

        $user = $request->user();
        if ($user) {
            $user->language = $language->store_location_name;
            $user->save();
            app()->setLocale($language->store_location_name);
        }
        session()->put('locale', $language->store_location_name);
        $request->session()->flash('Success', __('system.messages.change_success_message', ['model' => __('system.languages.title')]));

        return redirect()->back();
    }


    public function destroy(Language $language)
    {

        $request = request();
        if (strtolower($language->name) == 'english' || $language->store_location_name == config('app.app_locale')) {
            request()->session()->flash('Error', __('system.languages.can_not_remove'));
            return redirect()->back();
        }
        $path = lang_path($language->store_location_name);
        File::deleteDirectory($path);
        $language->delete();
        $request->session()->flash('Success', __('system.messages.deleted', ['model' => __('system.languages.title')]));
        if ($request->back) {
            return redirect($request->back);
        }
        return redirect(route('restaurant.languages.index'));
    }

    public function sampleDownload(Request $request)
    {
        // dd($request->all());

        return (new LanguageDataSampleSheet())->download('invoices.xlsx');
    }

    public function sampleImport(Request $request)
    {
        $file = $request->validate([
            'import_file' => ['required', 'file', 'mimes:xlsx'],
        ]);

        dd($file);
    }
}
