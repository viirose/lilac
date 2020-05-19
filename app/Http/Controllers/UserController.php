<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Information of Auth user
     *
     */
    public function me()
    {
        // $user = session('wechat.oauth_user.default'); // 拿到授权用户资料
        return view('user.me');
    }

}