<?php
use Tabuna\Breadcrumbs\Trail;
use App\Http\Controllers\Backend\DomainImportController;

Route::get('import/domain', [DomainImportController::class, 'index'])
    ->name('import.domain.index')
    ->middleware('permission:admin.import.domain')
    ->breadcrumbs(function (Trail $trail) {
        $trail->parent('admin.dashboard')
              ->push(__('Import domains'), route('admin.import.domain.index'));
    });

Route::post('import/domain', [DomainImportController::class, 'import'])
    ->name('import.domain.store')
    ->middleware('permission:admin.import.domain');

Route::post('import/domain/commit', [DomainImportController::class, 'commit'])
    ->name('import.domain.commit')
    ->middleware('permission:admin.import.domain');
