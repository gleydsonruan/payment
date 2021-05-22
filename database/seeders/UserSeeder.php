<?php

namespace Database\Seeders;

use App\Models\User;
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
            ->count(3)
            ->create([
                'type' => User::TYPE_COMMON,
            ]);
        User::factory()
            ->count(2)
            ->create([
                'type' => User::TYPE_SHOPKEEPER,
            ]);
    }
}
