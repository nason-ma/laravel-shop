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

    public function store(UserAddressRequest $request)
    {
        // user()->addresses() 获取当前用户与地址的关联关系（注意：这里并不是获取当前用户的地址列表）
        // addresses()->create() 在关联关系里创建一个新的记录。
        $request->user()->addresses()->create($request->only([
            'province',
            'city',
            'district',
            'address',
            'zip',
            'contact_name',
            'contact_phone',
        ]));

        return redirect()->route('user_addresses.index');
    }
}
