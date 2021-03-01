<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserAddressRequest;
use App\Models\UserAddress;
use Illuminate\Http\Request;

class UserAddressesController extends Controller
{
    public function index(Request $request)
    {
        return view('user_addresses.index', [
            'addresses' => $request->user()->addresses,
        ]);
    }

    public function create()
    {
        return view('user_addresses.create_and_edit', [
            'address' => new UserAddress(),
        ]);
    }

    public function edit(UserAddress $user_address)
    {
        $this->authorize('own', $user_address);
        return view('user_addresses.create_and_edit', [
            'address' => $user_address,
        ]);
    }

    public function store(UserAddressRequest $request, UserAddress $user_address)
    {
        // user()->addresses() 获取当前用户与地址的关联关系（注意：这里并不是获取当前用户的地址列表）
        // addresses()->create() 在关联关系里创建一个新的记录。
        $request->user()->addresses()->create($request->only($user_address->saveField));

        return redirect()->route('user_addresses.index');
    }

    public function update(UserAddressRequest $request, UserAddress $user_address)
    {
        $this->authorize('own', $user_address);
        $user_address->update($request->only($user_address->saveField));

        return redirect()->route('user_addresses.index');
    }

    public function destroy(UserAddress $user_address)
    {
        // authorize('own', $user_address) 方法会获取第二个参数 $user_address 的类名: App\Models\UserAddress，
        // 然后在 AuthServiceProvider 类的 $policies 属性中寻找对应的策略，在这里就是 App\Policies\UserAddressPolicy，
        // 找到之后会实例化这个策略类，再调用名为 own() 方法，如果 own() 方法返回 false 则会抛出一个未授权的异常。
        $this->authorize('own', $user_address);
        $user_address->delete();

        return [];
    }
}
