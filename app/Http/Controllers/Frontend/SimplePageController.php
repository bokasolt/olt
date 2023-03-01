<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Content;
use Illuminate\Http\Request;

/**
 * Class SimplePageController.
 */
class SimplePageController
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        return view('frontend.pages.' . $request->path());
    }
}
