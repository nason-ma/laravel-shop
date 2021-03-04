<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $images = [
            "https://laravel-china.org/uploads/images/201806/01/5320/7kG1HekGK6.jpg",
            "https://laravel-china.org/uploads/images/201806/01/5320/1B3n0ATKrn.jpg",
            "https://laravel-china.org/uploads/images/201806/01/5320/r3BNRe4zXG.jpg",
            "https://laravel-china.org/uploads/images/201806/01/5320/C0bVuKB2nt.jpg",
            "https://laravel-china.org/uploads/images/201806/01/5320/82Wf2sg8gM.jpg",
            "https://laravel-china.org/uploads/images/201806/01/5320/nIvBAQO5Pj.jpg",
            "https://laravel-china.org/uploads/images/201806/01/5320/XrtIwzrxj7.jpg",
            "https://laravel-china.org/uploads/images/201806/01/5320/uYEHCJ1oRp.jpg",
            "https://laravel-china.org/uploads/images/201806/01/5320/2JMRaFwRpo.jpg",
            "https://laravel-china.org/uploads/images/201806/01/5320/pa7DrV43Mw.jpg",
        ];

        return [
            'title' => $this->faker->word,
            'description' => $this->faker->sentence,
            'image' => $this->faker->randomElement($images),
            'on_sale' => true,
            'rating' => $this->faker->numberBetween(0, 5),
            'sold_count' => 0,
            'review_count' => 0,
            'price' => 0,
        ];
    }
}
