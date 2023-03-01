<?php

use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Backend\ContentController;
use App\Http\Controllers\PaymentController;
use Tabuna\Breadcrumbs\Trail;

/*
 * Frontend Controllers
 * All route names are prefixed with 'frontend.'.
 */
Route::get('/', [HomeController::class, 'index'])
    ->name('index')
    ->breadcrumbs(function (Trail $trail) {
        $trail->push(__('Home'), route('frontend.index'));
    });

Route::get('content/{path}', [ContentController::class, 'show'])
    ->name('content')
    ->breadcrumbs(function (Trail $trail, $path) {
        $content = \App\Models\Content::wherePath($path)->first();
        if ($content) {
            $trail->parent('frontend.index')
                ->push($content->title, route('frontend.content', $content->path));
        }
    });

Route::get('payment/{order}/process', [PaymentController::class, 'process'])
    ->name('payment.process')
    ->middleware('twocheckout.notification');
