<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Categoria;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;

class CategoryController extends Controller
{
      /**
     * @OA\Get(
     *     path="/api/category/getallbyuser",
     *     summary="Get all categories by user",
     *     tags={"Categories"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"token"},
     *             @OA\Property( property="token",  type="string",  description="Authentication token" )    
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Find Categories With Sucess",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Find Categories With Sucess")
     *         )
     *     ),
     *    @OA\Response(
     *         response=400,
     *         description="Failed Request Validation",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Conflict"),
     *             @OA\Property(property="message", type="string", example="The provided customer ID does not exist.")
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
     *         response=422,
     *         description="Conflict",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Conflict"),
     *             @OA\Property(property="message", type="string", example="The provided customer ID does not exist.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Categories not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Categories not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Failed to Find Categories",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Failed to Find Categories"),
     *             @OA\Property(property="message", type="string", example="Error message")
     *         )
     *     ),
     *     security={{"bearerAuth":{}}}
     * )
     */

    public function getallbyuser(Request $request)
    {
        try {
            $categories = Categoria::where('user_id', $request->user()->id)->get();

            if (count($categories) == 0) {

                return response()->json(['message' => 'No Categories Found'], 404);

            }
            return response()->json(['message' => 'Find Categories With Sucess', 'categories' => $categories], 201);

        } catch (\Illuminate\Database\QueryException $e) {   
                     
            return response()->json([ 'error' => 'Conflict', 'message' => 'The provided customer ID does not exist.'], 422); 
        
        } catch (\Illuminate\Validation\ValidationException $e) {

            return response()->json(['error' => 'Failed to create transaction', 'message' => $e->getMessage()], 400);

        }
        catch (\Exception $e) {

            return response()->json(['error' => 'Failed to create transaction', 'message' => $e->getMessage()], 500);

        }   
    }


     /**
     * @OA\Get(
     *     path="/api/category/create",
     *     summary="Create a new category",
     *     tags={"Categories"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "token"},
     *             @OA\Property( property="token",  type="string",  description="Authentication token") ,   
     *             @OA\Property( property="name",  type="string",  description="Name of the category" )     
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Create Category With Sucess",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Create Category With Sucess")
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
     *     @OA\Response(
     *         response=401,
     *         description="User not Authenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="User not Authenticated")
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
     *         description="Failed to Create Category",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Failed to Create Category"),
     *             @OA\Property(property="message", type="string", example="Error message")
     *         )
     *     ),
     *     security={{"bearerAuth":{}}}
     * )
     */
   
    public function create(Request $request)
    {
        try {
           
            $request->validate([
            'name' => 'required|string',
            ]);
            
            $category = Categoria::create([
            'name' => $request->name,
            'user_id' => $request->user()->id,
            ]);

            return response()->json(['message' => 'Category created successfully', 'category' => $category], 201);

        } catch (\Illuminate\Database\QueryException $e) {            

                return response()->json([ 'error' => 'Conflict', 'message' => 'The provided customer ID does not exist.'], 422); 
            
        } catch (\Illuminate\Validation\ValidationException $e) {

            return response()->json(['error' => 'Failed to create transaction', 'message' => $e->getMessage()], 400);

        }
        catch (\Exception $e) {

            return response()->json(['error' => 'Failed to create transaction', 'message' => $e->getMessage()], 500);

        }   
        
    }

    /**
     * @OA\Get(
     *     path="/api/category/delete",
     *     summary="Delete a new category",
     *     tags={"Categories"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"category_id", "token"},
     *             @OA\Property( property="token",  type="string",  description="Authentication token"),     
     *             @OA\Property( property="category_id",  type="string",  description="id of the category" )     
     *         )
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Delete Category With Sucess",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Delete Category With Sucess")
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
     *     @OA\Response(
     *         response=401,
     *         description="User not Authenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="User not Authenticated")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Category not Found",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Category not Found")
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
     *         description="Failed to Create Category",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Failed to Create Category"),
     *             @OA\Property(property="message", type="string", example="Error message")
     *         )
     *     ),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function delete(Request $request)
    {
        try {
            $request->validate([
                'category_id' => 'required|integer',
            ]);

            $category = Categoria::where("id", $request->category_id)->where('user_id', $request->user()->id)->first();
            if (!$category) {
                return response()->json(['message' => 'Category not found'], 404);
            }

            $category->delete();
            return response()->json(['message' => 'Category deleted successfully'], 204);
        } catch (\Illuminate\Database\QueryException $e) { 

            return response()->json([ 'error' => 'Conflict', 'message' => 'The provided customer ID does not exist.'], 422); 
        
        } catch (\Illuminate\Validation\ValidationException $e) {

            return response()->json(['error' => 'Failed to create transaction', 'message' => $e->getMessage()], 400);

        }
        catch (\Exception $e) {

            return response()->json(['error' => 'Failed to create transaction', 'message' => $e->getMessage()], 500);

        }   
    }


    /**
     * @OA\Get(
     *     path="/api/category/update",
     *     summary="Update a category",
     *     tags={"Categories"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"category_id", "token", "new_name"},
     *             @OA\Property( property="token",  type="string",  description="Authentication token"),     
     *             @OA\Property( property="category_id",  type="string",  description="id of the category" ),
     *             @OA\Property( property="new_name",  type="string",  description="new name of the category" )     
     *         )
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Delete Category With Sucess",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Update Category With Sucess")
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
     *     @OA\Response(
     *         response=401,
     *         description="User not Authenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="User not Authenticated")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Category not Found",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Category not Found")
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
     *         description="Failed to Create Category",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Failed to Create Category"),
     *             @OA\Property(property="message", type="string", example="Error message")
     *         )
     *     ),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function update(Request $request)
    {
        try {
                     
            $request->validate([
                'new_name' => 'required|string',
                'category_id' => 'required|integer',
            ]);

            $category = Categoria::where($request->category_id)->where('user_id', $request->user()->id)->first();

            if (!$category) {
                return response()->json(['message' => 'Category not found'], 404);
            }

            $category->name = $request->new_name;
            $category->save();

            return response()->json(['message' => 'Category updated successfully'], 200);
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