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

    // Função para criar uma nova conta de usuário
    public function register(Request $request)
    {
        try {
            User::valideDataToRegister($request);
            $user = User::registerUser($request);
            return response()->json(['response' => 'User Register with sucess'], 201);
        } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 400);
        }
    }
    
   
    // Função para deletar a própria conta
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

    public function update(Request $request)
    {
        try {
            // Verifica se o usuário está autenticado
            $user = $request->user();
            if (!$user) {
            return response()->json(['error' => 'Usuário não autenticado'], 401);
            }

            // Valida os dados recebidos
            $validatedData = $request->validate([
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'cpf' => 'nullable|string|unique:users,cpf,' . $user->id
            ]);

            // Atualiza os campos do usuário apenas se não forem nulos
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

    public function show($id)
    {
        // Retorna uma transação específica com os relacionamentos
        $transacao = Transacao::with(['user', 'categoria', 'tipoTransacao'])->findOrFail($id);
        return response()->json($transacao);
    }
}
