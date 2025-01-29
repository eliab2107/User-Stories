<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; 
use Laravel\Sanctum\PersonalAccessToken;

class User extends Authenticatable
{

/**
 * @OA\Schema(
 *     schema="User",
 *     type="object",
 *     title="Usuário",
 *     description="Modelo de um usuário",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="ID do usuário"
 *     ),
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         description="Nome do usuário"
 *     ),
 *     @OA\Property(
 *         property="email",
 *         type="string",
 *         format="email",
 *         description="Email do usuário"
 *     ),
 *     @OA\Property(
 *         property="password",
 *         type="string",
 *         description="Senha do usuário"
 *     ),
 *     @OA\Property(
 *         property="cpf",
 *         type="string",
 *         description="CPF do usuário"
 *     )
 * )
 */
    use HasFactory, Notifiable, HasApiTokens;
    protected $fillable = ['name', 'email', 'password', 'cpf'];


    public static function validateDataToLogin(Request $request)
    {
        // Validação dos dados
        $request->validate([
            'email' => 'required|email|unique:users',
            'password' => 'required|string',
        ]);
    }

    public static function validateDataToRegister(Request $request)
    {
        // Validação dos dados
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string',
            'cpf' => 'required|string|unique:users',
        ]);
    }

    public static function registerUser(Request $request)
    {
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'cpf' => $request->cpf,
            ]);

            return $user;
        } catch (\Exception $e) {
            // Handle the exception
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
  
     public function deleteAccount()
     {
         // Exclui o usuário
         $this->delete();
     }

    public function categorias()
    {
        return $this->hasMany(Categoria::class);
    }

    public function transacoes()
    {
        return $this->hasMany(Transacao::class);
    }

    public static function getUserByToken($token){
        $tokenModel = PersonalAccessToken::findToken($token);
        if (!$tokenModel || !$tokenModel->tokenable) {
            return null;
        }
        return $tokenModel->tokenable;
    }
}
