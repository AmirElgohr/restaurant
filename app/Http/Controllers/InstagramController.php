<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Amirsarhang\Instagram;

class InstagramController extends Controller
{
    public function index()
    {
        $permissions = [
            'instagram_basic',
            'instagram_manage_insights',
            'pages_read_engagement',
            'pages_show_list',
            'instagram_manage_comments',
            'instagram_manage_messages',
            'pages_manage_engagement',
            'pages_manage_metadata'
        ];

        // Generate Instagram Graph Login URL
        $login = (new Instagram())->getLoginUrl($permissions);

        // Redirect To Facebook Login & Select Account Page
        return redirect()->to($login);
    }

    public function callback()
    {
        $accessToken = Instagram::getUserAccessToken();
        dd($accessToken);
    }

    public function instagramAccounts(): array
    {
        $token = "<USER_ACCESS_TOKEN>"; // We got it in callback
        $instagram = new Instagram($token);

        // Will return all instagram accounts that connected to your facebook selected pages.
        return $instagram->getComment();
    }
}
