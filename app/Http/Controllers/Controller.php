<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Cookie;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public function themeMode()
    {
        // dd(request()->all());
        if (request()->theme) {
            Cookie::queue('resto_defult_theme', request()->theme, 24 * 60 * 60);
        } elseif (isset(request()->f_theme)) {
            Cookie::queue('front_theme', request()->f_theme, 24 * 60 * 60 * 365);
        } elseif (isset(request()->f_dir)) {
            Cookie::queue('front_dir', request()->f_dir, 24 * 60 * 60 * 365);
        }
    }
}
