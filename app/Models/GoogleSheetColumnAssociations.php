<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoogleSheetColumnAssociations extends Model
{
    use HasFactory;

    protected $fillable = [
        'gs_column',
        'db_column',
    ];
}
