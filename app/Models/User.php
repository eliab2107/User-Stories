<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Model
{
    use HasFactory, Notifiable, HasApiTokens;
    protected $fillable = ['name', 'email', 'password', 'cpf'];
    
    public static function createUserAndToken(Request $request)
    {
        // Validação e criação do usuário
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'cpf' => $request->cpf,
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return $token;
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
}
