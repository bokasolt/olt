<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DomainTempImport extends Domain
{
    use HasFactory;

    public function counterpartDomain()
    {
        return $this->hasOne(Domain::class, 'domain', 'domain');
    }
}
