<?php

namespace App\Observers;

use App\Models\Order;

class OrderObserver
{
    // 监听模型创建事件，在写入数据库之前触发
    public function creating(Order $order)
    {
        // 如果模型的 no 字段为空
        if (!$order->no) {
            // 调用 findAvailableNo 生成订单流水号
            $order->no = Order::findAvailableNo();
            // 如果生成失败，则终止创建订单
            if (!$order->no) {
                return false;
            }
        }
    }
}
