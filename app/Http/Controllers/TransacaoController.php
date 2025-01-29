<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transacao;
use App\Models\Categoria;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class TransacaoController extends Controller
{
   
    /**
     * @OA\Get(
     *     path="/api/transaction/getall",
     *     summary="Retrieve all transactions for the authenticated user",
     *     tags={"Transacoes"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful retrieval of transactions",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Transacao")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="User not Authenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="User not Authenticated")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Transactions not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Transactions not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="An error message"),
     *             @OA\Property(property="user", type="object")
     *         )
     *     )
     * )
     */
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
    

    /**
     * @OA\Post(
     *     path="/api/transaction/create",
     *     summary="Create a new transaction",
     *     tags={"Transacao"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"t_tipo", "value", "categoria_id"},
     *             @OA\Property(property="t_tipo", type="string", example="expense"),
     *             @OA\Property(property="value", type="number", format="float", example=100.50),
     *             @OA\Property(property="categoria_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Transaction created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Transaction created successfully"),
     *             @OA\Property(property="transaction", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="User not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Conflict",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Conflict"),
     *             @OA\Property(property="message", type="string", example="The provided customer ID does not exist.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Failed to create transaction",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Failed to create transaction"),
     *             @OA\Property(property="message", type="string", example="Error message")
     *         )
     *     ),
     *     security={{"bearerAuth":{}}}
     * )
     */
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
                ], 422); 
            
        } catch (\Exception $e) {

            return response()->json(['error' => 'Failed to create transaction', 'message' => $e->getMessage(), $user], 500);

        }   
    }

    /**
     * @OA\Get(
     *     path="/api/transaction/getbyfilter",
     *     summary="Retrieve a transaction by ID",
     *     tags={"Transacao"},
     *     @OA\Parameter(
     *         name="transaction_id",
     *         in="query",
     *         required=true,
     *         description="Transaction ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful retrieval of transaction",
     *         @OA\JsonContent(
     *             @OA\Property(property="transaction", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Transaction not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Transaction not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Failed to retrieve transaction",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Failed to retrieve transaction"),
     *             @OA\Property(property="message", type="string", example="Error message")
     *         )
     *     ),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function getbyfilter(Request $request)
    {
       
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

   
    /**
     * @OA\Put(
     *     path="/api/transaction/update",
     *     summary="Update a transaction",
     *     description="Updates the category of a transaction for an authenticated user.",
     *     tags={"Transacao"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"transaction_id", "new_categoria_id"},
     *             @OA\Property(property="transaction_id", type="integer", example=1, description="ID of the transaction to be updated"),
     *             @OA\Property(property="new_categoria_id", type="integer", example=2, description="New category ID for the transaction")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Transaction updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Transaction updated successfully"),
     *             @OA\Property(property="transaction", type="object", ref="#/components/schemas/Transacao")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="User not Authenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="User not Authenticated")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Transaction not found or Category not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Transaction not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Failed to update transaction",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Failed to update transaction"),
     *             @OA\Property(property="message", type="string", example="Error message")
     *         )
     *     ),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function update(Request $request)
    {
        try {
            $user = User::getUserByToken($request->bearerToken());

            if (!$user) {
                return response()->json(['error' => 'User not Authenticated'], 401);
            }

            $categoria = Categoria::where('user_id', $user->id)->where('id', $request->new_categoria_id)->first();
            if (!$categoria) {
                return response()->json(['error' => 'Category not found'], 404);
            }

            $transaction = Transacao::where('id', $request->transaction_id)->where('user_id', $user->id)->first();

            if (!$transaction) {
                return response()->json(['error' => 'Transaction not found'], 404);
            }

            $transaction->categoria_id = $request->new_categoria_id;
            $transaction->save();

            return response()->json(['message' => 'Transaction updated successfully', 'transaction' => $transaction], 200);

            
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update transaction', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/transaction/delete",
     *     summary="Delete a transaction",
     *     tags={"Transacao"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"transaction_id"},
     *             @OA\Property(property="transaction_id", type="integer", example=1, description="ID of the transaction to be deleted")
     *         )
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Transaction deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Transaction deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="User not Authenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="User not Authenticated")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Transaction not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Transaction not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Failed to delete transaction",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Failed to delete transaction"),
     *             @OA\Property(property="message", type="string", example="Error message")
     *         )
     *     ),
     *     security={{"bearerAuth":{}}}
     * )
     */

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