<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @OA\Schema(
 *     schema="TipoTransacao",
 *     type="object",
 *     title="Transaction Type",
 *     description="Transaction Type model",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="ID of the transaction type"
 *     ),
 *     @OA\Property(
 *         property="tipo",
 *         type="string",
 *         description="Name of the transaction type"
 *     )
 * )
 */

class TransactionType extends Model
{

    use HasFactory;

    protected $fillable = ['name'];

    public static function getNames(Request $request)
    {
        try {

            $transactionTypes = self::all()->pluck('name', 'id');

        } catch (\Exception $e) {

            return response()->json(['error' => $e->getMessage()], 500);
            
        }
        return response()->json($transactionTypes);
    }

    // Relacionamentos:
    public function transacoes()
    {
        return $this->hasMany(Transacao::class);
    }
}

