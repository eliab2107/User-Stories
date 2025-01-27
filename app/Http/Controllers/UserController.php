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
    
    public function getAllUsers(Request $request)
    {
        $users = User::all();
        return response()->json($users);
    }
    // Função para deletar a própria conta
    public function delete(Request $request)
    {
        // Excluir o usuário autenticado
        $user = $request->user(); // pega o usuário autenticado
        $user->deleteAccount();

        return response()->json(['message' => 'Conta deletada com sucesso!']);
    }

    public function index()
    {
        // Retorna todas as transações com os relacionamentos
        $transacoes = Transacao::with(['user', 'categoria', 'tipoTransacao'])->get();
        return response()->json($transacoes);
    }

    public function show($id)
    {
        // Retorna uma transação específica com os relacionamentos
        $transacao = Transacao::with(['user', 'categoria', 'tipoTransacao'])->findOrFail($id);
        return response()->json($transacao);
    }
}
