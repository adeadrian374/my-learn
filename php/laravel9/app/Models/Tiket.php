<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tiket extends Model
{
    //use HasFactory;
    protected $guarded = [];
    
    public function pengguna(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class);
    }

    public function transportasi(): BelongsTo
    {
        return $this->belongsTo(Transportasi::class);
    }

    public function bayar(): BelongsTo
    {
        return $this->belongsTo(Bayar::class);
    }
}
