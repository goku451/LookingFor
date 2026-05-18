<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reporte extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nombre_reportante',
        'codigo_reportante',
        'nivel',
        'grado',
        'seccion',
        'correo',
        'telefono',
        'nombre_objeto',
        'tipo',
        'fecha',
        'hora',
        'lugar',
        'descripcion',
        'imagen',
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'fecha' => 'date',
        ];
    }

    // ── Relaciones ──

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
