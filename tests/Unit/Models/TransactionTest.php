<?php

namespace Tests\Unit\Models;

use App\Models\Account;
use App\Models\Transaction;
use Mockery;
use Tests\TestCase;

class TransactionTest extends TestCase
{
    public function testPayerRelation()
    {
        $account = Account::factory()
            ->create();
        $transaction = Transaction::factory()
            ->create(['payer_id' => $account->id]);

        $this->assertEquals(1, $transaction->payer()->count());
        $this->assertEquals($account->id, $transaction->payer()->first()->id);
    }

    public function testPayeeRelation()
    {
        $account = Account::factory()
            ->create();
        $transaction = Transaction::factory()
            ->create(['payee_id' => $account->id]);

        $this->assertEquals(1, $transaction->payee()->count());
        $this->assertEquals($account->id, $transaction->payee()->first()->id);
    }

    public function testConfirmPayment()
    {
        $transaction = Transaction::factory()
            ->create();
        $this->assertEquals(Transaction::STATUS_PENDING, $transaction->status);
        $transaction->confirmPayment();
        $this->assertEquals(Transaction::STATUS_CONFIRMED, $transaction->status);
    }

    public function testTransferShouldDecreaseAndIncreaseAccountsBalance()
    {
        $accountMock = Mockery::mock(
            Account::class, function ($mock) {
                $mock->shouldReceive('decreaseBalance')->once();
                $mock->shouldReceive('increaseBalance')->once();
            }
        );
        $transaction = new Transaction();
        $transaction->payer = $accountMock;
        $transaction->payee = $accountMock;

        $transaction->transfer();
    }
}
