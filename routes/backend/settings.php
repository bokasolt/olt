<?php

use App\Http\Controllers\Backend\SettingsController;
use Tabuna\Breadcrumbs\Trail;

Route::group([
    'prefix' => 'settings',
    'as' => 'settings.',
], function () {
    Route::get('edit', [SettingsController::class, 'edit'])
           ->name('edit')
            ->breadcrumbs(function (Trail $trail) {
                $trail->parent('admin.dashboard')
                    ->push(__('Edit'), route('admin.settings.edit'));
            });
    Route::patch('/', [SettingsController::class, 'update'])->name('update');
});
