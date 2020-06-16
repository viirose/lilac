<?php

namespace App\Http\Controllers;

use App\Expo;
use App\User;
use App\Order;
use App\Ticket;
use App\Rules\Mobile;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    protected $app, $payment;

    function __construct()
    {
        $this->app = app('wechat.official_account');
        $this->payment =  app('wechat.payment');

    }
    /**
     * 微信支付购票
     *
     *
     */
    public function order($id)
    {
       $expo =  Expo::findOrFail($id);

        $info = [
            'body' => show($expo->info, 'title', '').'电子门票',
            'out_trade_no' => Auth::id().'-'.$expo->id.'-'.time(),
            'total_fee' => show($expo->info, 'price') ? floor(floatval(show($expo->info, 'price'))*100) : 20000, # 分
            // 'spbill_create_ip' => '123.12.12.123', // 可选，如不传该参数，SDK 将会自动获取相应 IP 地址
            // 'notify_url' => 'https://pay.weixin.qq.com/wxpay/pay.action', // 支付结果通知网址，如果不设置则会使用配置里的默认地址
            'trade_type' => 'JSAPI', // 请对应换成你的支付方式对应的值类型
            'openid' =>  show(Auth::user()->ids, 'wechat.id'),
            ];

        $order = $this->payment->order->unify($info);

        if(Arr::has($order, 'return_code') && Arr::get($order, 'return_code') == 'SUCCESS' && Arr::has($order, 'result_code') && Arr::get($order, 'result_code') == 'SUCCESS' && Arr::has($order, 'prepay_id')){
            $prepayId = Arr::get($order, 'prepay_id');

            // 写入
            Order::create($info);
        }else{
            abort('510');
        }

        $jssdk = $this->payment->jssdk;
        $json = $jssdk->bridgeConfig($prepayId);

        return view('pay', compact('json','info'));
    }

    /**
     * 支付结果回调
     *
     */
    function payCallback()
    {
        $response = $this->payment->handlePaidNotify(function($message, $fail){

            $order = Order::where('out_trade_no', $message['out_trade_no'])->first();

            if (!$order || $order->paid_at) { // 如果订单不存在 或者 订单已经支付过了
                return true;
            }

            // 查询
            $real_resault = $this->payment->order->queryByOutTradeNumber($message['out_trade_no']);

            // Log::info($real_resault);

            if ($message['return_code'] === 'SUCCESS') {
                // 用户是否支付成功
                if (Arr::get($message, 'result_code') === 'SUCCESS' && Arr::get($real_resault, 'trade_state') === 'SUCCESS') {
                    $this->getTicket($message);

                    $order->paid_at = now(); // 更新支付时间为当前时间
                    $order->status = '支付成功';

                // 用户支付失败
                } elseif (Arr::get($message, 'result_code') === 'FAIL') {
                    $order->status = '支付失败';
                }
            } else {
                return $fail('通信失败，请稍后再通知我');
            }

            $order->save(); // 保存订单

            return true; // 返回处理完成
        });

        return $response;
    }

    /**
     * 电子票
     *
     */
    private function getTicket($message)
    {
        $p = explode('-', $message['out_trade_no']);

        $order = Order::where('out_trade_no', $message['out_trade_no'])->firstOrFail();

        $new = [
            'user_id' => $p[0],
            'expo_id' => $p[1],
            'order_id' => $order->id,
            'logs' => [['time' => time(), 'do' => '购票', 'by' => $p[0]]],
        ];

        Ticket::create($new);
    }

    /**
     * 票
     *
     */
    public function index()
    {
        $this->authorize('viewAll', Ticket::class);

        $tickets = Ticket::paginate(20);

        return view('ticket.index', compact('tickets'));

    }

    /**
     * 票
     *
     */
    public function show($id)
    {
        $ticket = Ticket::findOrFail($id);

        // $this->authorize('view',Auth::user(),$ticket);

        $url = false;

        if((!$ticket->used || ($ticket->used && $ticket->afk)) && $ticket->expo->end > now()){
            $qrcode = $this->app->qrcode->temporary('t_'.Auth::id().'_'.$id, 60); # 1分钟
            $url = $qrcode['url'];
        }

        return view('ticket.show', compact('ticket', 'url'));

    }

    /**
     * 转让
     *
     */
    public function trans(Request $request, $id)
    {
        $request->validate([
            'mobile' => ['required', new Mobile],
            'terms' => ['accepted'],
        ]);

        $mobile = $request->mobile;

        $target = User::where('ids->mobile->number', $mobile)->first();

        if(!$target) json_encode(['errors' =>['mobile' => '用户不存在或者没有关注公众号']]);

        $ticket = Ticket::findOrFail($id);

        $logs = $ticket->logs;
        $logs[] = ['do' => '赠送', 'time' => time(), 'id' => Auth::id(), 'accept' => $target->id];

        // 更新票面信息
        $ticket->update([
            'user_id' => $target->id,
            'logs' => $logs,
        ]);

        return json_encode(['success' => 'ok']);
    }


}
