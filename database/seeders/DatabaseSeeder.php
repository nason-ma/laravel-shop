<?php

namespace Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        Model::unguard();

        // \App\Models\UserAddress::factory()->count(3)->create();
        \App\Models\CouponCode::factory()->count(10)->create();

        Model::reguard();
    }
}
