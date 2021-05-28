<?php

namespace Tests\Unit\Models;

use App\Models\Account;
use App\Models\User;
use Tests\TestCase;

class AccountTest extends TestCase
{
    public function testUserRelation()
    {
        $user = User::factory()
            ->create();
        $account = Account::factory()
            ->create(['user_id' => $user->id]);
        $this->assertEquals(1, $account->user()->count());
        $this->assertEquals($user->id, $account->user()->first()->id);
    }

    public function testIncreaseBalance()
    {
        $account = Account::factory()
            ->create();
        $balance = $account->balance;
        $value = 10;
        $account->increaseBalance($value);
        $this->assertEquals($balance + $value, $account->balance);
    }

    public function testDecreaseBalance()
    {
        $account = Account::factory()
            ->create();
        $balance = $account->balance;
        $value = 10;
        $account->decreaseBalance($value);
        $account->refresh();
        $this->assertEquals($balance - $value, $account->balance);
    }
}
