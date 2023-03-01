<?php

use App\Http\Controllers\Backend\AhrefsJobController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\OrderController;
use App\Http\Controllers\Backend\ContentController;
use App\Http\Controllers\CKEditorController;
use Tabuna\Breadcrumbs\Trail;

// All route names are prefixed with 'admin.'.
Route::redirect('/', '/admin/dashboard', 301);

Route::get('dashboard', [DashboardController::class, 'index'])
    ->name('dashboard')
    ->breadcrumbs(function (Trail $trail) {
        $trail->push(__('Home'), route('admin.dashboard'));
    });

Route::get('trashed-domains', [DashboardController::class, 'trashedDomains'])
    ->name('trashed-domains')
    ->breadcrumbs(function (Trail $trail) {
        $trail->parent('admin.dashboard')
              ->push(__('Removed Domains'), route('admin.trashed-domains'));
    });

Route::get('orders', [OrderController::class, 'index'])
    ->name('orders')
    ->middleware('role:'.config('boilerplate.access.role.admin'))
    ->breadcrumbs(function (Trail $trail) {
        $trail->parent('admin.dashboard')
              ->push(__('Orders'), route('admin.orders'));
    });

Route::get('content', [ContentController::class, 'index'])
    ->name('content')
    ->middleware('role:'.config('boilerplate.access.role.admin'))
    ->breadcrumbs(function (Trail $trail) {
        $trail->parent('admin.dashboard')
              ->push(__('Content'), route('admin.content'));
    });



Route::get('ahrefs/sync-all', [AhrefsJobController::class, 'createJob'])
    ->name('ahrefs.sync-all');

Route::get('ahrefs/failed', [AhrefsJobController::class, 'index'])
    ->name('ahrefs.failed')
    ->breadcrumbs(function (Trail $trail) {
        $trail->parent('admin.dashboard')
              ->push(__('Ahrefs Errors Report'), route('admin.ahrefs.failed'));
    });

Route::post('ckeditor/image_upload', [CKEditorController::class, 'upload'])->name('upload');

