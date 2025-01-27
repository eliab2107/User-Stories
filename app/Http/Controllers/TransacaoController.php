<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transacao;
use App\Http\Controllers\Controller;
use App\Http\ModeLs\User;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    // Display a listing of the transactions
    public function index()
    {
        // Code to list transactions
    }

    // Show the form for creating a new transaction
    public function create(Request $request)
    {
        try {
            $user = Auth::user();
            if(!$user) {
                $user = User::getUserByToken($request->bearerToken());
                if (!$user)
                    return response()->json(['error' => 'User not found'], 404);
            }
            $transaction = Transacao::create([
            'user_id' => $user->id,
            't_tipo' => $request->t_tipo,
            'value' => $request->value,
            'categoria_id' => $request->categoria_id,
            ]);
            return response()->json(['message' => 'Transaction created successfully', 'transaction' => $transaction], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create transaction', 'message' => $e->getMessage()], 500);
        }
    }

    // Store a newly created transaction in storage
    public function store(Request $request)
    {
        // Code to store a new transaction
    }

    // Display the specified transaction
    public function show($id)
    {
        // Code to display a specific transaction
    }

    // Remove the specified transaction from storage
    public function destroy($id)
    {
        // Code to delete a specific transaction
    }
}