<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()
            ->count(4)
            ->state(new Sequence(
                ['type' => User::TYPE_COMMON],
                ['type' => User::TYPE_SHOPKEEPER],
            ))
            ->hasAccount(1)
            ->create();
    }
}
