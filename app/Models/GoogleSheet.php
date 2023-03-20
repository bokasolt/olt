<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoogleSheet extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'url'
    ];

    public function associations(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(GoogleSheetColumnAssociations::class);
    }
}
