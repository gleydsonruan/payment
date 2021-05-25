<?php
namespace App\Repositories;

use App\Models\Transaction;

class TransactionRepository extends AbstractRepository
{
    /**      
     * @var Transaction      
     */     
    protected $model = Transaction::class;       
}
