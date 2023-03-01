<?php

namespace App\Domains\Auth\Services;

use App\Exceptions\DomainUserException;
use App\Models\Domain;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

class DomainUserService
{
    public function allowAccessToDomain(Domain $domain)
    {
        $user = Auth::user();

        if ($user->domains->contains($domain)) {
            throw new DomainUserException('Already bought', 1);
        }

        DB::beginTransaction();

        try {
            $affectedRows = DB::table('users')
                ->where('id', '=', $user->id)
                ->where('balance', '>', 0)
                ->decrement('balance');
            if ($affectedRows !== 1) {
                throw new \Exception('');
            }
        } catch (Throwable $e) {
            DB::rollBack();
            throw new DomainUserException('Not enough balance', 0, $e);
        }

        try {
            $user->domains()->attach($domain);
        } catch (Throwable $e) {
            DB::rollBack();
            throw new DomainUserException('Already bought', 1, $e);
        }

        DB::commit();
    }
}
