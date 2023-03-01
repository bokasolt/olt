<?php

use App\Http\Controllers\Backend\DomainController;
use App\Models\Domain;
use Tabuna\Breadcrumbs\Trail;

Route::group([
    'prefix' => 'domain',
    'as' => 'domain.',
], function () {
    Route::group([
        'prefix' => '{domain}',
    ], function () {
        Route::get('edit', [DomainController::class, 'edit'])
            ->name('edit')
            ->breadcrumbs(function (Trail $trail, Domain $domain) {
                $trail->parent('admin.dashboard')
                    ->push(__('Edit'), route('admin.domain.edit', $domain));
            });
        Route::patch('/', [DomainController::class, 'update'])->name('update');
        Route::delete('/', [DomainController::class, 'destroy'])->name('destroy');
    });
    Route::get('create', [DomainController::class, 'create'])
        ->name('create')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.dashboard')
                ->push(__('Add domain'), route('admin.domain.create'));
        });
    Route::post('/', [DomainController::class, 'store'])->name('store');
});
