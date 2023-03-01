<?php

use App\Http\Controllers\Backend\ContentController;
use App\Models\Content;
use Tabuna\Breadcrumbs\Trail;


Route::group([
    'prefix' => 'content',
    'as' => 'content.',
], function () {
    Route::group([
        'prefix' => '{content}',
    ], function () {
        Route::get('edit', [ContentController::class, 'edit'])
            ->name('edit')
            ->breadcrumbs(function (Trail $trail, Content $content) {
                $trail->parent('admin.content')
                    ->push(__('Edit'), route('admin.content.edit', $content));
            });
        Route::patch('/', [ContentController::class, 'update'])->name('update');
        Route::delete('/', [ContentController::class, 'destroy'])->name('destroy');
    });
    Route::get('create', [ContentController::class, 'create'])
        ->name('create')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.content')
                ->push(__('Add content'), route('admin.content.create'));
        });
    Route::post('/', [ContentController::class, 'store'])->name('store');
});
