<?php

use Illuminate\Http\Request;

use App\Jobs\WecahtGetTicket;
use Illuminate\Support\Facades\Auth;
use App\Jobs\WechatTicketPreregister;
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

// 抽奖
Route::get('/gift', 'GiftController@index');

Route::get('/trailer', 'ExpoController@trailer');

Route::group(['middleware' => ['mix', 'state']], function () {
    Route::get('/video/create', 'VideoController@create');
});

// wechat user
Route::group(['middleware' => ['web', 'wechat.oauth']], function () {
    
    // 展会预告
    
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
        Route::get('/expos/notice', 'ExpoController@notice');

        // 票
        Route::get('/pay/{id}', 'TicketController@order');
        Route::get('/tickets', 'TicketController@index');
        Route::get('/ticket/{id}', 'TicketController@show');
        Route::post('/ticket/trans/{id}', 'TicketController@trans');

        //发现
        Route::get('/discoveries','DiscoveryController@index');

        // 财务
        Route::get('/finances','FinanceController@index');
        Route::get('/finance/create','FinanceController@create');
        Route::post('/finance/store','FinanceController@store');
        Route::get('/finance/log','FinanceController@log');
        Route::get('/finance/dash','FinanceController@dash');
        Route::get('/finance/confirmed/{id}','FinanceController@confirmed');
        Route::get('/finance/abandon/{id}','FinanceController@abandon');

        // 事务
        Route::get('/tasks','TaskController@index');
        Route::get('/task/show/{id}','TaskController@show');
        Route::get('/task/create','TaskController@create');
        Route::post('/task/next','TaskController@next');
        Route::post('/task/store','TaskController@store');
        Route::get('/task/confirmed/{id}','TaskController@confirmed');
        Route::get('/task/abandon/{id}','TaskController@abandon');
        Route::post('/task/update','TaskController@update');
        Route::get('/task/finish/{id}','TaskController@finish');

    });
});

Route::get('/test', function () {
    // $a = Auth::user()->finance_to;
    $a = App\Task::find(1);
    $b = new App\Helpers\Task;
    var_dump($b->operate($a));
    // $a = App\Finance::where('abandon', false)->where('type', 'out')->sum('fee');

    // print_r($a);
    // $a = now();

    // print_r($a->users);

    // $a = [['id'=>5, 'task'=>'任务1'], ['id'=>6, 'task'=>"任务2"]];

    // function ch($array)
    // {
    //     $filtered = Arr::where($array, function ($value, $key) {
    //         // return is_string($value);
    //         if($value['id'] == Auth::id()) return $value;
    //     });

    //     return false;
    // }

    // var_dump(ch($a));
    
    // $new = [];
    // foreach ($a as $k) {
    //     $user = App\User::find($k['id']);
    //     $ks = Arr::add($k, 'name', face($user)->name);
    //     $new[] = $ks;
    // }

    // print_r($new);
});

// Route::get('/check', function () {

//     // $a = App\User::find(8)->orders;

//     $a = App\User::has('tickets')->get();


//     // echo $a.'-'.$b.'-'.$c.'-'.$d.'-';

//     foreach ($a as $key) {
        
//         echo '用户id: '.$key->id.'; 张数: '.$key->tickets->count().'<br>------<br>';

//         foreach ($key->tickets as $t) {
//             echo $t->id.'; 时间: '.$t->created_at.'<br>';
//         }
//         echo '---------------<br>';

//         $b = $key->orders;

//         $filtered = $b->reject(function ($v) {
//             return empty($v->status);
//         });

//         foreach ($filtered as $k) {
//             echo '交易号: '.$k->out_trade_no.'单号: '.$k->id.'; 金额: '.$k->total_fee/100 . '; ' . $k->status.' : '. $k->created_at .'<br>';
//         }

        

//         echo '=================<br>';
//     }

//     // $a = App\Ticket::orderBy('order_id')->distinct('order_id')->get();

//     // echo $a->count().'<br>-----<br>';

//     // foreach ($a as $key) {
//     //     # code...
//     //     echo '+'.$key->user->id.'-'.$key->order_id.'<br>';
//     // }
//     // echo now();
//     // $a = time();

//     // $b = date('Y-m-d H:i:s', $a);

//     // echo $b;

// });


// Route::get('/find', function () {
//     $arr = [11,133,159];
//     $users = App\User::whereIn('id', $arr)->get();

//     foreach ($users as $key) {
        
//         echo '用户id: '.$key->id.', 手机号:'.show($key->ids, 'mobile.number').'; 张数: '.$key->tickets->count().'<br>------<br>';

//         foreach ($key->tickets as $t) {
//             echo '票id号: '.$t->id.', 入场次序: '.$t->sorted .'; 对应交易号: '.$t->order->out_trade_no.'<br>';
//         }
//         echo '---------------<br>';

//         $b = $key->orders;

//         $filtered = $b->reject(function ($v) {
//             return empty($v->status);
//         });

//         foreach ($filtered as $k) {
//             echo '交易号: '.$k->out_trade_no.'单号: '.$k->id.'; 金额: '.$k->total_fee/100 . '; ' . $k->status.' : '. $k->created_at .'<br>';
//         }

        

//         echo '=================<br>';
//     }
// });


// ------------ dev -------------

// Route::get('/fake', 'WechatController@fake');

Route::get('/token/{id}', 'TestController@token');

Route::get('/in/{id}', function () {

    $r = new Request;
    $id = $r->id;
    
    // $user = App\User::find(5);
    $user = App\User::find($id);
    // $user = App\User::find(2);
    // $user = App\User::find(1);
    // $user = App\User::find(8);
    Auth::login($user);
    print_r($user->info);

    $t = $user->createToken('goodluck')->plainTextToken;

    // Auth::guard('sanctum')->user()
    echo '<br>'.$t;
});

Route::get('/phpinfo', function () {
    phpinfo();
});
