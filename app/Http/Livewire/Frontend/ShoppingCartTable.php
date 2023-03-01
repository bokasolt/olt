<?php


namespace App\Http\Livewire\Frontend;


use App\Models\Domain;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ShoppingCartTable extends DomainsTable
{
    public bool $showFilters = false;

    public function __construct()
    {
        $this->showFilters = false;

        parent::__construct();
    }

    public function query(): Builder
    {
        $query = Domain::withTrashed()
                     ->hasUserAccess(Auth::user());
        return $query;
    }
}
