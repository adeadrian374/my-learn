<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pengguna extends Model
{
    //use HasFactory;
    protected $guarded = [];

    public function tikets(): HasMany
    {
        return $this->hasMany(Tiket::class);
    }
}
