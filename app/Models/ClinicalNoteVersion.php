<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClinicalNoteVersion extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'clinical_note_id',
        'version_number',
        'content',
        'amendment_reason',
        'amended_by_id',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'content' => 'array',
            'version_number' => 'integer',
            'created_at' => 'datetime',
        ];
    }

    public function clinicalNote(): BelongsTo
    {
        return $this->belongsTo(ClinicalNote::class);
    }

    public function amendedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'amended_by_id');
    }
}
