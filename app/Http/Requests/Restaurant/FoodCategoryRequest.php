<?php

namespace App\Http\Requests\Restaurant;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class FoodCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {

        $request = request();
        $langs = getAllCurrentRestaruentLanguages();
        $rules = [
            'category_image' => ['nullable', 'max:50000', "image", 'mimes:jpeg,png,jpg,gif,svg'],
            'category_name' => ['required', 'string', 'max:255', 'min:2'],

            'restaurant_id' => ['required', 'unique:food_categories,restaurant_id,null,id,category_name,' . $request->category_name],
        ];


        $request->request->add(['restaurant' => array_filter($request->restaurant ?? [])]);
        // dd($request->all());
        if (isset($this->food_category)) {
            $old_id = $this->food_category->id;
            unset($rules['restaurant.*']);
            unset($rules['restaurant']);
            $rules['restaurant_id'] = ['required', 'unique:food_categories,restaurant_id,' . $old_id . ',id,category_name,' . $request->category_name];
            if (count($langs) > 0) {
                $rules['lang_category_name.*'] = ['required',];
                $rules['restaurant_ids.*'] = ['required'];

                foreach ($langs as $key => $lang) {
                    $rules['lang_category_name.' . $key] = ['string', 'max:255', 'min:2'];
                    $rules['restaurant_ids.' . $key] = ["unique:food_categories,restaurant_id,$old_id,id,lang_category_name->$key," .  str_replace("%", "%%", $request->lang_category_name[$key])];
                }
            }
        } elseif (count($langs) > 0) {
            $rules['lang_category_name.*'] = ['required',];
            $rules['restaurant_ids.*'] = ['required'];

            foreach ($langs as $key => $lang) {
                $rules['lang_category_name.' . $key] = ['string', 'max:255', 'min:2'];
                // dd("unique:food_categories,restaurant_id,null,id,lang_category_name->$key," . $request->lang_category_name[$key]);
                $rules['restaurant_ids.' . $key] = ["unique:food_categories,restaurant_id,null,id,lang_category_name->$key," . str_replace("%", "%%", $request->lang_category_name[$key])];
            }
        }
        // dd($rules);
        // DB::enableQueryLog();
        return $rules;
    }

    public function messages()
    {

        $langs = getAllCurrentRestaruentLanguages();
        $request = request();
        $lbl_category_image = strtolower(__('system.fields.category_image'));
        $lbl_category_name = strtolower(__('system.fields.category_name'));
        $messages = [
            "category_image.max" => __('validation.gt.file', ['attribute' => $lbl_category_image, 'value' => 50000]),
            "category_image.image" => __('validation.enum', ['attribute' => $lbl_category_image]),
            "category_image.mimes" => __('validation.enum', ['attribute' => $lbl_category_image]),

            "category_name.required" => __('validation.required', ['attribute' => $lbl_category_name]),
            "category_name.string" => __('validation.custom.invalid', ['attribute' => $lbl_category_name]),
            "category_name.max" => __('validation.custom.invalid', ['attribute' => $lbl_category_name]),

            "restaurant_id.required" => __('validation.custom.select_required', ['attribute' => 'restaurant']),
            "restaurant_id.unique" => __('validation.unique', ['name' => $request->category_name, 'attribute' => $lbl_category_name]),
        ];
        if (count($langs) > 0) {

            foreach ($langs as $key => $lang) {
                $messages["lang_category_name.$key.string"] = __('validation.custom.invalid', ['attribute' => 'category name ' . strtolower($lang)]);
                $messages["lang_category_name.$key.max"] = __('validation.custom.invalid', ['attribute' => 'category name ' . strtolower($lang)]);
                $messages["lang_category_name.$key.min"] = __('validation.custom.invalid', ['attribute' => 'category name ' . strtolower($lang)]);
                $messages["restaurant_ids.$key.unique"]  = __('validation.unique', ['name' => $request->lang_category_name[$key], 'attribute' => $lbl_category_name . " " . strtolower($lang)]);
            }
        }
        return $messages;
    }
}
