<?php
namespace App\Http\Controllers;

use App\Models\Transacao;
use App\Models\User;
use App\Models\Categoria;
use App\Models\TipoTransacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; 

class UserController extends Controller
{

    
    /**
     * @OA\Post(
     *     path="api/user/register",
     *     summary="Register a new user",
     *     tags={"User"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", example="john.doe@example.com"),
     *             @OA\Property(property="password", type="string", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User Register with success",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="response", type="string", example="User Register with success")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Error message")
     *         )
     *     )
     * )
     */
    public function register(Request $request)
    {
        try {
            User::validateDataToRegister($request);
            $request->cpf = str_replace(['.', '-'], '', $request->cpf);
            $user = User::registerUser($request);
            return response()->json(['response' => 'User Register with sucess'], 201);
        } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 400);
        }
    }
    
    /**
     * @OA\Delete(
     *     path="/api/user/delete",
     *     summary="Delete user account",
     *     description="Deletes the authenticated user's account.",
     *     tags={"User"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="token",
     *                 type="string",
     *                 description="Authentication token"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Conta deletada com sucesso!",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Conta deletada com sucesso!"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="error",
     *                 type="string",
     *                 example="Error message"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Usuário não autenticado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="error",
     *                 type="string",
     *                 example="Usuário não autenticado"
     *             )
     *         )
     *     )
     * )
     */
    public function delete(Request $request)
    {
       try {
            $user = $request->user();
            if (!$user) {
                return response()->json(['error' => 'Usuário não autenticado'], 401);
            }
            $user->delete();
            
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
        return response()->json(['message' => 'Conta deletada com sucesso!']);
    }


    /**
 * @OA\Put(
 *     path="/users/{id}",
 *     summary="Atualizar usuário",
 *     description="Atualiza os dados de um usuário",
 *     operationId="updateUser",
 *     tags={"Usuário"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID do usuário",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(ref="#/components/schemas/User")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Usuário atualizado com sucesso",
 *         @OA\JsonContent(ref="#/components/schemas/User")
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Usuário não encontrado"
 *     )
 * )
 */
    public function update(Request $request)
    {
        try {
            $user = $request->user();
            if (!$user) {
            return response()->json(['error' => 'Usuário não autenticado'], 401);
            }

            $validatedData = $request->validate([
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'cpf' => 'nullable|string|unique:users,cpf,' . $user->id
            ]);

            
            if (!empty($validatedData['name'])) {
                $user->name = $validatedData['name'];
            }
            if (!empty($validatedData['email'])) {
                $user->email = $validatedData['email'];
            }
            if (!empty($validatedData['password'])) {
                $user->password = Hash::make($validatedData['password']);
            }
            if (!empty($validatedData['cpf'])) {
                $user->cpf = $validatedData['cpf'];
            }
            $user->save();

            return response()->json(['message' => 'Usuário atualizado com sucesso!', 'user' => $user], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
