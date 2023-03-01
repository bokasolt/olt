<?php

namespace App\Models;

use App\Domains\Auth\Models\User;
use Carbon\Traits\Timestamp;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory;
    use Timestamp;
    use SoftDeletes;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
