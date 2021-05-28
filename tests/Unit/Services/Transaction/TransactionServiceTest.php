<?php

namespace Tests\Unit\Services\Transaction;

use App\Models\Account;
use App\Models\Transaction;
use App\Repositories\TransactionRepository;
use App\Services\Transaction\TransactionService;
use Illuminate\Support\Facades\DB;
use Mockery;
use Tests\TestCase;

class TransactionServiceTest extends TestCase
{
    public function testTransactionFind()
    {
        $repositoryMock = Mockery::mock(
            TransactionRepository::class, function ($mock) {
                $mock->shouldReceive('find')->once();
            }
        );
        $service = new TransactionService($repositoryMock);
        $service->find(1);
    }

    public function testTransactionAll()
    {
        $repositoryMock = Mockery::mock(
            TransactionRepository::class, function ($mock) {
                $mock->shouldReceive('all')->once();
            }
        );
        $service = new TransactionService($repositoryMock);
        $service->all();
    }

    public function testTransactionCreate()
    {
        DB::shouldReceive('beginTransaction');
        DB::shouldReceive('commit');

        $transactionMock = Mockery::mock(
            Transaction::class, function ($mock) {
                $mock->shouldReceive('confirmPayment')->once();
            }
        );
        $repositoryMock = Mockery::mock(
            TransactionRepository::class, function ($mock) use ($transactionMock) {
                $mock->shouldReceive('create')->once()->andReturn($transactionMock);
            }
        );
        $serviceMock = Mockery::mock(
            TransactionService::class, [$repositoryMock], function ($mock) {
                $mock->makePartial();
                $mock->shouldReceive('transfer')->once();
                $mock->shouldReceive('sendConfirmationEmail')->once();
            }
        );
        $transaction = $serviceMock->create([]);
        $this->assertInstanceOf('App\Models\Transaction', $transaction);
    }

    public function testTransactionCreateWhenThrowsException()
    {
        DB::shouldReceive('beginTransaction');
        DB::shouldReceive('rollBack');

        $repositoryMock = Mockery::mock(
            TransactionRepository::class, function ($mock) {
                $mock->shouldReceive('create')
                    ->andThrow(new \Exception('Fail to create'));
            }
        );
        $service = new TransactionService($repositoryMock);

        try {
            $service->create([]);
        } catch (\Throwable $th) {
            $this->assertInstanceOf('\Exception', $th);
            $this->assertEquals($th->getMessage(), 'Fail to create');

        }
    }

    public function testTransactionTransfer()
    {
        $transactionMock = Mockery::mock(
            Transaction::class, function ($mock) {
                $mock->shouldReceive('transfer')->once();
            }
        );
        $serviceMock = Mockery::mock(
            TransactionService::class, function ($mock) {
                $mock->makePartial();
                $mock->shouldReceive('validateTransaction')->once();
                $mock->shouldReceive('authorize')->once();
            }
        );

        $serviceMock->transfer($transactionMock);
    }
}
