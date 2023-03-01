<?php


namespace App\Services;


use App\Models\Content;

class MenuService
{
    public function getMenu()
    {
        return Content::whereNotNull('menu_order')->orderBy('menu_order')->orderBy('title')->get();
    }
}