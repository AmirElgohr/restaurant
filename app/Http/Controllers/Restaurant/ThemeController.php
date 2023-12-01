<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ThemeController extends Controller
{

    public function index()
    {
        $request = request();
        $themes = getAllThemes();
        $user = $request->user();
        $restaurant = $user->restaurant;
        return view('restaurant.themes.index', ['themes' => $themes, 'restaurant' => $restaurant]);
    }

    public function update()
    {
        $request = request();
        $request->validate([
            'theme' => ['required'],
        ]);

        $input = $request->only('theme');
        $user = $request->user();
        $restaurant = $user->restaurant;
        $restaurant->fill($input)->save();
        $request->session()->flash('Success', __('system.messages.change_success_message', ['model' => __('system.themes.title')]));

        return redirect()->back();
    }
}
