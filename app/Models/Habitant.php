<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Habitant extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'telephone',
        'date_naissance',
        'quartier',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date_naissance' => 'date',
    ];

    /**
     * Get the certificats for the habitant.
     */
    public function certificats(): HasMany
    {
        return $this->hasMany(Certificat::class);
    }

    /**
     * Get the habitant's full name.
     */
    public function getNomCompletAttribute(): string
    {
        return sprintf('%s %s', $this->prenom, $this->nom);
    }
}

