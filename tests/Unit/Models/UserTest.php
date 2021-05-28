<?php

namespace Tests\Unit\Models;


use App\Models\User;
use App\Models\Account;
use Tests\TestCase;

class UserTest extends TestCase
{

    public function testAccountRelationNotExist()
    {
        $user = $this->createUser();
        $this->assertEmpty($user->account()->get());
    }

    public function testAccountRelationExists()
    {
        $user = $this->createUser();
        Account::factory()
            ->create(['user_id' => $user->id]);
        $account = $user
            ->account()
            ->get();
        $this->assertNotEmpty($account);
        $this->assertCount(1, $account);
    }

    public function testGetJWTIdentifier()
    {
        $user = $this->createUser();
        $this->assertEquals($user->getKey(), $user->getJWTIdentifier());
    }

    public function testGetJWTCustomClaims()
    {
        $this->assertEmpty((new User())->getJWTCustomClaims());
    }

    protected function createUser($attributes = [])
    {
        return User::factory($attributes)
            ->create();
    }
}
