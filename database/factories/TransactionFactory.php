<?php

namespace Database\Factories;

use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Transaction::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'payer_id' => Account::factory(),
            'payee_id' => Account::factory(),
            'value' => $this->faker->randomFloat(2, 0.1, 10000),
            'status' => Transaction::STATUS_PENDING,
        ];
    }
}
