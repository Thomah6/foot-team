<?php

namespace Database\Factories;

use App\Models\Presence;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class PresenceFactory extends Factory
{
    protected $model = Presence::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'date' => Carbon::now()->subDays(rand(0, 30))->toDateString(),
            'present' => $this->faker->boolean(80), // 80% de chance d'être présent
            'validated_by_admin' => $this->faker->boolean(60), // 60% de chance d'être validé
        ];
    }

    /**
     * État : présence validée
     */
    public function validated(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'present' => true,
                'validated_by_admin' => true,
            ];
        });
    }

    /**
     * État : présence en attente de validation
     */
    public function pending(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'present' => true,
                'validated_by_admin' => false,
            ];
        });
    }

    /**
     * État : absence
     */
    public function absent(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'present' => false,
                'validated_by_admin' => false,
            ];
        });
    }

    /**
     * État : date spécifique
     */
    public function onDate($date): Factory
    {
        return $this->state(function (array $attributes) use ($date) {
            return [
                'date' => $date instanceof Carbon ? $date->toDateString() : $date,
            ];
        });
    }

    /**
     * État : utilisateur spécifique
     */
    public function forUser(User $user): Factory
    {
        return $this->state(function (array $attributes) use ($user) {
            return [
                'user_id' => $user->id,
            ];
        });
    }

    /**
     * État : mois spécifique
     */
    public function forMonth($month, $year): Factory
    {
        return $this->state(function (array $attributes) use ($month, $year) {
            $startDate = Carbon::create($year, $month, 1);
            $endDate = $startDate->clone()->endOfMonth();
            
            return [
                'date' => $this->faker->dateTimeBetween($startDate, $endDate)->format('Y-m-d'),
            ];
        });
    }
}
