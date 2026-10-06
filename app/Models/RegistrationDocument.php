<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegistrationDocument extends Model
{
    protected $fillable = ['registration_id', 'jenis', 'nama_file', 'path', 'ukuran'];

    public function registration(): BelongsTo
    {
        return $this->belongsTo(Registration::class);
    }

    public function getUkuranLabelAttribute(): string
    {
        $kb = $this->ukuran / 1024;

        return $kb > 1024
            ? number_format($kb / 1024, 2).' MB'
            : number_format($kb, 0).' KB';
    }
}
