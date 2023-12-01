<?php

namespace App\Exports;

use App\Repositories\Restaurant\FoodCategoryRepository;
use App\Repositories\Restaurant\FoodRepository;
use Illuminate\Support\Arr;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;

class LanguageFilePerSheet implements FromArray, WithTitle
{
    public function __construct(string $key, string $val, array $languages)
    {
        $this->key = $key;
        $this->val  = $val;
        $this->languages  = $languages;
        $request = request();
        $this->user = $request->user();
    }

    
    public function array(): array
    {
        // return [];
        $datas = [];
        if (in_array($this->key, array_keys(getDynamicDataTables()))) {
            // dd($this->key);
            $params['restaurant_id'] = $this->user->restaurant_id;

            if ($this->key == "foods") {
                $foods = (new FoodRepository())->getUserRestaurantFoods($params);

                $newD =  [];
                $key = [];
                foreach ($this->languages as $k =>  $lang) {
                    if (count($key) == 0) {
                        $key[] = 'key';
                    }
                    $key[] = $lang . " (name)";
                    $key[] = $lang . " (description)";
                }
                foreach ($foods as $food) {
                    $temp = [];
                    $temp[] = $food->id;
                    $temp[] = $food->name;
                    $temp[] = $food->description;
                    foreach ($this->languages as $k =>  $lang) {
                        if ($k != 'en') {
                            $temp[] = $food->lang_name[$k] ?? '';
                            $temp[] = $food->lang_description[$k] ?? '';
                        }
                    }

                    $newD[] = $temp;
                }
                array_unshift($newD, $key);
                return $newD;
            } else {
                $foodCategories = (new FoodCategoryRepository)->getRestaurantFoodCategories($params);
                $newD =  [];
                $key = [];
                foreach ($this->languages as $k =>  $lang) {
                    if (count($key) == 0) {
                        $key[] = 'key';
                    }
                    $key[] = $lang;
                }
                foreach ($foodCategories as $category) {
                    $temp = [];
                    $temp[] = $category->id;
                    $temp[] = $category->category_name;
                    foreach ($this->languages as $k =>  $lang) {
                        if ($k != 'en') {
                            $temp[] = $category->lang_category_name[$k] ?? '';
                        }
                    }

                    $newD[] = $temp;
                }
                array_unshift($newD, $key);
                return $newD;
            }
        } else {
            $key = [];
            foreach ($this->languages as $k =>  $lang) {
                $vls = getFileAllLanguagesData($this->key, $k, 1);
                if (count($key) == 0) {
                    $key =  array_keys($vls);
                }
                $datas[$lang] = $vls;
            }
            $keys1 = array_keys($datas);
            array_unshift($keys1, 'key');
            $newD[] = $keys1;
            foreach ($key as $i => $k) {
                // dd($k);
                $a =  $datas[$this->languages['en']][$k];
                $temp = array_column($datas, $k);
                array_unshift($temp, $k);

                $newD[$k] = $temp;
                // dd($datas);
                // dd();
            }
            return ($newD);
        }


        return $datas;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return $this->val;
    }
}
