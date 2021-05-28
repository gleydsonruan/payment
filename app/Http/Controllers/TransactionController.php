<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransactionRequest;
use App\Services\Transaction\TransactionService;
use Illuminate\Http\Response;

class TransactionController extends Controller
{
    protected $model;

    /**
     * Create a new controller instance.
     * 
     * @param TransactionService $model 
     */
    public function __construct(TransactionService $model)
    {
        $this->model = $model;
    }

    /**
     * List transactions
     *
     * @return Collection
     */
    public function index()
    {
        return $this->model->all();
    }

    /**
     * Store a transaction
     *
     * @param  StoreTransactionRequest $request  
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreTransactionRequest $request)
    {
        try {
            $transaction = $this->model->create($request->validated());
        } catch (\Throwable $th) {
            return response()->json(
                [
                'success' => false,
                'message' => $th->getMessage(),
                ], 
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        if ($transaction) {
            return response()->json(
                [
                    'success' => true,
                    'data' => $transaction->toArray(),
                ], 
                Response::HTTP_CREATED
            );
        }

        return response()->json(
            [
                'success' => false,
                'message' => 'Transação não pode ser adicionada',
            ], 
            Response::HTTP_INTERNAL_SERVER_ERROR
        );
    }

    /**
     * Show the transaction
     *
     * @param  int $id  
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $transaction = $this->model->find($id);
            return response()->json(['data' => $transaction]);
        } catch (\Throwable $th) {
            return response()->json(
                ['message' => 'Transação não encontrada'], 
                Response::HTTP_NOT_FOUND
            );
        }
    }
}
