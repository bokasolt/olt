<?php

namespace App\Http\Controllers\Frontend\User;

use App\Models\Domain;

/**
 * Class DashboardController.
 */
class DashboardController
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $total = Domain::count();
        return view('frontend.user.dashboard')
            ->withPrice(config('order.price'))
            ->withMinOrder(config('order.min'))
            ->withTotal($total);
    }

    public function shoppingCard()
    {
        return view('frontend.user.shopping-cart');
    }
}
