<?php

use Illuminate\Support\Facades\Redis;

use Illuminate\Support\Facades\Route;
use function EasyWeChat\Kernel\Support\str_random;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// logs
Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');

// wechat
Route::any('/wechat', 'WechatController@serve');
Route::any('/wechat/call_back', 'WechatController@callBack');

// sms
Route::get('/sms', 'AuthController@sms');
Route::post('/code', 'AuthController@code')->middleware('throttle:10,1');
Route::post('/check', 'AuthController@check')->middleware('throttle:50,5');

Route::get('/', function () {
    return view('welcome');
});

// wechat user
Route::group(['middleware' => ['web', 'wechat.oauth']], function () {

    // auth users
    Route::get('/expos', 'UserController@index');
    Route::group(['middleware' => ['mix']], function () {
        Route::get('/me', 'UserController@me');
    });

});

Route::get('/test', function () {
   $a =  session('wechat.oauth_user.default');
   print_r($a);
});
