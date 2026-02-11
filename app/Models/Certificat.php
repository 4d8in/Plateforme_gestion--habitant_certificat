<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Certificat extends Model
{
    use HasFactory;

    public const STATUT_EN_ATTENTE = 'en_attente';

    public const STATUT_PAYE = 'paye';

    public const STATUT_DELIVRE = 'delivre';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'habitant_id',
        'date_certificat',
        'statut',
        'montant',
        'reference_paiement',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date_certificat' => 'date',
    ];

    /**
     * Get the habitant that owns the certificat.
     */
    public function habitant(): BelongsTo
    {
        return $this->belongsTo(Habitant::class);
    }
}

