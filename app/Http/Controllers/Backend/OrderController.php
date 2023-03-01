<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests\Backend\Domain\DomainRequest;

class OrderController
{
    public function index()
    {
        return view('backend.orders');
    }
}
