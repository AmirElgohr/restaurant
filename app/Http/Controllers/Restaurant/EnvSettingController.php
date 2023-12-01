<?php

namespace App\Http\Controllers\Restaurant;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Mail\Mailer;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Validation\Rule;
use Jackiedo\DotenvEditor\Facades\DotenvEditor;

class EnvSettingController extends Controller {

    public function show()
    {
        $user = auth()->user();

        if($user->hasRole('admin'))
        {
            return view('admin.settings.create');
        } else
        {
            $restaurant = $user->restaurant;

            return view('restaurant.settings.create', [
                'row' => $restaurant,
            ]);
        }
    }

    public function updateRestaurant(Request $request)
    {
        $request->validate([
            'restaurant_name' => ['required', 'string', 'min:2'],
//            'instagram_url'   => ['required', 'string', 'regex:/(?:(?:http|https):\/\/)?(?:www.)?(?:instagram.com|instagr.am|instagr.com)\/(\w+)/'],
            'theme'           => ['required', 'string', Rule::notIn('#ffffff')],
//            'menu_title_en'   => ['required', 'string'],
//            'menu_title_ar'   => ['required', 'string'],
            'restaurant_logo' => ['max:10000', "image", 'mimes:jpeg,png,jpg,gif,svg'],
        ], [
            "restaurant_logo.max"   => __('validation.gt.file', ['attribute' => 'restaurant_logo', 'value' => 10000]),
            "restaurant_logo.image" => __('validation.enum', ['attribute' => 'restaurant_logo']),
            "restaurant_logo.mimes" => __('validation.enum', ['attribute' => 'restaurant_logo']),
            "theme.not_in" => 'Cannot choose white color',
        ]);

        $data['name'] = $request->get('restaurant_name');
        $data['instagram_url'] = $request->has('instagram_url') ? $request->get('instagram_url') : '';
        $data['script_code'] = $request->has('script_code') ? $request->get('script_code') : '';
        $data['theme'] = $request->get('theme');
        $data['menu_title_en'] = $request->get('menu_title_en');
        $data['menu_title_ar'] = $request->get('menu_title_ar');

        if($request->has('intro_video_url'))
        {
            $data['intro_video_url'] = $request->get('intro_video_url');
        }

        if($request->has('restaurant_logo'))
        {
            $data['logo'] = '/storage/' . uploadFile($request->restaurant_logo, 'logo');
        }

        auth()->user()->restaurant()->update($data);

        $request->session()->flash('Success', __('system.messages.updated', ['model' => __('system.environment.title')]));

        return redirect()->back();
    }

    public function updateAdmin()
    {
        $request = request();

        if(!auth()->user()->isAdmin())
        {
            abort(403);
        }

        $lbl_app_dark_logo = strtolower(__('system.fields.logo'));
        $lbl_app_light_logo = strtolower(__('system.fields.app_dark_logo'));
        $lbl_app_favicon_logo = __('system.fields.app_favicon_logo');
        $intro_video_url = __('system.fields.intro_video_url');

        $request->validate([
            'app_dark_logo'    => ['max:10000', "image", 'mimes:jpeg,png,jpg,gif,svg'],
            'app_light_logo'   => ['max:10000', "image", 'mimes:jpeg,png,jpg,gif,svg'],
            'app_favicon_logo' => ['max:10000', "image", 'mimes:jpeg,png,jpg,gif,svg'],
            'intro_video_url' => ['required'],
        ], [
            "app_dark_logo.max"   => __('validation.gt.file', ['attribute' => $lbl_app_dark_logo, 'value' => 10000]),
            "app_dark_logo.image" => __('validation.enum', ['attribute' => $lbl_app_dark_logo]),
            "app_dark_logo.mimes" => __('validation.enum', ['attribute' => $lbl_app_dark_logo]),

            "app_light_logo.max"   => __('validation.gt.file', ['attribute' => $lbl_app_light_logo, 'value' => 10000]),
            "app_light_logo.image" => __('validation.enum', ['attribute' => $lbl_app_light_logo]),
            "app_light_logo.mimes" => __('validation.enum', ['attribute' => $lbl_app_light_logo]),

            "app_favicon_logo.max"   => __('validation.gt.file', ['attribute' => $lbl_app_favicon_logo, 'value' => 10000]),
            "app_favicon_logo.image" => __('validation.enum', ['attribute' => $lbl_app_favicon_logo]),
            "app_favicon_logo.mimes" => __('validation.enum', ['attribute' => $lbl_app_favicon_logo]),
        ]);

        if($request->has('app_light_logo'))
        {
            $data['APP_LIGHT_SMALL_LOGO'] = '/storage/' . uploadFile($request->app_light_logo, 'logo');
        }

        if($request->has('intro_video_url'))
        {
            $data['INTRO_VIDEO_URL'] = $request->get('intro_video_url');
        }

        if($request->has('app_dark_logo'))
        {
            $data['APP_DARK_SMALL_LOGO'] = '/storage/' . uploadFile($request->app_dark_logo, 'logo');
        }
        if($request->has('app_favicon_logo'))
        {
            $data['APP_FAVICON_ICON'] = '/storage/' . uploadFile($request->app_favicon_logo, 'logo');
        }

        DotenvEditor::setKeys($data)->save();
        Artisan::call('config:clear');

        $request->session()->flash('Success', __('system.messages.updated', ['model' => __('system.environment.title')]));

        return redirect()->back();
    }
}
