<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Models\Order;
use App\Models\UserAddress;
use App\Services\OrderService;
use Illuminate\Http\Request;

class OrdersController extends Controller
{

    public function store(OrderRequest $request, OrderService $orderService)
    {
        $user = $request->user();
        $address = UserAddress::find($request->input('address_id'));

        return $orderService->store($user, $address, $request->input('remark'), $request->input('items'));
    }

    public function index(Request $request)
    {
        // 使用 with 方法预加载，避免N + 1问题
        $orders = Order::with(['items.product', 'items.productSku'])
            ->where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->paginate();

        return view('orders.index', ['orders' => $orders]);
    }

    public function show(Order $order, Request $request)
    {
        $this->authorize('own', $order);
        // load() 方法与 with() 预加载方法有些类似，称为 延迟预加载，
        // 不同点在于 load() 是在已经查询出来的模型上调用，而 with() 则是在 ORM 查询构造器上调用。
        return view('orders.show', ['order' => $order->load(['items.productSku', 'items.product'])]);
    }
}