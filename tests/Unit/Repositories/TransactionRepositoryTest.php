<?php

namespace Tests\Unit\Repositories;

use App\Models\Account;
use App\Models\Transaction;
use App\Repositories\TransactionRepository;
use Tests\TestCase;

class TransactionRepositoryTest extends TestCase
{
    public function testTransactionCreate()
    {
        $repository = new TransactionRepository();

        $input = [
            'payer_id' => Account::factory()->create()->id,
            'payee_id' => Account::factory()->create()->id,
            'value' => 1000,
            'status' => Transaction::STATUS_PENDING,
        ];

        $transaction = $repository->create($input);

        $this->assertInstanceOf('App\Models\Transaction', $transaction);
        $this->assertDatabaseHas(
            'transactions', [
                'payer_id' => $transaction->payer_id,
                'payee_id' => $transaction->payee_id,
                'value' => $transaction->value,
                'status' => $transaction->status,
            ]
        );
    }

    public function testTransactionFind()
    {
        $repository = new TransactionRepository();
        $transaction = Transaction::factory()->create();
        $model = $repository->find($transaction->id);

        $this->assertInstanceOf('App\Models\Transaction', $model);
        $this->assertEquals($transaction->payer_id, $model->payer_id);
        $this->assertEquals($transaction->payee_id, $model->payee_id);
        $this->assertEquals($transaction->value, $model->value);
        $this->assertEquals($transaction->status, $model->status);
    }

    public function testTransactionFindWhenUsesFindOrFailMethodAndRegisterNotExists()
    {
        try {
            $repository = new TransactionRepository();
            $repository->find(1);
        } catch (\Throwable $th) {
            $this->assertInstanceOf(
                'Illuminate\Database\Eloquent\ModelNotFoundException', 
                $th
            );
        }
    }

    public function testTransactionFindWhenUsesFindMethodAndRegisterNotExists()
    {
        $repository = new TransactionRepository();
        $this->assertEmpty($repository->find(1, false));
    }

    public function testTransactionAll()
    {
        $repository = new TransactionRepository();
        Transaction::factory()
            ->count(4)
            ->create();

        $this->assertCount(4, $repository->all());
    }
}