<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\PlayerOfTheMonth;
use App\Models\User;
use App\Models\Stat;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PlayerOfTheMonth>
 */
class PlayerOfTheMonthFactory extends Factory
{
    protected $model = PlayerOfTheMonth::class;

    public function definition()
{
    $playerImages = [
        'https://picsum.photos/seed/factory1/400/500.jpg',
        'https://picsum.photos/seed/factory2/400/500.jpg',
        'https://picsum.photos/seed/factory3/400/500.jpg',
        'https://picsum.photos/seed/factory4/400/500.jpg'
    ];

    return [
        'user_id' => User::factory(),
        'month'   => $this->faker->dateTimeBetween('-1 years', 'now')->format('Y-m-01'),
        'reason'  => $this->faker->sentence(),
        'is_active' => $this->faker->boolean(20),
        'image'   => $this->faker->randomElement($playerImages),
    ];
}

public function configure()
{
    return $this->afterCreating(function ($player) {
        Stat::factory()
            ->count(rand(3, 8))
            ->forUser($player->user_id)
            ->state([
                'date' => $player->month,
                'validated_by_admin' => true,
            ])
            ->create();
    });
}

}
