<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'nombre',
        'codigo',
        'email',
        'password',
        'telefono',
        'nivel',
        'grado',
        'seccion',
        'foto',
        'role_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ── Relaciones ──

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function objetos(): HasMany
    {
        return $this->hasMany(Objeto::class);
    }

    public function reportes(): HasMany
    {
        return $this->hasMany(Reporte::class);
    }

    public function publicaciones(): HasMany
    {
        return $this->hasMany(Publicacion::class);
    }

    // ── Scopes ──

    public function scopeAlumnos($query)
    {
        return $query->whereRelation('role', 'slug', 'alumno');
    }

    public function scopeProfesores($query)
    {
        return $query->whereRelation('role', 'slug', 'profesor');
    }

    public function scopeAdmins($query)
    {
        return $query->whereRelation('role', 'slug', 'administrador');
    }

    // ── Accessors ──

    public function getIsAdminAttribute(): bool
    {
        return $this->role?->slug === 'administrador';
    }

    public function getIsProfesorAttribute(): bool
    {
        return $this->role?->slug === 'profesor';
    }

    public function getIsAlumnoAttribute(): bool
    {
        return $this->role?->slug === 'alumno';
    }
}
