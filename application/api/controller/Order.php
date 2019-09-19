<?php

namespace app\api\controller;

use think\Controller;

/**
 * Created by PhpStorm.
 * User: RSCMS_V2
 * Date: 2018-12-4
 * Time: 12:33
 */
class Order extends Controller
{
    public function pay($order_id = '')
    {
        if (!$order = \app\api\model\Order::get($order_id)) {
            return ['code' => -1, 'msg' => '订单不存在'];
        }
        if ($order->pay_status == 1) {
            // 订单已支付
            return '订单已支付';
        }
        if (time() - strtotime($order->create_time) > 300) {
            // 订单已过期
            return '订单已过期';
        }
        $end_time = date('Y-m-d H:i:s', strtotime($order->create_time) + 300);
        $url = "alipays://platformapi/startapp?appId=09999988&actionType=toAccount&goBack=NO&amount=" . $order->amount . "&userId=" . $order->alipay_user_id . "&memo=" . $order->trade_no;
        $this->assign([
            'order_id' => $order->id,
            'trade_no' => $order->trade_no,
            'qrcode' => $url,
            'end_time' => $end_time,
            'amount' => $order->amount,
            'success_url' => $order->success_url
        ]);
        return $this->fetch();
    }

    public function status($order_id)
    {
        if (!$order = \app\api\model\Order::get($order_id)) {
            return ['err' => 1];
        }
        return ['status' => $order->pay_status];
    }


}