<?php

    Route::resource('google-sheet', \App\Http\Controllers\Backend\GoogleSheetController::class);
    Route::post('google-sheet/load-header', [\App\Http\Controllers\Backend\GoogleSheetController::class, 'loadHeaderGoogleSheet'])
        ->name('google-sheet.load');

    Route::get('google-sheet/import/{google_sheet}', [\App\Http\Controllers\Backend\GoogleSheetController::class, 'importPreview'])
        ->name('google-sheet.import');

    Route::post('google-sheet/import/{google_sheet}', [\App\Http\Controllers\Backend\GoogleSheetController::class, 'import'])
        ->name('google-sheet.import');
