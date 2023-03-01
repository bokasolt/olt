<?php

namespace App\Http\Controllers\Frontend\User;

/**
 * Class PurchaseController.
 */
class PurchaseController
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('frontend.user.purchase');
    }
}
