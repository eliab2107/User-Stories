<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transacao;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class TransacaoController extends Controller
{
    // Display a listing of the transactions
    public function getall(Request $request)
    {
        try {
            $user = User::getUserByToken($request->bearerToken());

            if (!$user) {
                return response()->json(['error' => 'User not Authenticated'], 401);
            }
            $transactions = Transacao::where('user_id', $user->id)->get();
            if(!$transactions){
                return response()->json(['error' => 'Transactions not found'], 404);
            }
            return response()->json($transactions);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage(), $user], 500);
        }
    }
    

    // Show the form for creating a new transaction
    public function create(Request $request)
    {
        try {
            $user = User::getUserByToken($request->bearerToken());

            if (!$user){
                return response()->json(['error' => 'User not found'], 404);}

            $transaction = Transacao::create([
            'user_id' => $user->id,
            't_tipo' => $request->t_tipo,
            'value' => $request->value,
            'categoria_id' => $request->categoria_id,
            ]);

            return response()->json(['message' => 'Transaction created successfully', 'transaction' => $transaction], 201);

        } catch (\QueryException $e) {
            
                return response()->json([
                    'error' => 'Conflict',
                    'message' => 'The provided customer ID does not exist.'
                ], 422); //request valida mas n pode ser processada
            
        } catch (\Exception $e) {

            return response()->json(['error' => 'Failed to create transaction', 'message' => $e->getMessage(), $user], 500);

        }   
    }

    // Store a newly created transaction in storage
    public function getbyfilter(Request $request)
    {
        // Code to store a new transaction
    try {
        $user = User::getUserByToken($request->bearerToken());

        if (!$user) {
            return response()->json(['error' => 'User not Authenticated'], 401);
        }

        $query = Transacao::where('user_id', $user->id);

        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }

        if ($request->has('t_tipo')) {
            $query->where('t_tipo', $request->t_tipo);
        }

        if ($request->has('categoria_id')) {
            $query->where('categoria_id', $request->categoria_id);
        }

        $transactions = $query->get();

        if ($transactions->isEmpty()) {
        return response()->json(['error' => 'Transactions not found', $user], 404);
        }

        return response()->json($transactions);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Failed to retrieve transactions', 'message' => $e->getMessage()], 500);
    }
    }

    // Display the specified transaction
    public function show($id)
    {
        // Code to display a specific transaction
    }

    // Remove the specified transaction from storage
    public function delete(Request $request)
    {
        try {
            $user = User::getUserByToken($request->bearerToken());

            if (!$user) {
                return response()->json(['error' => 'User not Authenticated'], 401);
            }

            $transaction = Transacao::where('id', $request->transaction_id)->where('user_id', $user->id)->first();

            if (!$transaction) {
                return response()->json(['error' => 'Transaction not found'], 404);
            }

            $transaction->delete();

            return response()->json(['message' => 'Transaction deleted successfully'], 204);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete transaction', 'message' => $e->getMessage()], 500);
        }
    }
}