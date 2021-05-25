<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransactionRequest;
use App\Models\Transaction;
use App\Services\Transaction\TransactionService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TransactionController extends Controller
{
    protected $model;

    public function __construct(TransactionService $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        return $this->model->all();
    }

    public function store(StoreTransactionRequest $request)
    {
        try {
            $transaction = $this->model->create($request->all());
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        if ($transaction) {
            return response()->json([
                'success' => true,
                'data' => $transaction->toArray(),
            ], Response::HTTP_CREATED);
        }

        return response()->json([
            'success' => false,
            'message' => 'Transação não pode ser adicionada',
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function show($id)
    {
        try {
            $transaction = $this->model->find($id);
            return response()->json(['data' => $transaction]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Transação não encontrada',
            ], Response::HTTP_NOT_FOUND);
        }
    }
}
