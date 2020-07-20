<?php

use App\Jobs\WecahtGetTicket;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Route;

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
Route::get('/wechat/init', 'WechatController@init');

// 微信支付回调
Route::any('/pay_callback', 'TicketController@payCallback');

// 退出登录
Route::get('/logout', 'AuthController@logout');

// 默认页
Route::get('/', function () {
    return redirect('/trailer');
});

// 系统
Route::get('/note', 'SysController@note');
Route::get('/msg', 'SysController@msg');
Route::get('/contact', 'SysController@contact');


// wechat user
Route::group(['middleware' => ['web', 'wechat.oauth']], function () {
    
    // 展会预告
    Route::get('/trailer', 'ExpoController@trailer');
    
    Route::get('/sms', 'AuthController@sms');
    Route::post('/code', 'AuthController@code')->middleware('throttle:1,2');
    Route::post('/check', 'AuthController@check')->middleware('throttle:5,5');

    // auth users
    Route::group(['middleware' => ['mix', 'state']], function () {
        // ----- OA -----
        // 统计
        Route::get('/report', 'SysController@report');
        // Excel: 导入
        Route::get('/import/order', 'SysController@import');
        Route::post('/import/save_order', 'SysController@saveOrder');

        // Excel: 导出
        Route::get('/download/{key}', 'SysController@download');
        

        // 应用中心
        Route::get('/apps', 'SysController@apps');

        // 微信
        Route::get('/ad', 'WechatController@ad');

        // 用户
        Route::get('/users', 'UserController@index');
        Route::get('/user/{id}', 'UserController@show');
        Route::get('/lock/{id}', 'UserController@lock');
        Route::get('/unlock/{id}', 'UserController@unlock');
        Route::get('/me', 'UserController@me');
        Route::get('/customers', 'UserController@customers'); # 客户
        Route::get('/suppliers', 'UserController@suppliers'); # 供应商
        Route::get('/partners', 'UserController@partners'); # 合作伙伴
        Route::post('/pub', 'UserController@pub'); # 公开

        // 机构
        Route::get('/orgs', 'OrgController@index');
        Route::get('/org/create/{id}', 'OrgController@create');
        Route::post('/org/store', 'OrgController@store');

        // 会展
        Route::get('/expos', 'ExpoController@index');
        Route::get('/expo/{id}', 'ExpoController@show');
        Route::post('/expo/allow/{id}', 'ExpoController@allow'); # 开关售票
        Route::get('/expo/sort/{id}', 'ExpoController@sort'); # 登记入场顺序
        Route::post('/expo/sort/store/{id}', 'ExpoController@sortStore'); # 登记入场顺序
        Route::get('/expos/create', 'ExpoController@create');
        Route::post('/expos/store', 'ExpoController@store');

        // 票
        Route::get('/pay/{id}', 'TicketController@order');
        Route::get('/tickets', 'TicketController@index');
        Route::get('/ticket/{id}', 'TicketController@show');
        Route::post('/ticket/trans/{id}', 'TicketController@trans');

        //发现
        Route::get('/discoveries','DiscoveryController@index');
    });
});


Route::get('/test', function () {

    // $a = App\User::find(8)->orders;

    $a = App\User::has('tickets')->get();


    // echo $a.'-'.$b.'-'.$c.'-'.$d.'-';

    foreach ($a as $key) {
        echo $key->id.' 张数: '.$key->tickets->count().'<br>------<br>';
        $b = $key->orders;

        foreach ($b as $k) {
            echo '订单号: '.$k->id.' /金额: '.$k->total_fee/100 . '/' . $k->status.' : '. $k->created_at .'<br>';
        }

        foreach ($key->tickets as $t) {
            echo '订单号: '.$t->order.' /时间: '.$t->created_at.'<br>';
        }

        echo '=================<br>';
    }

    // $a = App\Ticket::orderBy('order_id')->distinct('order_id')->get();

    // echo $a->count().'<br>-----<br>';

    // foreach ($a as $key) {
    //     # code...
    //     echo '+'.$key->user->id.'-'.$key->order_id.'<br>';
    // }
    // echo now();
    // $a = time();

    // $b = date('Y-m-d H:i:s', $a);

    // echo $b;

});


// ------------ dev -------------

// Route::get('/fake', 'WechatController@fake');



// Route::get('/in', function () {
    // // $user = App\User::find(5);
    // $user = App\User::find(6);
    // // $user = App\User::find(2);
    // // $user = App\User::find(1);
    // // $user = App\User::find(8);
    // Auth::login($user);
    // print_r($user->info);
// });
