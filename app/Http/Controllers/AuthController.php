<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
     /**
     * @OA\Get(
     *     path="/api/login",
     *     summary="Login a user",
     *     tags={"Categories"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property( property="email",  type="string",  description="Email of the user" ),    
     *             @OA\Property( property="password",  type="string",  description="Password of the user" )     
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login With Sucess",
     *         @OA\JsonContent(
     *             @OA\Property(property="token", type="string", example="00|dQMSEc17xseRzOwsuDLUhu0nnr0C7gU1hdsGl7FR75189e29"),
     *             @OA\Property(property="redirect", type="Route", example="dashboard"),
     *             @OA\Property(property="user", type="Object", example={"id": 1, "name": "John Doe", "email": "Jhon@gmail.comn", "cpf": "12345678901",
     *                "password": "senhasegura123"})
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Failed Request Validation",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Conflict"),
     *             @OA\Property(property="message", type="string", example="The provided customer ID does not exist.")
     *         )
     *     ),
     *    @OA\Response(
     *         response=401,
     *         description="User not Authenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="User not Authenticated")
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
     *    
     * )
     */

    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|string|email',
                'password' => 'required|string',
            ]);

            if (!Auth::attempt($request->only('email', 'password'))) {
                return response()->json(['message' => 'Invalid login details'], 401);
            }

            $user = $request->user();
            $token = $user->createToken('auth_token')->plainTextToken; 

            return response()->json(['token' => $token, 'user' => $user, 'redirect' => route('dashboard')], 200);

        } catch (\Illuminate\Database\QueryException $e) {            
            return response()->json([ 'error' => 'Conflict', 'message' => 'The provided customer ID does not exist.'], 422); 
        
        } catch (\Illuminate\Validation\ValidationException $e) {

            return response()->json(['error' => 'Failed to create transaction', 'message' => $e->getMessage()], 400);

        }
        catch (\Exception $e) {

            return response()->json(['error' => 'Failed to create transaction', 'message' => $e->getMessage()], 500);

        }   
       

       
    }
}