<?php

namespace App\Services\Transaction;

use App\Models\Transaction;
use App\Models\User;
use App\Repositories\TransactionRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class TransactionService
{
    /**
     * @var \App\Repositories\TransactionRepository $_repository
     */
    private $_repository;

    public function __construct(TransactionRepository $repository)
    {
        $this->_repository = $repository;
    }

    public function find($id, $fail = true) : ?Transaction
    {
        return $this->_repository->find($id, $fail);
    }

    public function all() : ?Collection
    {
        return $this->_repository->all();
    }
    
    public function create(array $attributes) : ?Transaction
    {
        try {
            DB::beginTransaction();
            
            $transaction = $this->_repository->create($attributes);
            $this->transfer($transaction);            
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

        DB::commit();

        return $transaction;
    }

    protected function transfer(Transaction $transaction)
    {
        $this->validateTransaction($transaction);
        $transaction->payer->decreaseBalance($transaction->value);
        $transaction->payee->increaseBalance($transaction->value);
        $this->authorize($transaction);
    }

    protected function validateTransaction(Transaction $transaction)
    {
        if ($transaction->payer->user->type == User::TYPE_SHOPKEEPER) {
            throw new \Exception("Usuário lojista não pode fazer transferência");
        }

        if ($transaction->payer->id == $transaction->payee->id) {
            throw new \Exception("Usuário não pode transferir para ele mesmo");
        }

        if ($transaction->value > $transaction->payer->balance) {
            throw new \Exception("Saldo insuficiente");
        }
    }

    protected function authorize(Transaction $transaction)
    {
        $auth = Http::get('https://run.mocky.io/v3/8fafdd68-a090-496f-8c9a-3442cf30dae6')['message'];
        
        if ($auth != 'Autorizado') {
            throw new \Exception("Transação não autorizada");
        }
    }
}
