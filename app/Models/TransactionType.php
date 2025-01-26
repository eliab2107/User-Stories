<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionType extends Model
{
    use HasFactory;

    protected $fillable = ['nome'];

    // Relacionamentos:
    public function transacoes()
    {
        return $this->hasMany(Transacao::class);
    }
}
