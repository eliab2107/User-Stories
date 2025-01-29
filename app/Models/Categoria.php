<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @OA\Schema(
 *     schema="Categoria",
 *     type="object",
 *     title="Categoria",
 *     description="Categoria model",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="ID of the category"
 *     ),
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         description="Name of the category"
 *     ),
 *     @OA\Property(
 *         property="user",
 *         ref="#/components/schemas/User",
 *         description="User associated with the category"
 *     ),
 *     @OA\Property(
 *         property="transacoes",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/Transacao"),
 *         description="Transactions associated with the category"
 *     )
 * )
 */
class Categoria extends Model
{
    use HasFactory;

    protected $fillable = ['name',  'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transacoes()
    {
        return $this->hasMany(Transacao::class);
    }
}
