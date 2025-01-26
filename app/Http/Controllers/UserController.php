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
    public function create(Request $request)
    {
        // Validação dos dados
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'cpf' => 'required|string|max:14|unique:users',
            'token_name' => 'required|string',
        ]);

         // Validação e criação do usuário
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'cpf' => $request->cpf,
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

      
        return response()->json(['token' => $token], 201);
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
