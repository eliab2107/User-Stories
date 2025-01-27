<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
