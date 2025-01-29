<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Transacao",
 *     type="object",
 *     title="Transacao",
 *     description="Modelo de Transacao",
 *     @OA\Property(
 *         property="value",
 *         type="number",
 *         format="float",
 *         description="Valor da transação"
 *     ),
 *     @OA\Property(
 *         property="user_id",
 *         type="integer",
 *         description="ID do usuário"
 *     ),
 *     @OA\Property(
 *         property="categoria_id",
 *         type="integer",
 *         description="ID da categoria"
 *     ),
 *     @OA\Property(
 *         property="t_tipo",
 *         type="string",
 *         description="Tipo da transação"
 *     ),
 * )
 */
class Transacao extends Model
{
    protected $fillable = ['value', 'user_id', 'categoria_id', 't_tipo'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function tipo()
    {
        return $this->belongsTo(TipoTransacao::class, 'tipo_transacao_id');
    }

}
