<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use App\Models\RestaurantUser;
use DateInterval;
use DateTime;
use Illuminate\Http\Request;

class MenuController extends Controller {

    public function index(Request $request)
    {
        if($request->has('menu') && !empty($request->get('menu')))
        {
            $uuid = $request->get('menu');
        } else
        {
            if(auth()->check())
            {
                $user = auth()->user();

                if(!$user->isRest())
                {
                    return redirect('home');
                }
                $uuid = $user->restaurant->uuid;
            } else
            {
                return redirect('login');
            }
        }

        $rest = Restaurant::query()->where('uuid', $uuid)->first();

        if(empty($rest))
        {
            abort(404);
        }

//        $rest->theme = "#824a7e";

        $intro_video_url = !empty($rest->intro_video_url) ? $rest->intro_video_url : config('app.intro_video_url');

        return view('menu.index', [
            'theme'           => !empty($rest->theme) ? $rest->theme : "#821379",
            'logo'            => $rest->logo,
            'foods'           => $rest->foods()->where('foods.is_available', 1)->get(),
            'menu_title'      => [
                'en' => $rest->menu_title_en,
                'ar' => $rest->menu_title_ar,
            ],
            'insta'           => getInstaUsername($rest->instagram_url),
            'intro_video_url' => $intro_video_url,
            'script_code'    => $rest->script_code
        ]);
    }
}
