<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Categoria;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;

class CategoryController extends Controller
{
    
    public function getAllByUser(Request $request)
    {
        $user = $this->user->find(auth()->user()->request->id);
        $categories = $user->categories;
        return response()->json($categories);
    }
    // Display a listing of the resource.
    public function index(Request $request)
    {
        $user = $this->user->find(auth()->user()->request->id);
        $categories = Categoria::all();
        return response()->json($categories);
    }

    public function get(Request $request)
    {
        try {
            $user = User::getUserByToken($request->bearerToken());
           
            $categories = Categoria::where('user_id', $user->id)->get();
            return response()->json($categories);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage(), $user], 500);
        }
    }

    // Store a newly created resource in storage.
    public function create(Request $request)
    {
        try {
            $request->validate([
            'name' => 'required|string',
            ]);
            $user_id = Auth::id();
            $category = Categoria::create([
            'name' => $request->name,
            'user_id' => $user_id,
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage(), 'user_id' => $user_id], 500);
        }
        return response()->json(['message' => 'Category created successfully', 'category' => $category], 201);
    }

   
    public function show($id)
    {
        $category = Categoria::find($id);
        if (is_null($category)) {
            return response()->json(['message' => 'Category not found'], 404);
        }
        return response()->json($category);
    }

    // Remove the specified resource from storage.
    public function destroy($id)
    {
        $category = Categoria::find($id);
        if (is_null($category)) {
            return response()->json(['message' => 'Category not found'], 404);
        }
        $category->delete();
        return response()->json(null, 204);
    }

}