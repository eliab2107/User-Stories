<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionType extends Model
{
    use HasFactory;

    protected $fillable = ['nome'];

    public static function getNames(Request $request)
    {
        try {

            $transactionTypes = self::all()->pluck('nome', 'id');

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
